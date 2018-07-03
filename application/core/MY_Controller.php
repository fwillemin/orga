<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->postes = array('1' => 'Fabrication', '2' => 'Pose', '3' => 'PAO', '4' => 'Dépannage');
    }

    public function passwordCheck($str) {
        $this->form_validation->set_message('passwordCheck', 'Votre mot de passe doit contenir au moins une lettre et un chiffre');
        if (!$str || (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str))) {
            return TRUE;
        }
        return FALSE;
    }

    public function existUtilisateur($utilisateurId) {
        $this->form_validation->set_message('existUtilisateur', 'Cet utilisateur est introuvable.');
        if ($this->managerUtilisateurs->count(array('id' => $utilisateurId, 'userEtablissementId' => $this->session->userdata('etablissementId'))) == 1 || !$utilisateurId) :
            return true;
        else :
            return false;
        endif;
    }

}
