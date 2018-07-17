<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Personnels extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(20, 21)))) :
            redirect('organibat/board');
        endif;
    }

    public function liste() {

        $personnels = $this->managerPersonnels->getPersonnels();
        if (!empty($personnels)):
            foreach ($personnels as $p):
                $p->hydrateEquipe();
            endforeach;
        endif;

        $data = array(
            'equipes' => $this->managerEquipes->getEquipes(),
            'horaires' => $this->managerHoraires->getHoraires(),
            'personnels' => $personnels,
            'title' => 'Personnels',
            'description' => 'Liste du personnel de l\'entreprise',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function fichePersonnel($personnelId = null, $tauxHoraireId = null) {


        if (!$this->ion_auth->in_group(21)):
            redirect('personnels/liste');
        endif;

        if (!$personnelId || !$this->existPersonnel($personnelId)):
            redirect('personnels/liste');
        endif;

        $personnel = $this->managerPersonnels->getPersonnelById($personnelId);
        $personnel->hydrateTauxHoraires();

        if ($tauxHoraireId && $this->existTauxHoraire($tauxHoraireId)):
            $tauxHoraire = $this->managerTauxHoraires->getTauxHoraireById($tauxHoraireId);
        else:
            $tauxHoraire = '';
        endif;

        $data = array(
            'personnel' => $personnel,
            'tauxHoraire' => $tauxHoraire,
            'equipes' => $this->managerEquipes->getEquipes(),
            'horaires' => $this->managerHoraires->getHoraires(),
            'title' => 'Personnel ' . $personnel->getPersonnelNom(),
            'description' => 'Fiche personnel',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addPersonnel() {

        if (!$this->form_validation->run('addPersonnel')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addPersonnelId')):
                $personnel = $this->managerPersonnels->getPersonnelById($this->input->post('addPersonnelId'));
                $personnel->setPersonnelNom(strtoupper($this->input->post('addPersonnelNom')));
                $personnel->setPersonnelPrenom(ucfirst($this->input->post('addPersonnelPrenom')));
                $personnel->setPersonnelQualif($this->input->post('addPersonnelQualif'));
                $personnel->setPersonnelCode($this->input->post('addPersonnelCode'));
                $personnel->setPersonnelMessage($this->input->post('addPersonnelMessage'));
                $personnel->setPersonnelEquipeId($this->input->post('addPersonnelEquipeId') ?: null);
                $personnel->setPersonnelHoraireId($this->input->post('addPersonnelHoraireId') ?: null);
                $personnel->setPersonnelActif($this->input->post('addPersonnelActif') ? 1 : 0);
                $this->managerPersonnels->editer($personnel);

            else:

                $dataPersonnel = array(
                    'personnelEtablissementId' => $this->session->userdata('etablissementId'),
                    'personnelNom' => strtoupper($this->input->post('addPersonnelNom')),
                    'personnelPrenom' => ucfirst($this->input->post('addPersonnelPrenom')),
                    'personnelQualif' => $this->input->post('addPersonnelQualif'),
                    'personnelCode' => $this->input->post('addPersonnelCode'),
                    'personnelMessage' => $this->input->post('addPersonnelMessage'),
                    'personnelEquipeId' => $this->input->post('addPersonnelEquipeId') ?: null,
                    'personnelHoraireId' => $this->input->post('addPersonnelHoraireId') ?: null,
                    'personnelActif' => $this->input->post('addPersonnelActif') ? 1 : 0
                );
                $personnel = new Personnel($dataPersonnel);
                $this->managerPersonnels->ajouter($personnel);

            endif;

            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function equipes($equipeId = null) {

        $equipes = $this->managerEquipes->getEquipes();

        if ($equipeId):
            if ($this->existEquipe($equipeId)):
                $equipe = $this->managerEquipes->getEquipeById($equipeId);
            else:
                redirect('personnels/equipes');
            endif;
        endif;

        $personnels = $this->managerPersonnels->getPersonnels(array('personnelActif' => 1), 'personnelEquipeId');
        if (!empty($personnels)):
            foreach ($personnels as $personnel) {
                $personnel->hydrateEquipe();
            }
        endif;

        $data = array(
            'personnels' => !empty($personnels) ? $personnels : '',
            'equipes' => $equipes,
            'equipe' => $equipeId ? $equipe : '',
            'title' => 'Equipes',
            'description' => 'Liste de vos équipes',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addEquipe() {

        if (!$this->form_validation->run('addEquipe')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        if ($this->input->post('addEquipeId')):

            $equipe = $this->managerEquipes->getEquipeById($this->input->post('addEquipeId'));
            $equipe->setEquipeNom(strtoupper($this->input->post('addEquipeNom')));
            $this->managerEquipes->editer($equipe);

        else:

            $arrayEquipe = array('equipeNom' => strtoupper($this->input->post('addEquipeNom')));
            $equipe = new Equipe($arrayEquipe);
            $this->managerEquipes->ajouter($equipe);

        endif;
        echo json_encode(array('type' => 'success'));
    }

    public function delEquipe() {
        if (!$this->form_validation->run('getEquipe')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $equipe = $this->managerEquipes->getEquipeById($this->input->post('equipeId'));
            $this->managerEquipes->delete($equipe);
            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function affectationPersonnelEquipe() {

        if ($this->form_validation->run('getEquipe') && $this->form_validation->run('getPersonnel')):

            $personnel = $this->managerPersonnels->getPersonnelById($this->input->post('personnelId'));
            if ($personnel->getPersonnelEquipeId() == $this->input->post('equipeId')):
                $personnel->setPersonnelEquipeId(null);
            else:
                $personnel->setPersonnelEquipeId($this->input->post('equipeId'));
            endif;
            $this->managerPersonnels->editer($personnel);
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
    }

    public function addTauxHoraire() {

        if (!$this->form_validation->run('addTauxHoraire')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addTauxHoraireId')):
                $taux = $this->managerTauxHoraires->getTauxHoraireById($this->input->post('addTauxHoraireId'));
                $taux->setTauxHoraire($this->input->post('addTauxHoraire'));
                $taux->setTauxHoraireDate($this->own->mktimeFromInputDate($this->input->post('addTauxHoraireDate')));
                $this->managerTauxHoraires->editer($taux);
            else:

                $dataTaux = array(
                    'tauxHorairePersonnelId' => $this->input->post('addTauxHorairePersonnelId'),
                    'tauxHoraireDate' => $this->own->mktimeFromInputDate($this->input->post('addTauxHoraireDate')),
                    'tauxHoraire' => $this->input->post('addTauxHoraire')
                );
                $taux = new TauxHoraire($dataTaux);
                $this->managerTauxHoraires->ajouter($taux);
            endif;
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function delTauxHoraire() {
        if (!$this->form_validation->run('getTauxHoraire')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $taux = $this->managerTauxHoraires->getTauxHoraireById($this->input->post('tauxHoraireId'));
            $this->managerTauxHoraires->delete($taux);
            echo json_encode(array('type' => 'success'));

        endif;
    }

}
