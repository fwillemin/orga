<?php

/**
 * Classe de gestion des TauxHoraires
 * Manager : Model_TauxHoraires
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class TauxHoraire {

    protected $tauxHoraireId;
    protected $tauxHorairePersonnelId;
    protected $tauxHoraire;
    protected $tauxHoraireDate;

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

    function getTauxHoraireId() {
        return $this->tauxHoraireId;
    }

    function getTauxHorairePersonnelId() {
        return $this->tauxHorairePersonnelId;
    }

    function getTauxHoraire() {
        return $this->tauxHoraire;
    }

    function getTauxHoraireDate() {
        return $this->tauxHoraireDate;
    }

    function setTauxHoraireId($tauxHoraireId) {
        $this->tauxHoraireId = $tauxHoraireId;
    }

    function setTauxHorairePersonnelId($tauxHorairePersonnelId) {
        $this->tauxHorairePersonnelId = $tauxHorairePersonnelId;
    }

    function setTauxHoraire($tauxHoraire) {
        $this->tauxHoraire = $tauxHoraire;
    }

    function setTauxHoraireDate($tauxHoraireDate) {
        $this->tauxHoraireDate = $tauxHoraireDate;
    }

}
