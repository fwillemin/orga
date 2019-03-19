<?php

/**
 * Classe de gestion des Heures
 * Manager : Model_HeuresSupp
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class HeureSupp {

    protected $hsSemaine;
    protected $hsAnnee;
    protected $hsPersonnelId;
    protected $hsNbHeuresSupp;

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

    function getHsSemaine() {
        return $this->hsSemaine;
    }

    function getHsAnnee() {
        return $this->hsAnnee;
    }

    function getHsPersonnelId() {
        return $this->hsPersonnelId;
    }

    function getHsNbHeuresSupp() {
        return $this->hsNbHeuresSupp;
    }

    function setHsSemaine($hsSemaine) {
        $this->hsSemaine = $hsSemaine;
    }

    function setHsAnnee($hsAnnee) {
        $this->hsAnnee = $hsAnnee;
    }

    function setHsPersonnelId($hsPersonnelId) {
        $this->hsPersonnelId = $hsPersonnelId;
    }

    function setHsNbHeuresSupp($hsNbHeuresSupp) {
        $this->hsNbHeuresSupp = $hsNbHeuresSupp;
    }

}
