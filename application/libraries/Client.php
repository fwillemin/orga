<?php

/**
 * Classe de gestion des Clients
 * Manager : Model_Clients
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Client {

    protected $clientId;
    protected $clientEtablissementId;
    protected $clientNom;
    protected $clientAdresse;
    protected $clientCp;
    protected $clientVille;
    protected $clientPays;
    protected $clientFixe;
    protected $clientPortable;
    protected $clientEmail;
    protected $clientPlaces;
    protected $clientLastAffaire;
    protected $clientAffaires;

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

    public function hydratePlaces() {
        $CI = & get_instance();
        $this->clientPlaces = $CI->managerPlaces->getPlaces(array('placeClientId' => $this->clientId));
    }

    public function hydrateAffaires() {
        $CI = & get_instance();
        $this->clientAffaires = $CI->managerAffaires->getAffairesByClientId($this->clientId);
    }

    public function hydrateLastAffaire() {
        $CI = & get_instance();
        $this->clientLastAffaire = $CI->managerAffaires->getLastAffaireByClientId($this->clientId);
    }

    function getClientLastAffaire() {
        return $this->clientLastAffaire;
    }

    function setClientLastAffaire($clientLastAffaire) {
        $this->clientLastAffaire = $clientLastAffaire;
    }

    function getClientAffaires() {
        return $this->clientAffaires;
    }

    function setClientAffaires($clientAffaires) {
        $this->clientAffaires = $clientAffaires;
    }

    function getClientId() {
        return $this->clientId;
    }

    function getClientEtablissementId() {
        return $this->clientEtablissementId;
    }

    function getClientNom() {
        return $this->clientNom;
    }

    function getClientAdresse() {
        return $this->clientAdresse;
    }

    function getClientCp() {
        return $this->clientCp;
    }

    function getClientVille() {
        return $this->clientVille;
    }

    function getClientPays() {
        return $this->clientPays;
    }

    function getClientFixe() {
        return $this->clientFixe;
    }

    function getClientPortable() {
        return $this->clientPortable;
    }

    function getClientEmail() {
        return $this->clientEmail;
    }

    function getClientPlaces() {
        return $this->clientPlaces;
    }

    function setClientId($clientId) {
        $this->clientId = $clientId;
    }

    function setClientEtablissementId($clientEtablissementId) {
        $this->clientEtablissementId = $clientEtablissementId;
    }

    function setClientNom($clientNom) {
        $this->clientNom = $clientNom;
    }

    function setClientAdresse($clientAdresse) {
        $this->clientAdresse = $clientAdresse;
    }

    function setClientCp($clientCp) {
        $this->clientCp = $clientCp;
    }

    function setClientVille($clientVille) {
        $this->clientVille = $clientVille;
    }

    function setClientPays($clientPays) {
        $this->clientPays = $clientPays;
    }

    function setClientFixe($clientFixe) {
        $this->clientFixe = $clientFixe;
    }

    function setClientPortable($clientPortable) {
        $this->clientPortable = $clientPortable;
    }

    function setClientEmail($clientEmail) {
        $this->clientEmail = $clientEmail;
    }

    function setClientPlaces($clientPlaces) {
        $this->clientPlaces = $clientPlaces;
    }

}
