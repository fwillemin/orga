<?php

/**
 * Classe de gestion des Affaires
 * Manager : Model_Affaires
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Affaire {

    protected $affaireId;
    protected $affaireEtablissementId;
    protected $affaireCreation;
    protected $affaireClientId;
    protected $affaireClient;
    protected $affaireCategorieId;
    protected $affaireCategorie;
    protected $affaireCommercialId;
    protected $affaireCommercial;
    protected $affaireDevis;
    protected $affaireObjet;
    protected $affairePrix;
    protected $affaireDateSignature;
    protected $affaireDateCloture;
    protected $affaireEtat;
    protected $affaireCouleur;
    protected $affaireRemarque;

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

    function getAffaireCreation() {
        return $this->affaireCreation;
    }

    function setAffaireCreation($affaireCreation) {
        $this->affaireCreation = $affaireCreation;
    }

    function getAffaireId() {
        return $this->affaireId;
    }

    function getAffaireEtablissementId() {
        return $this->affaireEtablissementId;
    }

    function getAffaireClientId() {
        return $this->affaireClientId;
    }

    function getAffaireClient() {
        return $this->affaireClient;
    }

    function getAffaireCategorieId() {
        return $this->affaireCategorieId;
    }

    function getAffaireCategorie() {
        return $this->affaireCategorie;
    }

    function getAffaireCommercialId() {
        return $this->affaireCommercialId;
    }

    function getAffaireCommercial() {
        return $this->affaireCommercial;
    }

    function getAffaireDevis() {
        return $this->affaireDevis;
    }

    function getAffaireObjet() {
        return $this->affaireObjet;
    }

    function getAffairePrix() {
        return $this->affairePrix;
    }

    function getAffaireDateSignature() {
        return $this->affaireDateSignature;
    }

    function getAffaireDateCloture() {
        return $this->affaireDateCloture;
    }

    function getAffaireEtat() {
        return $this->affaireEtat;
    }

    function getAffaireCouleur() {
        return $this->affaireCouleur;
    }

    function getAffaireRemarque() {
        return $this->affaireRemarque;
    }

    function setAffaireId($affaireId) {
        $this->affaireId = $affaireId;
    }

    function setAffaireEtablissementId($affaireEtablissementId) {
        $this->affaireEtablissementId = $affaireEtablissementId;
    }

    function setAffaireClientId($affaireClientId) {
        $this->affaireClientId = $affaireClientId;
    }

    function setAffaireClient($affaireClient) {
        $this->affaireClient = $affaireClient;
    }

    function setAffaireCategorieId($affaireCategorieId) {
        $this->affaireCategorieId = $affaireCategorieId;
    }

    function setAffaireCategorie($affaireCategorie) {
        $this->affaireCategorie = $affaireCategorie;
    }

    function setAffaireCommercialId($affaireCommercialId) {
        $this->affaireCommercialId = $affaireCommercialId;
    }

    function setAffaireCommercial($affaireCommercial) {
        $this->affaireCommercial = $affaireCommercial;
    }

    function setAffaireDevis($affaireDevis) {
        $this->affaireDevis = $affaireDevis;
    }

    function setAffaireObjet($affaireObjet) {
        $this->affaireObjet = $affaireObjet;
    }

    function setAffairePrix($affairePrix) {
        $this->affairePrix = $affairePrix;
    }

    function setAffaireDateSignature($affaireDateSignature) {
        $this->affaireDateSignature = $affaireDateSignature;
    }

    function setAffaireDateCloture($affaireDateCloture) {
        $this->affaireDateCloture = $affaireDateCloture;
    }

    function setAffaireEtat($affaireEtat) {
        $this->affaireEtat = $affaireEtat;
    }

    function setAffaireCouleur($affaireCouleur) {
        $this->affaireCouleur = $affaireCouleur;
    }

    function setAffaireRemarque($affaireRemarque) {
        $this->affaireRemarque = $affaireRemarque;
    }

}
