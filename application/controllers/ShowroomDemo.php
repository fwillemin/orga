<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class showroom extends My_Controller {
    /* Clé Google Maps API */

    const googleApiKey = "";

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';
    }

    public function index() {
        redirect('secure/login');
    }

    public function noway() {
        $this->output->set_status_header('404');
        $data = array(
            'type' => 'website',
            'url' => site_url('showroom/noway'),
            'image' => '',
            'title' => 'Erreur 404. Page inexistante',
            'description' => 'La page que vous souhaitez n\'existe pas ou a été supprimée.',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/contentShowroom', $data);
    }

}
