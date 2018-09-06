<?php

/**
 * Classe de gestion des Affectations
 * Manager : Model_Affectations
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Affectation {

    protected $affectationId;
    protected $affectationOriginId;
    protected $affectationChantierId;
    protected $affectationChantier;
    protected $affectationPersonnelId;
    protected $affectationPersonnel;
    protected $affectationPlaceId;
    protected $affectationPlace;
    /* -- */
    protected $affectationNbDemi;
    protected $affectationDebut;
    protected $affectationDebutMoment; /* 1 Matin, 2 Aprem */
    protected $affectationFin;
    protected $affectationFinMoment; /* 1 Matin, 2 Aprem */
    protected $affectationCases; /* Nombre de case du planning incluant les week-end */
    /* --  */
    protected $affectationEtat;
    protected $affectationChantierEtat; /* Etat du chantier pere */
    protected $affectationCommentaire;
    protected $affectationType;
    protected $affectationAffichage; /* 1 FULL, 2 BAS, 3 HAUT */
    /* --- */
    protected $affectationAffaire;
    protected $affectationClient;
    protected $affectationHTML;

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

    public function hydrateChantier() {
        $CI = & get_instance();
        $this->affectationChantier = $CI->managerChantiers->getChantierById($this->affectationChantierId);
    }

    public function hydratePersonnel() {
        $CI = & get_instance();
        $this->affectationPersonnel = $CI->managerPersonnels->getPersonnelById($this->affectationPersonelId);
    }

    public function hydratePlace() {
        $CI = & get_instance();
        $this->affectationPlace = $CI->managerPlaces->getPlaceById($this->affectationPlaceId);
    }

    public function hydrateOrigines() {
        $CI = & get_instance();
        if (!$this->affectationChantier):
            $this->hydrateChantier();
        endif;
        $this->affectationAffaire = $CI->managerAffaires->getAffaireById($this->affectationChantier->getChantierAffaireId());
        $this->affectationClient = $CI->managerClients->getClientById($this->affectationAffaire->getAffaireClientId());
    }

    public function getHTML() {

    }

    function getAffectationId() {
        return $this->affectationId;
    }

    function getAffectationOriginId() {
        return $this->affectationOriginId;
    }

    function getAffectationChantierId() {
        return $this->affectationChantierId;
    }

    function getAffectationChantier() {
        return $this->affectationChantier;
    }

    function getAffectationPersonnelId() {
        return $this->affectationPersonnelId;
    }

    function getAffectationPersonnel() {
        return $this->affectationPersonnel;
    }

    function getAffectationPlaceId() {
        return $this->affectationPlaceId;
    }

    function getAffectationPlace() {
        return $this->affectationPlace;
    }

    function getAffectationNbDemi() {
        return $this->affectationNbDemi;
    }

    function getAffectationDebut() {
        return $this->affectationDebut;
    }

    function getAffectationDebutMoment() {
        return $this->affectationDebutMoment;
    }

    function getAffectationFin() {
        return $this->affectationFin;
    }

    function getAffectationFinMoment() {
        return $this->affectationFinMoment;
    }

    function getAffectationCases() {
        return $this->affectationCases;
    }

    function getAffectationEtat() {
        return $this->affectationEtat;
    }

    function getAffectationChantierEtat() {
        return $this->affectationChantierEtat;
    }

    function getAffectationCommentaire() {
        return $this->affectationCommentaire;
    }

    function getAffectationType() {
        return $this->affectationType;
    }

    function getAffectationAffichage() {
        return $this->affectationAffichage;
    }

    function getAffectationAffaire() {
        return $this->affectationAffaire;
    }

    function getAffectationClient() {
        return $this->affectationClient;
    }

    function getAffectationHTML() {
        return $this->affectationHTML;
    }

    function setAffectationId($affectationId) {
        $this->affectationId = $affectationId;
    }

    function setAffectationOriginId($affectationOriginId) {
        $this->affectationOriginId = $affectationOriginId;
    }

    function setAffectationChantierId($affectationChantierId) {
        $this->affectationChantierId = $affectationChantierId;
    }

    function setAffectationChantier($affectationChantier) {
        $this->affectationChantier = $affectationChantier;
    }

    function setAffectationPersonnelId($affectationPersonnelId) {
        $this->affectationPersonnelId = $affectationPersonnelId;
    }

    function setAffectationPersonnel($affectationPersonnel) {
        $this->affectationPersonnel = $affectationPersonnel;
    }

    function setAffectationPlaceId($affectationPlaceId) {
        $this->affectationPlaceId = $affectationPlaceId;
    }

    function setAffectationPlace($affectationPlace) {
        $this->affectationPlace = $affectationPlace;
    }

    function setAffectationNbDemi($affectationNbDemi) {
        $this->affectationNbDemi = $affectationNbDemi;
    }

    function setAffectationDebut($affectationDebut) {
        $this->affectationDebut = $affectationDebut;
    }

    function setAffectationDebutMoment($affectationDebutMoment) {
        $this->affectationDebutMoment = $affectationDebutMoment;
    }

    function setAffectationFin($affectationFin) {
        $this->affectationFin = $affectationFin;
    }

    function setAffectationFinMoment($affectationFinMoment) {
        $this->affectationFinMoment = $affectationFinMoment;
    }

    function setAffectationCases($affectationCases) {
        $this->affectationCases = $affectationCases;
    }

    function setAffectationEtat($affectationEtat) {
        $this->affectationEtat = $affectationEtat;
    }

    function setAffectationChantierEtat($affectationChantierEtat) {
        $this->affectationChantierEtat = $affectationChantierEtat;
    }

    function setAffectationCommentaire($affectationCommentaire) {
        $this->affectationCommentaire = $affectationCommentaire;
    }

    function setAffectationType($affectationType) {
        $this->affectationType = $affectationType;
    }

    function setAffectationAffichage($affectationAffichage) {
        $this->affectationAffichage = $affectationAffichage;
    }

    function setAffectationAffaire($affectationAffaire) {
        $this->affectationAffaire = $affectationAffaire;
    }

    function setAffectationClient($affectationClient) {
        $this->affectationClient = $affectationClient;
    }

    function setAffectationHTML($affectationHTML) {
        $this->affectationHTML = $affectationHTML;
    }

}
