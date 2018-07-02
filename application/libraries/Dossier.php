<?php

/**
 * Classe de gestion des Dossiers
 * Les dossiers sont des plannifications directes sans passer par la phase AFFAIRE
 * Ils sont plannifier directement.
 * Manager : Model_dossiers
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/* Supprimer le champs dossierDateSortie */

class Dossier {

    protected $dossierId;
    protected $dossierClient;
    protected $dossierDescriptif;
    protected $dossierSortieEtat; /* 1 = Attente, 2 = Fait */
    protected $dossierPao;
    protected $dossierFab;
    protected $dossierPose;
    protected $dossierClos;
    protected $dossierAffectations;

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

    function hydrateAffectations() {
        $CI = & get_instance();
        $this->dossierAffectations = $CI->managerAffectations->liste(array('affectationDossierId' => $this->dossierId));
    }

    function cloture() {
        $CI = & get_instance();
        $this->dossierClos = 1;
        $this->hydrateAffectations();
        foreach ($this->dossierAffectations as $a):
            $a->setAffectationEtat(3);
            $CI->managerAffectations->editer($a);
        endforeach;
    }

    function ouverture() {
        $CI = & get_instance();
        $this->dossierClos = 0;
    }

    function nextStepSortie() {
        switch ($this->dossierSortieEtat):
            case 1:
                $this->dossierSortieEtat = 2;
                break;
            case 2:
                $this->dossierSortieEtat = 1;
                break;
        endswitch;
    }

    function getDossierId() {
        return $this->dossierId;
    }

    function getDossierClient() {
        return $this->dossierClient;
    }

    function getDossierDescriptif() {
        return $this->dossierDescriptif;
    }

    function getDossierSortieEtat() {
        return $this->dossierSortieEtat;
    }

    function getDossierPao() {
        return $this->dossierPao;
    }

    function getDossierFab() {
        return $this->dossierFab;
    }

    function getDossierPose() {
        return $this->dossierPose;
    }

    function getDossierClos() {
        return $this->dossierClos;
    }

    function getDossierAffectations() {
        return $this->dossierAffectations;
    }

    function setDossierId($dossierId) {
        $this->dossierId = $dossierId;
    }

    function setDossierClient($dossierClient) {
        $this->dossierClient = $dossierClient;
    }

    function setDossierDescriptif($dossierDescriptif) {
        $this->dossierDescriptif = $dossierDescriptif;
    }

    function setDossierSortieEtat($dossierSortieEtat) {
        $this->dossierSortieEtat = $dossierSortieEtat;
    }

    function setDossierPao($dossierPao) {
        $this->dossierPao = $dossierPao;
    }

    function setDossierFab($dossierFab) {
        $this->dossierFab = $dossierFab;
    }

    function setDossierPose($dossierPose) {
        $this->dossierPose = $dossierPose;
    }

    function setDossierClos($dossierClos) {
        $this->dossierClos = $dossierClos;
    }

    function setDossierAffectations($dossierAffectations) {
        $this->dossierAffectations = $dossierAffectations;
    }

}
