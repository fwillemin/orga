<?php

/**
 * Classe de gestion des Fournisseurs
 * Manager : Model_Fournisseurs
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Fournisseur {

    protected $fournisseurId;
    protected $fournisseurOriginId;
    protected $fournisseurEtablissementId;
    protected $fournisseurNom;
    protected $fournisseurAdresse;
    protected $fournisseurCp;
    protected $fournisseurVille;
    protected $fournisseurTelephone;
    protected $fournisseurEmail;
    protected $fournisseurLivraisons;

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
    }

    public function hydrateLivraisons() {
        $CI = & get_instance();
        $this->fournisseurLivraisons = $CI->managerLivraisons->getLivraisons(array('livraisonFournisseurId' => $this->fournisseurId), 'livraisonEtat ASC', 'object');
    }

    function getFournisseurId() {
        return $this->fournisseurId;
    }

    function getFournisseurEtablissementId() {
        return $this->fournisseurEtablissementId;
    }

    function getFournisseurNom() {
        return $this->fournisseurNom;
    }

    function getFournisseurAdresse() {
        return $this->fournisseurAdresse;
    }

    function getFournisseurCp() {
        return $this->fournisseurCp;
    }

    function getFournisseurVille() {
        return $this->fournisseurVille;
    }

    function getFournisseurTelephone() {
        return $this->fournisseurTelephone;
    }

    function getFournisseurEmail() {
        return $this->fournisseurEmail;
    }

    function setFournisseurId($fournisseurId) {
        $this->fournisseurId = $fournisseurId;
    }

    function setFournisseurEtablissementId($fournisseurEtablissementId) {
        $this->fournisseurEtablissementId = $fournisseurEtablissementId;
    }

    function setFournisseurNom($fournisseurNom) {
        $this->fournisseurNom = $fournisseurNom;
    }

    function setFournisseurAdresse($fournisseurAdresse) {
        $this->fournisseurAdresse = $fournisseurAdresse;
    }

    function setFournisseurCp($fournisseurCp) {
        $this->fournisseurCp = $fournisseurCp;
    }

    function setFournisseurVille($fournisseurVille) {
        $this->fournisseurVille = $fournisseurVille;
    }

    function setFournisseurTelephone($fournisseurTelephone) {
        $this->fournisseurTelephone = $fournisseurTelephone;
    }

    function setFournisseurEmail($fournisseurEmail) {
        $this->fournisseurEmail = $fournisseurEmail;
    }

    function getFournisseurLivraisons() {
        return $this->fournisseurLivraisons;
    }

    function setFournisseurLivraisons($fournisseurLivraisons) {
        $this->fournisseurLivraisons = $fournisseurLivraisons;
    }

    function getFournisseurOriginId() {
        return $this->fournisseurOriginId;
    }

    function setFournisseurOriginId($fournisseurOriginId) {
        $this->fournisseurOriginId = $fournisseurOriginId;
    }

}
