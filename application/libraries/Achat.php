<?php

/**
 * Classe de gestion des Achats
 * Manager : Model_Achats
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Achat {

    protected $achatId;
    protected $achatChantierId;
    protected $achatDate;
    protected $achatDescription;
    protected $achatType;
    protected $achatQte;
    protected $achatQtePrevisionnel;
    protected $achatPrix;
    protected $achatPrixPrevisionnel;
    protected $achatTotal;
    protected $achatTotalPrevisionnel;

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

    function getAchatId() {
        return $this->achatId;
    }

    function getAchatChantierId() {
        return $this->achatChantierId;
    }

    function getAchatDate() {
        return $this->achatDate;
    }

    function getAchatDescription() {
        return $this->achatDescription;
    }

    function getAchatQte() {
        return $this->achatQte;
    }

    function getAchatQtePrevisionnel() {
        return $this->achatQtePrevisionnel;
    }

    function getAchatPrix() {
        return $this->achatPrix;
    }

    function getAchatPrixPrevisionnel() {
        return $this->achatPrixPrevisionnel;
    }

    function getAchatTotal() {
        return $this->achatTotal;
    }

    function getAchatTotalPrevisionnel() {
        return $this->achatTotalPrevisionnel;
    }

    function setAchatId($achatId) {
        $this->achatId = $achatId;
    }

    function setAchatChantierId($achatChantierId) {
        $this->achatChantierId = $achatChantierId;
    }

    function setAchatDate($achatDate) {
        $this->achatDate = $achatDate;
    }

    function setAchatDescription($achatDescription) {
        $this->achatDescription = $achatDescription;
    }

    function setAchatQte($achatQte) {
        $this->achatQte = $achatQte;
    }

    function setAchatQtePrevisionnel($achatQtePrevisionnel) {
        $this->achatQtePrevisionnel = $achatQtePrevisionnel;
    }

    function setAchatPrix($achatPrix) {
        $this->achatPrix = $achatPrix;
    }

    function setAchatPrixPrevisionnel($achatPrixPrevisionnel) {
        $this->achatPrixPrevisionnel = $achatPrixPrevisionnel;
    }

    function setAchatTotal($achatTotal) {
        $this->achatTotal = $achatTotal;
    }

    function setAchatTotalPrevisionnel($achatTotalPrevisionnel) {
        $this->achatTotalPrevisionnel = $achatTotalPrevisionnel;
    }

    function getAchatType() {
        return $this->achatType;
    }

    function setAchatType($achatType) {
        $this->achatType = $achatType;
    }

}
