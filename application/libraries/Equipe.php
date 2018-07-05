<?php

/**
 * Classe de gestion des Equipes
 * Manager : Model_Equipes
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Equipe {

    protected $equipeId;
    protected $equipeEtablissementId;
    protected $equipeNom;

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

}
