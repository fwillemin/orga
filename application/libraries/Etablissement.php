<?php

/**
 * Classe de gestion des Etablissements
 * Manager : Model_Etablissements
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Etablissement {

    protected $etablissementId;
    protected $etablissementOriginId;
    protected $etablissementRsId;
    protected $etablissementNom;
    protected $etablissementAdresse;
    protected $etablissementCp;
    protected $etablissementVille;
    protected $etablissementContact;
    protected $etablissementTelephone;
    protected $etablissementEmail;
    protected $etablissementGps;
    protected $etablissementStatut;
    protected $etablissementChantierDiversId;
    protected $etablissementMessage;
    protected $etablissementTauxFraisGeneraux;
    protected $etablissementTauxHoraireMoyen;

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

    function getEtablissementId() {
        return $this->etablissementId;
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

    function getEtablissementChantierDiversId() {
        return $this->etablissementChantierDiversId;
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

    function setEtablissementChantierDiversId($etablissementChantierDiversId) {
        $this->etablissementChantierDiversId = $etablissementChantierDiversId;
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

    function getEtablissementOriginId() {
        return $this->etablissementOriginId;
    }

    function setEtablissementOriginId($etablissementOriginId) {
        $this->etablissementOriginId = $etablissementOriginId;
    }

}
