<?php

/**
 * Classe de gestion des Affaires
 * Manager : Model_raisons
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class RaisonSociale {

    protected $rsId;
    protected $rsOriginId;
    protected $rsNom;
    protected $rsInscription;
    protected $rsMoisFiscal;
    protected $rsCategorieNC;

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

    function getRsId() {
        return $this->rsId;
    }

    function getRsNom() {
        return $this->rsNom;
    }

    function getRsInscription() {
        return $this->rsInscription;
    }

    function getRsMoisFiscal() {
        return $this->rsMoisFiscal;
    }

    function getRsCategorieNC() {
        return $this->rsCategorieNC;
    }

    function setRsId($rsId) {
        $this->rsId = $rsId;
    }

    function setRsNom($rsNom) {
        $this->rsNom = $rsNom;
    }

    function setRsInscription($rsInscription) {
        $this->rsInscription = $rsInscription;
    }

    function setRsMoisFiscal($rsMoisFiscal) {
        $this->rsMoisFiscal = $rsMoisFiscal;
    }

    function setRsCategorieNC($rsCategorieNC) {
        $this->rsCategorieNC = $rsCategorieNC;
    }

    function getRsOriginId() {
        return $this->rsOriginId;
    }

    function setRsOriginId($rsOriginId) {
        $this->rsOriginId = $rsOriginId;
    }

}
