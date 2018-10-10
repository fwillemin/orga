<?php

/**
 * Classe de gestion des Parametres
 * Manager : Model_Parametres
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Parametre {

    protected $parametreEtablissementId;
    protected $nbSemainesAvant;
    protected $nbSemainesApres;
    protected $tailleAffectations;
    protected $tranchePointage;
    protected $genererPaniers;

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

    function getParametreEtablissementId() {
        return $this->parametreEtablissementId;
    }

    function getNbSemainesAvant() {
        return $this->nbSemainesAvant;
    }

    function getNbSemainesApres() {
        return $this->nbSemainesApres;
    }

    function getTailleAffectations() {
        return $this->tailleAffectations;
    }

    function getTranchePointage() {
        return $this->tranchePointage;
    }

    function setParametreEtablissementId($parametreEtablissementId) {
        $this->parametreEtablissementId = $parametreEtablissementId;
    }

    function setNbSemainesAvant($nbSemainesAvant) {
        $this->nbSemainesAvant = $nbSemainesAvant;
    }

    function setNbSemainesApres($nbSemainesApres) {
        $this->nbSemainesApres = $nbSemainesApres;
    }

    function setTailleAffectations($tailleAffectations) {
        $this->tailleAffectations = $tailleAffectations;
    }

    function setTranchePointage($tranchePointage) {
        $this->tranchePointage = $tranchePointage;
    }

    function getGenererPaniers() {
        return $this->genererPaniers;
    }

    function setGenererPaniers($genererPaniers) {
        $this->genererPaniers = $genererPaniers;
    }

}
