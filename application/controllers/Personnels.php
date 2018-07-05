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

    public function fichePersonnel($personnelId = null) {


        if (!$this->ion_auth->in_group(21)):
            redirect('personnels/liste');
        endif;

        if (!$personnelId || !$this->existPersonnel($personnelId)):
            redirect('personnels/liste');
        endif;

        $personnel = $this->managerPersonnels->getPersonnelById($personnelId);

        $data = array(
            'personnel' => $personnel,
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
                    'personnelActif' => $this->input->post('addPersonnelActif') ? 1 : 0,
                );
                $personnel = new Personnel($dataPersonnel);
                $this->managerPersonnels->ajouter($personnel);

            endif;

            echo json_encode(array('type' => 'success'));

        endif;
    }

}
