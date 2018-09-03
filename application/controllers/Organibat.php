<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Organibat extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in()) :
            redirect('secure/login');
        endif;
    }

    public function board() {

        $data = array(
            'title' => 'BOARD',
            'description' => 'Tableau de bord',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function deconnexion() {
        $this->ion_auth->logout();
        redirect('secure/login');
    }

    public function noway() {
        $this->output->set_status_header('404');
        $data = array(
            'title' => 'Erreur 404. Page inexistante',
            'description' => 'La page que vous souhaitez n\'existe pas ou a été supprimée.',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function parametres() {

        $data = array(
            'title' => 'Paramètres',
            'description' => 'Faites un Organibat qui vous ressemble',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function modParametres() {

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(1)))) :
            redirect('organibat/board');
            exit;
        endif;
        if (!$this->form_validation->run('modParametres')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $parametre = $this->managerParametres->getParametres();

            $parametre->setNbSemainesAvant($this->input->post('nbSemainesAvant'));
            $parametre->setNbSemainesApres($this->input->post('nbSemainesApres'));
            $parametre->setTranchePointage($this->input->post('tranchePointage'));
            $parametre->setTailleAffectations($this->input->post('tailleAffectations'));
            $this->managerParametres->editer($parametre);

            /* Mise à jour de la session */
            $this->session->unset_userdata('parametres');
            $this->session->set_userdata('parametres', (array) $this->managerParametres->getParametres('array'));

            echo json_encode(array('type' => 'success'));
        endif;
    }

}
