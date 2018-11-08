<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Personnels extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(25, 26)))) :
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
        //$personnel->hydrateTauxHoraires();
        $personnel->hydrateHoraire();

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

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

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
                $personnel->setPersonnelPointages($this->input->post('addPersonnelHoraireId') ? $this->input->post('addPersonnelPointages') : 1);
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
                    'personnelPointages' => $this->input->post('addPersonnelHoraireId') ? $this->input->post('addPersonnelPointages') : 1,
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

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

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

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

        if (!$this->form_validation->run('getEquipe')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $equipe = $this->managerEquipes->getEquipeById($this->input->post('equipeId'));
            $this->managerEquipes->delete($equipe);
            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function affectationPersonnelEquipe() {

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

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

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

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

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

        if (!$this->form_validation->run('getTauxHoraire')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $taux = $this->managerTauxHoraires->getTauxHoraireById($this->input->post('tauxHoraireId'));
            $this->managerTauxHoraires->delete($taux);
            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function addIndisponibilite() {
        if (!$this->form_validation->run('addIndispo')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            /* Personnels du planning */
            $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($this->session->userdata('planningPersonnelsIds'));
            if (!empty($personnelsPlanning)):
                foreach ($personnelsPlanning as $personnel):
                    $personnel->hydrateEquipe();
                endforeach;
            endif;

            $debutDate = $this->own->mktimeFromInputDate($this->input->post('addIndispoDebutDate'));
            $finDate = $this->own->mktimeFromInputDate($this->input->post('addIndispoFinDate'));
            $nbDemi = $this->cal->nbDemiEntreDates($debutDate, $this->input->post('addIndispoDebutMoment'), $finDate, $this->input->post('addIndispoFinMoment'));
            $cases = $this->cal->nbCasesEntreDates($debutDate, $this->input->post('addIndispoDebutMoment'), $finDate, $this->input->post('addIndispoFinMoment'));

            if ($this->input->post('addIndispoId')):
                $indispo = $this->managerIndisponibilites->getIndisponibiliteById($this->input->post('addIndispoId'));
                $indispo->setIndispoDebutDate($debutDate);
                $indispo->setIndispoFinDate($finDate);
                $indispo->setIndispoDebutMoment($this->input->post('addIndispoDebutMoment'));
                $indispo->setIndispoFinMoment($this->input->post('addIndispoFinMoment'));
                $indispo->setIndispoNbDemi($nbDemi);
                $indispo->setIndispoCases($cases);
                $indispo->setIndispoMotifId($this->input->post('addIndispoMotifId'));

                $this->managerIndisponibilites->editer($indispo);
                $indispo->hydrateMotif();
                $indispo->genereHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                $HTML = $indispo->getIndispoHTML();

            else:
                $HTML = '';
                $arrayIndispo = array(
                    'indispoDebutDate' => $debutDate,
                    'indispoFinDate' => $finDate,
                    'indispoDebutMoment' => $this->input->post('addIndispoDebutMoment'),
                    'indispoFinMoment' => $this->input->post('addIndispoFinMoment'),
                    'indispoNbDemi' => $nbDemi,
                    'indispoCases' => $cases,
                    'indispoMotifId' => $this->input->post('addIndispoMotifId'),
                    'indispoAffichage' => 1
                );

                foreach ($this->input->post('addIndispoPersonnelsIds') as $personnelId):

                    $arrayIndispo['indispoPersonnelId'] = $personnelId;
                    $indispo = new Indisponibilite($arrayIndispo);
                    $this->managerIndisponibilites->ajouter($indispo);
                    $indispo->genereHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                    $HTML .= $indispo->getIndispoHTML();
                    unset($indispo);

                endforeach;

            endif;
            echo json_encode(array('type' => 'success', 'HTML' => $HTML));
        endif;
    }

    public function getIndisponibiliteDetails() {
        if (!$this->form_validation->run('getIndispo')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $indispo = $this->managerIndisponibilites->getIndisponibiliteById($this->input->post('indispoId'), 'array');

            echo json_encode(array('type' => 'success', 'indispo' => $indispo));
        endif;
    }

    /* Passe l'affectation à un affichage FULL, BAS, HAUT */

    public function indisponibiliteToggleAffichage() {
        if (!$this->form_validation->run('getIndispo')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $indispo = $this->managerIndisponibilites->getIndisponibiliteById($this->input->post('indispoId'));
            $indispo->toggleAffichage();
            $indispo->genereHTML($this->session->userdata('premierJourPlanning'), array(), $this->input->post('ligne'), $this->hauteur, $this->largeur);
            $this->managerIndisponibilites->editer($indispo);
            echo json_encode(array('type' => 'success', 'html' => $indispo->getIndispoHTML()));
        endif;
    }

    public function delIndisponibilite() {
        if (!$this->ion_auth->in_group(26)):
            echo json_encode(array('type' => 'error', 'message' => 'Vous ne possédez pas les droits necessaires'));
        elseif (!$this->form_validation->run('getIndispo')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $indispo = $this->managerIndisponibilites->getIndisponibiliteById($this->input->post('indispoId'));
            $this->managerIndisponibilites->delete($indispo);
            echo json_encode(array('type' => 'success'));
        endif;
    }

}
