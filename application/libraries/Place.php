<?php

/**
 * Classe de gestion des Places
 * Manager : Model_Places
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Place {

    protected $placeId;
    protected $placeEtablissementId;
    protected $placeClientId;
    protected $placeAdresse;
    protected $placeVille; /* Donnees utilisée dans les feuilles de pointage */
    protected $placeLat;
    protected $placeLon;
    protected $placeDistance;
    protected $placeDuree;
    protected $placeVolOiseau;
    protected $placeGoogleId;
    protected $placeZone;
    protected $placeUtilisations; /* Nombre d'utilisation de cette place dans les affectations */

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

    public function hydrateUtilisations() {
        $CI = & get_instance();
        $this->placeUtilisations = $CI->managerAffectations->count(array('affectationPlaceId' => $this->placeId));
    }

    function getPlaceUtilisations() {
        return $this->placeUtilisations;
    }

    function setPlaceUtilisations($placeUtilisations) {
        $this->placeUtilisations = $placeUtilisations;
    }

    function getPlaceId() {
        return $this->placeId;
    }

    function getPlaceEtablissementId() {
        return $this->placeEtablissementId;
    }

    function getPlaceClientId() {
        return $this->placeClientId;
    }

    function getPlaceAdresse() {
        return $this->placeAdresse;
    }

    function getPlaceLat() {
        return $this->placeLat;
    }

    function getPlaceLon() {
        return $this->placeLon;
    }

    function getPlaceDistance() {
        return $this->placeDistance;
    }

    function getPlaceVolOiseau() {
        return $this->placeVolOiseau;
    }

    function getPlaceGoogleId() {
        return $this->placeGoogleId;
    }

    function getPlaceZone() {
        return $this->placeZone;
    }

    function setPlaceId($placeId) {
        $this->placeId = $placeId;
    }

    function setPlaceEtablissementId($placeEtablissementId) {
        $this->placeEtablissementId = $placeEtablissementId;
    }

    function setPlaceClientId($placeClientId) {
        $this->placeClientId = $placeClientId;
    }

    function setPlaceAdresse($placeAdresse) {
        $this->placeAdresse = $placeAdresse;
    }

    function setPlaceLat($placeLat) {
        $this->placeLat = $placeLat;
    }

    function setPlaceLon($placeLon) {
        $this->placeLon = $placeLon;
    }

    function setPlaceDistance($placeDistance) {
        $this->placeDistance = $placeDistance;
    }

    function setPlaceVolOiseau($placeVolOiseau) {
        $this->placeVolOiseau = $placeVolOiseau;
    }

    function setPlaceGoogleId($placeGoogleId) {
        $this->placeGoogleId = $placeGoogleId;
    }

    function setPlaceZone($placeZone) {
        $this->placeZone = $placeZone;
    }

    function getPlaceDuree() {
        return $this->placeDuree;
    }

    function setPlaceDuree($placeDuree) {
        $this->placeDuree = $placeDuree;
    }

    function getPlaceVille() {
        return $this->placeVille;
    }

    function setPlaceVille($placeVille) {
        $this->placeVille = $placeVille;
    }

}
