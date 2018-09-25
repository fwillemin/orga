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
    protected $fournisseurAchats;

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

    public function hydrateAchats() {
        $CI = & get_instance();
        $this->fournisseurAchats = $CI->managerLivraisons->getLivraisons(array('livraisonFournisseurId' => $this->fournisseurId), 'livraisonEtat ASC', 'object');
    }

    function getFournisseurId() {
        return $this->fournisseurId;
    }

    function getFournisseurOriginId() {
        return $this->fournisseurOriginId;
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

    function getFournisseurAchats() {
        return $this->fournisseurAchats;
    }

    function setFournisseurId($fournisseurId) {
        $this->fournisseurId = $fournisseurId;
    }

    function setFournisseurOriginId($fournisseurOriginId) {
        $this->fournisseurOriginId = $fournisseurOriginId;
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

    function setFournisseurAchats($fournisseurAchats) {
        $this->fournisseurAchats = $fournisseurAchats;
    }

}
