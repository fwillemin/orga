<?php

/**
 * Classe de gestion des Indisponibilites
 * Manager : Model_Indisponibilites
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Indisponibilite {

    protected $indispoId;
    protected $indispoPersonnelId;
    protected $indispoPersonnel;
    protected $indispoDebutDate;
    protected $indispoDebutMoment;
    protected $indispoFinDate;
    protected $indispoFinMoment;
    protected $indispoNbDemi;
    protected $indispoCases;
    protected $indispoMotifId;
    protected $indispoMotif;
    protected $indispoAffichage;
    protected $indispoHTML;

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
        $this->hydrateMotif();
    }

    public function hydrateMotif() {
        $CI = & get_instance();
        $this->indispoMotif = $CI->managerMotifs->getMotifById($this->indispoMotifId);
    }

    public function toggleAffichage() {
        $this->indispoAffichage++;
        if ($this->indispoAffichage > 3):
            $this->indispoAffichage = 1;
        endif;
    }

    public function genereHTML($premierJourPlanning = null, $personnelsPlanning = array(), $numLigne = null, $hauteur, $largeur) {
        $CI = & get_instance();

        $positionLeft = floor(($this->indispoDebutDate - $premierJourPlanning) / 86400) * ($largeur * 2 + 2) + 2;
        if (date('I', $premierJourPlanning) == 0 && date('I', $this->indispoDebutDate) == 1):
            $positionLeft += ($largeur * 2 + 2);
        endif;

        //si on commence de l'aprem, on ajoute une 1/2 journée
        if ($this->indispoDebutMoment == 2) {
            $positionLeft += $largeur;
        }

        $classes = 'indispo';
        $taille = $this->indispoCases * ($largeur + 1) - 3;

        $txt = '<span class="planningIndispoText">'
                . substr($this->indispoMotif->getMotifNom(), 0, floor($taille / 10))
                . '</span>';

        if (!$numLigne):
            $ligne = 1;
            while ($personnelsPlanning[$ligne - 1]->getPersonnelId() != $this->indispoPersonnelId):
                $ligne++;
            endwhile;
        else:
            $ligne = $numLigne;
        endif;
        $positionTop = $hauteur * ($ligne - 1) + 41;

        //mode d'affichage de la div (pleine case, 1/2 haut ou 1/2 bas)
        if ($this->indispoAffichage == 1):
            $hauteurDiv = $hauteur - 3;
        else:
            $hauteurDiv = floor($hauteur / 2) - 2;
            if ($this->indispoAffichage == 2):
                $positionTop += floor($hauteur / 2) - 1;
            endif;
        endif;
        $this->indispoHTML = '<div style="'
                . 'top:' . $positionTop . 'px;'
                . ' left:' . $positionLeft . 'px;'
                . ' width:' . $taille . 'px;'
                . ' height:' . $hauteurDiv . 'px;'
                . ' z-index:1;"'
                . ' data-indispoid="' . $this->indispoId . '"'
                . ' data-ligne="' . $ligne . '"'
                . ' class="' . $classes . '" >'
                . $txt
                . '</div>';
    }

    public function hydratePersonnel() {
        $CI = & get_instance();
        $this->indispoPersonnel = $CI->managerPersonnels->getPersonnelById($this->indispoPersonnelId);
    }

    function getIndispoId() {
        return $this->indispoId;
    }

    function getIndispoPersonnelId() {
        return $this->indispoPersonnelId;
    }

    function getIndispoPersonnel() {
        if (empty($this->indispoPersonnel)):
            $this->hydratePersonnel();
        endif;
        return $this->indispoPersonnel;
    }

    function getIndispoDebutDate() {
        return $this->indispoDebutDate;
    }

    function getIndispoDebutMoment() {
        return $this->indispoDebutMoment;
    }

    function getIndispoFinDate() {
        return $this->indispoFinDate;
    }

    function getIndispoFinMoment() {
        return $this->indispoFinMoment;
    }

    function getIndispoNbDemi() {
        return $this->indispoNbDemi;
    }

    function getIndispoCases() {
        return $this->indispoCases;
    }

    function getIndispoMotifId() {
        return $this->indispoMotifId;
    }

    function getIndispoMotif() {
        return $this->indispoMotif;
    }

    function getIndispoAffichage() {
        return $this->indispoAffichage;
    }

    function getIndispoHTML() {
        return $this->indispoHTML;
    }

    function setIndispoId($indispoId) {
        $this->indispoId = $indispoId;
    }

    function setIndispoPersonnelId($indispoPersonnelId) {
        $this->indispoPersonnelId = $indispoPersonnelId;
    }

    function setIndispoPersonnel($indispoPersonnel) {
        $this->indispoPersonnel = $indispoPersonnel;
    }

    function setIndispoDebutDate($indispoDebutDate) {
        $this->indispoDebutDate = $indispoDebutDate;
    }

    function setIndispoDebutMoment($indispoDebutMoment) {
        $this->indispoDebutMoment = $indispoDebutMoment;
    }

    function setIndispoFinDate($indispoFinDate) {
        $this->indispoFinDate = $indispoFinDate;
    }

    function setIndispoFinMoment($indispoFinMoment) {
        $this->indispoFinMoment = $indispoFinMoment;
    }

    function setIndispoNbDemi($indispoNbDemi) {
        $this->indispoNbDemi = $indispoNbDemi;
    }

    function setIndispoCases($indispoCases) {
        $this->indispoCases = $indispoCases;
    }

    function setIndispoMotifId($indispoMotifId) {
        $this->indispoMotifId = $indispoMotifId;
    }

    function setIndispoMotif($indispoMotif) {
        $this->indispoMotif = $indispoMotif;
    }

    function setIndispoAffichage($indispoAffichage) {
        $this->indispoAffichage = $indispoAffichage;
    }

    function setIndispoHTML($indispoHTML) {
        $this->indispoHTML = $indispoHTML;
    }

}
