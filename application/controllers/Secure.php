<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Secure extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__);

        if ($this->ion_auth->logged_in()) :
            redirect('organibat/');
            exit;
        endif;
    }

    /**
     * page de login
     */
    public function login() {
        $data = array(
            'map_enable' => '',
            'title' => 'Connexion à la console.',
            'description' => 'Saississez vos identifiants pour accèder à la console.',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function tryLogin() {
        if (!$this->form_validation->run('identification')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            /* On teste la demande de connexion */
            if ($this->ion_auth->login($this->input->post('login'), $this->input->post('pass'), 0)) :
                echo json_encode(array('type' => 'success'));
            else :
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' MAUVAIS ID DE CONNEXION');
                echo json_encode(array('type' => 'error', 'message' => 'Identifiants de connexion invalides.'));
            endif;
        endif;
    }

//    public function addAdminUser() {
//
//        $email = 'rudy@test.fr';
//        $identity = 'rudy';
//        $password = 'rfed2017';
//
//        $additional_data = array(
//            'first_name' => 'Rudy',
//            'last_name' => '',
//            'company' => '',
//            'phone' => '0651731808',
//        );
//
//        /* Admin */
//        $group = array('1');
//
//        $this->ion_auth->register($identity, $password, $email, $additional_data, $group);
//    }
}
