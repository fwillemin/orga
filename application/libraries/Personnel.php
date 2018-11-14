<?php

/**
 * Classe de gestion des Personnels
 * Manager : Model_Personnels
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Personnel {

    protected $personnelId;
    protected $personnelOriginId;
    protected $personnelEtablissementId;
    protected $personnelNom;
    protected $personnelPrenom;
    protected $personnelQualif;
    protected $personnelActif;
    protected $personnelCode;
    protected $personnelMessage;
    protected $personnelHoraireId;
    protected $personnelHoraire;
    protected $personnelEquipeId;
    protected $personnelEquipe;
    protected $personnelPointages; /* On génère les feuilles de pointages : 1= Au réél des heures saisies, 2 = en suivant l'horaire attribué */
    protected $personnelTauxHoraire;
    protected $personnelTauxHoraireHistorique;

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
        $listeTaux = $CI->managerTauxHoraires->getTauxHoraires(array('tauxHorairePersonnelId' => $this->personnelId), 'tauxHoraireDate ASC');
        if (!empty($listeTaux)):
            $this->personnelTauxHoraire = $listeTaux[0]->getTauxHoraire();
            $this->personnelTauxHoraireHistorique = $listeTaux;
        else:
            $this->personnelTauxHoraire = 0;
            $this->personnelTauxHoraire = array();
        endif;
    }

    public function getTauxHoraireADate($date) {

        if (!$this->personnelTauxHoraireHistorique):
//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Pas de taux horaires pour ce personnel');
            return 0;
        else:

            $tauxADate = 0;
            foreach ($this->personnelTauxHoraireHistorique as $taux):
                if ($taux->getTauxHoraireDate() <= $date):
                    $tauxADate = $taux->getTauxHoraire();
                    continue;
                endif;
            endforeach;

            return $tauxADate;

        endif;
    }

//    public function hydrateTauxHoraires() {
//        $CI = & get_instance();
//        $this->personnelTauxHoraireHistorique = $CI->managerTauxHoraires->getTauxHoraires(array('tauxHorairePersonnelId' => $this->personnelId));
//    }

    public function hydrateHoraire() {
        $CI = & get_instance();
        $this->personnelHoraire = $CI->managerHoraires->getHoraireById($this->personnelHoraireId);
    }

    public function hydrateEquipe() {
        $CI = & get_instance();
        $this->personnelEquipe = $CI->managerEquipes->getEquipeById($this->personnelEquipeId);
    }

    function getPersonnelId() {
        return $this->personnelId;
    }

    function getPersonnelEtablissementId() {
        return $this->personnelEtablissementId;
    }

    function getPersonnelNom() {
        return $this->personnelNom;
    }

    function getPersonnelPrenom() {
        return $this->personnelPrenom;
    }

    function getPersonnelQualif() {
        return $this->personnelQualif;
    }

    function getPersonnelActif() {
        return $this->personnelActif;
    }

    function getPersonnelCode() {
        return $this->personnelCode;
    }

    function getPersonnelMessage() {
        return $this->personnelMessage;
    }

    function getPersonnelHoraireId() {
        return $this->personnelHoraireId;
    }

    function getPersonnelHoraire() {
        return $this->personnelHoraire;
    }

    function getPersonnelEquipeId() {
        return $this->personnelEquipeId;
    }

    function getPersonnelEquipe() {
        return $this->personnelEquipe;
    }

    function setPersonnelId($personnelId) {
        $this->personnelId = $personnelId;
    }

    function setPersonnelEtablissementId($personnelEtablissementId) {
        $this->personnelEtablissementId = $personnelEtablissementId;
    }

    function setPersonnelNom($personnelNom) {
        $this->personnelNom = $personnelNom;
    }

    function setPersonnelPrenom($personnelPrenom) {
        $this->personnelPrenom = $personnelPrenom;
    }

    function setPersonnelQualif($personnelQualif) {
        $this->personnelQualif = $personnelQualif;
    }

    function setPersonnelActif($personnelActif) {
        $this->personnelActif = $personnelActif;
    }

    function setPersonnelCode($personnelCode) {
        $this->personnelCode = $personnelCode;
    }

    function setPersonnelMessage($personnelMessage) {
        $this->personnelMessage = $personnelMessage;
    }

    function setPersonnelHoraireId($personnelHoraireId) {
        $this->personnelHoraireId = $personnelHoraireId;
    }

    function setPersonnelHoraire($personnelHoraire) {
        $this->personnelHoraire = $personnelHoraire;
    }

    function setPersonnelEquipeId($personnelEquipeId) {
        $this->personnelEquipeId = $personnelEquipeId;
    }

    function setPersonnelEquipe($personnelEquipe) {
        $this->personnelEquipe = $personnelEquipe;
    }

    function getPersonnelTauxHoraire() {
        return $this->personnelTauxHoraire;
    }

    function getPersonnelTauxHoraireHistorique() {
        return $this->personnelTauxHoraireHistorique;
    }

    function setPersonnelTauxHoraire($personnelTauxHoraire) {
        $this->personnelTauxHoraire = $personnelTauxHoraire;
    }

    function setPersonnelTauxHoraireHistorique($personnelTauxHoraireHistorique) {
        $this->personnelTauxHoraireHistorique = $personnelTauxHoraireHistorique;
    }

    function getPersonnelOriginId() {
        return $this->personnelOriginId;
    }

    function setPersonnelOriginId($personnelOriginId) {
        $this->personnelOriginId = $personnelOriginId;
    }

    function getPersonnelPointages() {
        return $this->personnelPointages;
    }

    function setPersonnelPointages($personnelPointages) {
        $this->personnelPointages = $personnelPointages;
    }

}
