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

}
