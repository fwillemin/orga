<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
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

    public function existHoraire($horaireId) {
        $this->form_validation->set_message('existHoraire', 'Cet horaire est introuvable.');
        if ($this->managerHoraires->count(array('horaireId' => $horaireId, 'horaireEtablissementId' => $this->session->userdata('etablissementId'))) == 1 || !$horaireId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existPersonnel($personnelId) {
        $this->form_validation->set_message('existPersonnel', 'Ce personnel est introuvable.');
        if ($this->managerPersonnels->count(array('personnelId' => $personnelId, 'personnelEtablissementId' => $this->session->userdata('etablissementId'))) == 1 || !$personnelId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existEquipe($equipeId) {
        $this->form_validation->set_message('existEquipe', 'Cette équipe est introuvable.');
        if ($this->managerEquipes->count(array('equipeId' => $equipeId, 'equipeEtablissementId' => $this->session->userdata('etablissementId'))) == 1 || !$equipeId) :
            return true;
        else :
            return false;
        endif;
    }

}
