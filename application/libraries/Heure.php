<?php

/**
 * Classe de gestion des Heures
 * Manager : Model_Heures
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Heure {

    protected $heureId;
    protected $heureOriginId;
    protected $heureAffectationId;
    protected $heureAffectation;
    protected $heurePersonnelId;
    protected $heureDate;
    protected $heureDuree; /* en minutes */
    protected $heureValide;

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

    public function hydrateAffectation() {
        $CI = & get_instance();
        $this->heureAffectation = $CI->managerAffectations->getAffectationById($this->heureAffectationId);
        $this->heurePersonnelId = $this->heureAffectation->getAffectationPersonnelId();
    }

    public function valide() {
        $this->heureValide = 1;
    }

    function getHeureId() {
        return $this->heureId;
    }

    function getHeureOriginId() {
        return $this->heureOriginId;
    }

    function getHeureAffectationId() {
        return $this->heureAffectationId;
    }

    function getHeureAffectation() {
        return $this->heureAffectation;
    }

    function getHeureDate() {
        return $this->heureDate;
    }

    function getHeureValide() {
        return $this->heureValide;
    }

    function setHeureId($heureId) {
        $this->heureId = $heureId;
    }

    function setHeureOriginId($heureOriginId) {
        $this->heureOriginId = $heureOriginId;
    }

    function setHeureAffectationId($heureAffectationId) {
        $this->heureAffectationId = $heureAffectationId;
    }

    function setHeureAffectation($heureAffectation) {
        $this->heureAffectation = $heureAffectation;
    }

    function setHeureDate($heureDate) {
        $this->heureDate = $heureDate;
    }

    function setHeureValide($heureValide) {
        $this->heureValide = $heureValide;
    }

    function getHeurePersonnelId() {
        return $this->heurePersonnelId;
    }

    function setHeurePersonnelId($heurePersonnelId) {
        $this->heurePersonnelId = $heurePersonnelId;
    }

    function getHeureDuree() {
        return $this->heureDuree;
    }

    function setHeureDuree($heureDuree) {
        $this->heureDuree = $heureDuree;
    }

}
