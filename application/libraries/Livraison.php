<?php

/**
 * Classe de gestion des Livraisons
 * Manager : Model_Livraisons
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Livraison {

    protected $livraisonId;
    protected $livraisonOriginId;
    protected $livraisonChantierId;
    protected $livraisonChantier;
    protected $livraisonFournisseurId;
    protected $livraisonFournisseur;
    protected $livraisonDate;
    protected $livraisonRemarque;
    protected $livraisonEtat;
    protected $livraisonEtatText;

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

        switch ($this->livraisonEtat):
            case 1:
                $this->livraisonEtatText = 'En attente';
                break;
            case 2:
                $this->livraisonEtatText = 'Confirmée';
                break;
            case 3:
                $this->livraisonEtatText = 'Receptionnée';
                break;
        endswitch;
    }

    public function hydrateChantier() {
        $CI = & get_instance();
        $this->livraisonChantier = $CI->managerChantiers->getChantierById($this->livraisonChantierId);
    }

    public function hydrateFournisseur() {
        $CI = & get_instance();
        $this->livraisonFournisseur = $CI->managerFournisseurs->getFournisseurById($this->livraisonFournisseurId);
    }

    function getLivraisonId() {
        return $this->livraisonId;
    }

    function getLivraisonChantierId() {
        return $this->livraisonChantierId;
    }

    function getLivraisonChantier() {
        return $this->livraisonChantier;
    }

    function getLivraisonFournisseurId() {
        return $this->livraisonFournisseurId;
    }

    function getLivraisonFournisseur() {
        return $this->livraisonFournisseur;
    }

    function getLivraisonDate() {
        return $this->livraisonDate;
    }

    function getLivraisonRemarque() {
        return $this->livraisonRemarque;
    }

    function getLivraisonEtat() {
        return $this->livraisonEtat;
    }

    function getLivraisonEtatText() {
        return $this->livraisonEtatText;
    }

    function setLivraisonId($livraisonId) {
        $this->livraisonId = $livraisonId;
    }

    function setLivraisonChantierId($livraisonChantierId) {
        $this->livraisonChantierId = $livraisonChantierId;
    }

    function setLivraisonChantier($livraisonChantier) {
        $this->livraisonChantier = $livraisonChantier;
    }

    function setLivraisonFournisseurId($livraisonFournisseurId) {
        $this->livraisonFournisseurId = $livraisonFournisseurId;
    }

    function setLivraisonFournisseur($livraisonFournisseur) {
        $this->livraisonFournisseur = $livraisonFournisseur;
    }

    function setLivraisonDate($livraisonDate) {
        $this->livraisonDate = $livraisonDate;
    }

    function setLivraisonRemarque($livraisonRemarque) {
        $this->livraisonRemarque = $livraisonRemarque;
    }

    function setLivraisonEtat($livraisonEtat) {
        $this->livraisonEtat = $livraisonEtat;
    }

    function setLivraisonEtatText($livraisonEtatText) {
        $this->livraisonEtatText = $livraisonEtatText;
    }

    function getLivraisonOriginId() {
        return $this->livraisonOriginId;
    }

    function setLivraisonOriginId($livraisonOriginId) {
        $this->livraisonOriginId = $livraisonOriginId;
    }

}
