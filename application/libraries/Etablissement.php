<?php

/**
 * Classe de gestion des Etablissements
 * Manager : Model_Etablissements
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 *
  ALTER TABLE `etablissements` ADD `etablissementBaseHebdomadaire` DECIMAL(6,2) NOT NULL COMMENT 'Nb h\'eures d\'une semaine. Somme des heures des horaires du personnel actif' AFTER `etablissementTauxHoraireMoyen`;

  /*

 */
class Etablissement {

    protected $etablissementId;
    protected $etablissementOriginId;
    protected $etablissementRsId;
    protected $etablissementRs;
    protected $etablissementNom;
    protected $etablissementAdresse;
    protected $etablissementCp;
    protected $etablissementVille;
    protected $etablissementContact;
    protected $etablissementTelephone;
    protected $etablissementEmail;
    protected $etablissementGps;
    protected $etablissementStatut;
    protected $etablissementAffaireDiversId;
    protected $etablissementMessage;
    protected $etablissementTauxFraisGeneraux;
    protected $etablissementTauxHoraireMoyen;
    protected $etablissementBaseHebdomadaire;

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

    public function hydrateRs() {
        $CI = & get_instance();
        $this->etablissementRs = $CI->managerRaisonsSociales->getRaisonSocialeById($this->etablissementRsId);
    }

    function getEtablissementId() {
        return $this->etablissementId;
    }

    function getEtablissementOriginId() {
        return $this->etablissementOriginId;
    }

    function getEtablissementRsId() {
        return $this->etablissementRsId;
    }

    function getEtablissementNom() {
        return $this->etablissementNom;
    }

    function getEtablissementAdresse() {
        return $this->etablissementAdresse;
    }

    function getEtablissementCp() {
        return $this->etablissementCp;
    }

    function getEtablissementVille() {
        return $this->etablissementVille;
    }

    function getEtablissementContact() {
        return $this->etablissementContact;
    }

    function getEtablissementTelephone() {
        return $this->etablissementTelephone;
    }

    function getEtablissementEmail() {
        return $this->etablissementEmail;
    }

    function getEtablissementGps() {
        return $this->etablissementGps;
    }

    function getEtablissementStatut() {
        return $this->etablissementStatut;
    }

    function getEtablissementAffaireDiversId() {
        return $this->etablissementAffaireDiversId;
    }

    function getEtablissementMessage() {
        return $this->etablissementMessage;
    }

    function getEtablissementTauxFraisGeneraux() {
        return $this->etablissementTauxFraisGeneraux;
    }

    function getEtablissementTauxHoraireMoyen() {
        return $this->etablissementTauxHoraireMoyen;
    }

    function setEtablissementId($etablissementId) {
        $this->etablissementId = $etablissementId;
    }

    function setEtablissementOriginId($etablissementOriginId) {
        $this->etablissementOriginId = $etablissementOriginId;
    }

    function setEtablissementRsId($etablissementRsId) {
        $this->etablissementRsId = $etablissementRsId;
    }

    function setEtablissementNom($etablissementNom) {
        $this->etablissementNom = $etablissementNom;
    }

    function setEtablissementAdresse($etablissementAdresse) {
        $this->etablissementAdresse = $etablissementAdresse;
    }

    function setEtablissementCp($etablissementCp) {
        $this->etablissementCp = $etablissementCp;
    }

    function setEtablissementVille($etablissementVille) {
        $this->etablissementVille = $etablissementVille;
    }

    function setEtablissementContact($etablissementContact) {
        $this->etablissementContact = $etablissementContact;
    }

    function setEtablissementTelephone($etablissementTelephone) {
        $this->etablissementTelephone = $etablissementTelephone;
    }

    function setEtablissementEmail($etablissementEmail) {
        $this->etablissementEmail = $etablissementEmail;
    }

    function setEtablissementGps($etablissementGps) {
        $this->etablissementGps = $etablissementGps;
    }

    function setEtablissementStatut($etablissementStatut) {
        $this->etablissementStatut = $etablissementStatut;
    }

    function setEtablissementAffaireDiversId($etablissementAffaireDiversId) {
        $this->etablissementAffaireDiversId = $etablissementAffaireDiversId;
    }

    function setEtablissementMessage($etablissementMessage) {
        $this->etablissementMessage = $etablissementMessage;
    }

    function setEtablissementTauxFraisGeneraux($etablissementTauxFraisGeneraux) {
        $this->etablissementTauxFraisGeneraux = $etablissementTauxFraisGeneraux;
    }

    function setEtablissementTauxHoraireMoyen($etablissementTauxHoraireMoyen) {
        $this->etablissementTauxHoraireMoyen = $etablissementTauxHoraireMoyen;
    }

    function getEtablissementRs() {
        return $this->etablissementRs;
    }

    function setEtablissementRs($etablissementRs) {
        $this->etablissementRs = $etablissementRs;
    }

    function getEtablissementBaseHebdomadaire() {
        return $this->etablissementBaseHebdomadaire;
    }

    function setEtablissementBaseHebdomadaire($etablissementBaseHebdomadaire) {
        $this->etablissementBaseHebdomadaire = $etablissementBaseHebdomadaire;
    }

}
