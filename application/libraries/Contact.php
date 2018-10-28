<?php

/**
 * Classe de gestion des Contacts
 * Manager : Model_Contacts
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Contact {

    protected $contactId;
    protected $contactEtablissementId;
    protected $contactDate;
    protected $contactMode;
    protected $contactModeText;
    protected $contactNom;
    protected $contactAdresse;
    protected $contactCp;
    protected $contactVille;
    protected $contactTelephone;
    protected $contactEmail;
    protected $contactObjet;
    protected $contactCategorieId;
    protected $contactCategorie;
    protected $contactSource;
    protected $contactSourceText;
    protected $contactCommercialId;
    protected $contactCommercial;
    protected $contactEtat;
    protected $contactEtatText;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value):
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        endforeach;
        $CI = & get_instance();
        $this->contactCategorie = $CI->managerCategories->getCategorieById($this->contactCategorieId);
        switch ($this->contactMode):
            case 1:
                $this->contactModeText = 'Appel entrant';
                break;
            case 2:
                $this->contactModeText = 'Email';
                break;
            case 3:
                $this->contactModeText = 'Visite showroom';
                break;
        endswitch;
        switch ($this->contactEtat):
            case 1:
                $this->contactEtatText = 'Non traité';
                break;
            case 2:
                $this->contactEtatText = 'Sans suite';
                break;
            case 3:
                $this->contactEtatText = 'Devis';
                break;
            case 4:
                $this->contactEtatText = 'Conclu';
                break;
            case 5:
                $this->contactEtatText = 'Perdu';
                break;
        endswitch;
        switch ($this->contactSource):
            case 1:
                $this->contactSourceText = 'Spontané';
                break;
            case 2:
                $this->contactSourceText = 'Publicité boite aux lettres';
                break;
            case 3:
                $this->contactSourceText = 'Publicité voie publique';
                break;
            case 4:
                $this->contactSourceText = 'Site internet/Google';
                break;
            case 5:
                $this->contactSourceText = 'Amis/Connaissances';
                break;
            case 6:
                $this->contactSourceText = 'Pages jaunes';
                break;
        endswitch;
        $this->contactCategorie = $CI->managerCategories->getCategorieById($this->contactCategorieId);
    }

    public function hydrateCommercial() {
        $CI = & get_instance();
        $this->contactUsers = $CI->managerUsers->getUserById($this->contactCommercialId);
    }

    function getContactId() {
        return $this->contactId;
    }

    function getContactEtablissementId() {
        return $this->contactEtablissementId;
    }

    function getContactDate() {
        return $this->contactDate;
    }

    function getContactMode() {
        return $this->contactMode;
    }

    function getContactModeText() {
        return $this->contactModeText;
    }

    function getContactNom() {
        return $this->contactNom;
    }

    function getContactAdresse() {
        return $this->contactAdresse;
    }

    function getContactCp() {
        return $this->contactCp;
    }

    function getContactVille() {
        return $this->contactVille;
    }

    function getContactTelephone() {
        return $this->contactTelephone;
    }

    function getContactEmail() {
        return $this->contactEmail;
    }

    function getContactObjet() {
        return $this->contactObjet;
    }

    function getContactCategorieId() {
        return $this->contactCategorieId;
    }

    function getContactCategorie() {
        return $this->contactCategorie;
    }

    function getContactSource() {
        return $this->contactSource;
    }

    function getContactSourceText() {
        return $this->contactSourceText;
    }

    function getContactCommercialId() {
        return $this->contactCommercialId;
    }

    function getContactCommercial() {
        return $this->contactCommercial;
    }

    function getContactEtat() {
        return $this->contactEtat;
    }

    function getContactEtatText() {
        return $this->contactEtatText;
    }

    function setContactId($contactId) {
        $this->contactId = $contactId;
    }

    function setContactEtablissementId($contactEtablissementId) {
        $this->contactEtablissementId = $contactEtablissementId;
    }

    function setContactDate($contactDate) {
        $this->contactDate = $contactDate;
    }

    function setContactMode($contactMode) {
        $this->contactMode = $contactMode;
    }

    function setContactModeText($contactModeText) {
        $this->contactModeText = $contactModeText;
    }

    function setContactNom($contactNom) {
        $this->contactNom = $contactNom;
    }

    function setContactAdresse($contactAdresse) {
        $this->contactAdresse = $contactAdresse;
    }

    function setContactCp($contactCp) {
        $this->contactCp = $contactCp;
    }

    function setContactVille($contactVille) {
        $this->contactVille = $contactVille;
    }

    function setContactTelephone($contactTelephone) {
        $this->contactTelephone = $contactTelephone;
    }

    function setContactEmail($contactEmail) {
        $this->contactEmail = $contactEmail;
    }

    function setContactObjet($contactObjet) {
        $this->contactObjet = $contactObjet;
    }

    function setContactCategorieId($contactCategorieId) {
        $this->contactCategorieId = $contactCategorieId;
    }

    function setContactCategorie($contactCategorie) {
        $this->contactCategorie = $contactCategorie;
    }

    function setContactSource($contactSource) {
        $this->contactSource = $contactSource;
    }

    function setContactSourceText($contactSourceText) {
        $this->contactSourceText = $contactSourceText;
    }

    function setContactCommercialId($contactCommercialId) {
        $this->contactCommercialId = $contactCommercialId;
    }

    function setContactCommercial($contactCommercial) {
        $this->contactCommercial = $contactCommercial;
    }

    function setContactEtat($contactEtat) {
        $this->contactEtat = $contactEtat;
    }

    function setContactEtatText($contactEtatText) {
        $this->contactEtatText = $contactEtatText;
    }

}
