<?php

/**
 * Classe de gestion des Pointages
 * Manager : Model_Pointages
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Pointage {

    protected $pointageId;
    protected $pointagePersonnelId;
    protected $pointagePersonnel;
    protected $pointageMois;
    protected $pointageAnnee;
    protected $pointageHTML;

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

    public function hydratePersonnel() {
        $CI = & get_instance();
        $this->pointagePersonnel = $CI->managerPersonnels->getPersonnelById($this->pointagePersonnelId);
    }

    function getPointageId() {
        return $this->pointageId;
    }

    function getPointagePersonnelId() {
        return $this->pointagePersonnelId;
    }

    function getPointagePersonnel() {
        return $this->pointagePersonnel;
    }

    function getPointageMois() {
        return $this->pointageMois;
    }

    function getPointageAnnee() {
        return $this->pointageAnnee;
    }

    function getPointageHTML() {
        return $this->pointageHTML;
    }

    function setPointageId($pointageId) {
        $this->pointageId = $pointageId;
    }

    function setPointagePersonnelId($pointagePersonnelId) {
        $this->pointagePersonnelId = $pointagePersonnelId;
    }

    function setPointagePersonnel($pointagePersonnel) {
        $this->pointagePersonnel = $pointagePersonnel;
    }

    function setPointageMois($pointageMois) {
        $this->pointageMois = $pointageMois;
    }

    function setPointageAnnee($pointageAnnee) {
        $this->pointageAnnee = $pointageAnnee;
    }

    function setPointageHTML($pointageHTML) {
        $this->pointageHTML = $pointageHTML;
    }

}
