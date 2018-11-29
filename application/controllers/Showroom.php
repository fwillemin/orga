<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class showroom extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';
    }

    public function index() {

        $data = array(
            'title' => 'Gestion et planification de chantiers et d\'interventions',
            'description' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentShowroom', $data);
    }

}
