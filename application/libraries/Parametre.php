<?php

/**
 * Classe de gestion des Parametres
 * Manager : Model_Parametres
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*
  ALTER TABLE `parametres` ADD `distanceZI` TINYINT NOT NULL DEFAULT '2' COMMENT '1=Vol oiseau, 2=reelle' AFTER `limiteHeuresSupp`;

 */
class Parametre {

    protected $parametreEtablissementId;
    protected $nbSemainesAvant;
    protected $nbSemainesApres;
    protected $tailleAffectations;
    protected $tranchePointage;
    protected $genererPaniers;
    protected $limiteHeuresSupp;
    protected $distanceZI; /* 1=vol oiseau, 2=réelle */
    protected $messageEtablissement;

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

    function getMessageEtablissement() {
        return $this->messageEtablissement;
    }

    function setMessageEtablissement($messageEtablissement) {
        $this->messageEtablissement = $messageEtablissement;
    }

    function getLimiteHeuresSupp() {
        return $this->limiteHeuresSupp;
    }

    function setLimiteHeuresSupp($limiteHeuresSupp) {
        $this->limiteHeuresSupp = $limiteHeuresSupp;
    }

    function getDistanceZI() {
        return $this->distanceZI;
    }

    function setDistanceZI($distanceZI) {
        $this->distanceZI = $distanceZI;
    }

}
