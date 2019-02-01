<?php

/**
 * Classe de gestion des Equipes
 * Manager : Model_Equipes
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*
  ALTER TABLE `equipes` ADD `equipeCouleur` VARCHAR(7) NOT NULL DEFAULT '#000000' AFTER `equipeNom`;
  ALTER TABLE `equipes` ADD `equipeCouleurSecondaire` VARCHAR(7) NOT NULL DEFAULT '#FEFEFE' AFTER `equipeCouleur`;

 */
class Equipe {

    protected $equipeId;
    protected $equipeEtablissementId;
    protected $equipeNom;
    protected $equipeCouleur;
    protected $equipeCouleurSecondaire;

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

    function getEquipeId() {
        return $this->equipeId;
    }

    function getEquipeEtablissementId() {
        return $this->equipeEtablissementId;
    }

    function getEquipeNom() {
        return $this->equipeNom;
    }

    function setEquipeId($equipeId) {
        $this->equipeId = $equipeId;
    }

    function setEquipeEtablissementId($equipeEtablissementId) {
        $this->equipeEtablissementId = $equipeEtablissementId;
    }

    function setEquipeNom($equipeNom) {
        $this->equipeNom = $equipeNom;
    }

    function getEquipeCouleur() {
        return $this->equipeCouleur;
    }

    function getEquipeCouleurSecondaire() {
        return $this->equipeCouleurSecondaire;
    }

    function setEquipeCouleur($equipeCouleur) {
        $this->equipeCouleur = $equipeCouleur;
    }

    function setEquipeCouleurSecondaire($equipeCouleurSecondaire) {
        $this->equipeCouleurSecondaire = $equipeCouleurSecondaire;
    }

}
