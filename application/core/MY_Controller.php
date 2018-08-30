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

    public function existTauxHoraire($tauxId) {
        $this->form_validation->set_message('existTauxHoraire', 'Ce taux horaire est introuvable.');
        if ($this->managerTauxHoraires->count(array('tauxHoraireId' => $tauxId)) == 1 || !$tauxId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existClient($clientId) {
        $this->form_validation->set_message('existClient', 'Ce client est introuvable.');
        if ($this->managerClients->count(array('clientId' => $clientId)) == 1 || !$clientId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existPlace($placeId) {
        $this->form_validation->set_message('existPlace', 'Cette place est introuvable.');
        if ($this->managerPlaces->count(array('placeId' => $placeId)) == 1 || !$placeId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existCategorie($categorieId) {
        $this->form_validation->set_message('existCategorie', 'Cette catégorie est introuvable.');
        if ($this->managerCategories->count(array('categorieId' => $categorieId)) == 1 || !$categorieId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existAffaire($affaireId) {
        $this->form_validation->set_message('existAffaire', 'Cette affaire est introuvable.');
        if ($this->managerAffaires->count(array('affaireId' => $affaireId)) == 1 || !$affaireId) :
            return true;
        else :
            return false;
        endif;
    }

    public function existChantier($chantier) {
        $this->form_validation->set_message('existChantier', 'Ce chantier est introuvable.');
        if ($this->managerChantiers->count(array('chantierId' => $chantier)) == 1 || !$chantier) :
            return true;
        else :
            return false;
        endif;
    }

    public function existAchat($achatId) {
        $this->form_validation->set_message('existAchat', 'Cet achat est introuvable.');
        if ($this->managerAchats->count(array('achatId' => $achatId)) == 1 || !$achatId) :
            return true;
        else :
            return false;
        endif;
    }

    public function isPortable($numero) {
        $this->form_validation->set_message('isPortable', 'Le numéro de portable doit commencer par 06 ou 07 ou +336 ou +337');
        if (preg_match("/^((\+|00)33\s?|0)[67](\s?\d{2}){4}$/", $numero) || !$numero):
            return true;
        else :
            return false;
        endif;
    }

    public function getCouleurSecondaire() {
        echo json_encode(array('type' => 'success', 'couleur' => $this->couleurSecondaire($this->input->post('couleur'))));
    }

    public function couleurSecondaire($couleur) {
        return $this->own->getCouleurSecondaire($couleur, 200);
    }

}
