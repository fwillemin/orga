<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Horaires extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(20, 21)))) :
            redirect('organibat/board');
        endif;

        $this->lang->load('calendar_lang', 'french');
    }

    public function liste() {

        $horaires = $this->managerHoraires->getHoraires();

        $data = array(
            'horaires' => $horaires,
            'title' => 'Horaires',
            'description' => 'Liste des horaires de travail de l\'entreprise',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheHoraire($horaireId = null) {


        if (!$this->ion_auth->in_group(21)):
            redirect('horaires/liste');
        endif;

        if (!$horaireId || !$this->existHoraire($horaireId)):
            redirect('horaires/liste');
        endif;

        $horaire = $this->managerHoraires->getHoraireById($horaireId);

        $data = array(
            'horaire' => $horaire,
            'title' => 'Horaire ' . $horaire->geHoraireNom(),
            'description' => 'Fiche horaire',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addHoraire() {

        if (!$this->form_validation->run('addUtilisateur')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addUserId')):
                $utilisateur = $this->managerUtilisateurs->getUtilisateurById($this->input->post('addUserId'));
                $utilisateur->setUserNom(strtoupper($this->input->post('addUserNom')));
                $utilisateur->setUserPrenom(ucfirst($this->input->post('addUserPrenom')));
                $utilisateur->setEmail($this->input->post('addUserEmail'));
                $this->managerUtilisateurs->editer($utilisateur);

                if ($this->input->post('addUserPassword')):
                    /* Modification du mot de passe */
                    $this->ion_auth_model->reset_password($utilisateur->getUsername(), $this->input->post('addUserPassword'));
                endif;

            else:

                if (!$this->input->post('addUserPassword')):
                    echo json_encode(array('type' => 'error', 'message' => 'Vous devez choisir un mot de passe'));
                    exit;
                endif;

                $additional_data = array(
                    'userNom' => strtoupper($this->input->post('addUserNom')),
                    'userPrenom' => ucfirst($this->input->post('addUserPrenom')),
                    'userEtablissementId' => $this->session->userdata('etablissementId'),
                    'userClairMdp' => '',
                    'userCode' => 0000
                );

                $this->ion_auth->register($this->input->post('addUserEmail'), $this->input->post('addUserPassword'), $this->input->post('addUserEmail'), $additional_data, array('2'));

            endif;
            echo json_encode(array('type' => 'success'));

        endif;
    }

}
