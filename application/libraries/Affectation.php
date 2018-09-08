<?php

/**
 * Classe de gestion des Affectations
 * Manager : Model_Affectations
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Affectation {

    protected $affectationId;
    protected $affectationOriginId;
    protected $affectationChantierId;
    protected $affectationChantier;
    protected $affectationPersonnelId;
    protected $affectationPersonnel;
    protected $affectationPlaceId;
    protected $affectationPlace;
    /* -- */
    protected $affectationNbDemi;
    protected $affectationDebut;
    protected $affectationDebutMoment; /* 1 Matin, 2 Aprem */
    protected $affectationFin;
    protected $affectationFinMoment; /* 1 Matin, 2 Aprem */
    protected $affectationCases; /* Nombre de case du planning incluant les week-end */
    /* --  */
    protected $affectationEtat;
    protected $affectationChantierEtat; /* Etat du chantier pere */
    protected $affectationCommentaire;
    protected $affectationType;
    protected $affectationAffichage; /* 1 FULL, 2 BAS, 3 HAUT */
    /* --- */
    protected $affectationAffaire;
    protected $affectationClient;
    protected $affectationHTML;

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

    public function hydrateChantier() {
        $CI = & get_instance();
        $this->affectationChantier = $CI->managerChantiers->getChantierById($this->affectationChantierId);
    }

    public function hydratePersonnel() {
        $CI = & get_instance();
        $this->affectationPersonnel = $CI->managerPersonnels->getPersonnelById($this->affectationPersonelId);
    }

    public function hydratePlace() {
        $CI = & get_instance();
        $this->affectationPlace = $CI->managerPlaces->getPlaceById($this->affectationPlaceId);
    }

    public function hydrateOrigines() {
        $CI = & get_instance();
        if (!$this->affectationChantier):
            $this->hydrateChantier();
        endif;
        $this->affectationAffaire = $CI->managerAffaires->getAffaireById($this->affectationChantier->getChantierAffaireId());
        $this->affectationClient = $CI->managerClients->getClientById($this->affectationAffaire->getAffaireClientId());
    }

    /**
     * Génère une div representant l'affectation dans le planning en mode readOnly
     *
     * @param Affectation $affectation Affectation à afficher
     * @param string $type Indispo ou active
     * @param integer $premier_jour timestamp du premier jour du planning
     * @param int $num_ligne Numéro de la ligne de placement de cette affectation correpsondant au personnel affecté
     * @param boolean $drag La div peut-elle être déplacée
     * @param boolean $resize La div peut-elle être redimensionnée
     * @param integer $hauteur Hauteur de la div
     * @param integer $largeur Largeur de la div
     *
     * @return string Retourne le code HTML de la div à insérer dans le planning
     */
    public function getHTML2($premierJourPlanning = null, $personnelsPlanning = array(), $hauteur, $largeur, $drag, $resize) {

    }

    public function getHTML($premierJourPlanning = null, $personnelsPlanning = array(), $hauteur, $largeur) {
        $CI = & get_instance();


        $positionLeft = ceil(($this->affectationDebut - $premierJourPlanning) / 86400) * ($largeur * 2 + 2) + 2;
        //si on commence de l'aprem, on ajoute une 1/2 journée
        if ($this->affectationDebutMoment == 2) {
            $positionLeft += $largeur;
        }

        $classes = 'affectation';
        $attributs = 'js-affectationid="' . $this->affectationId . '" js-chantierid="' . $this->affectationChantierId; /* ac signifie affectation du chantier + l'id du chantier associé => utilisé pour mettre toutes les affectations les elements d'un chantier en surbrillance lors du click dans le slide gauche */
        $taille = $this->affectationCases * ($largeur + 1) - 3;
        $zindex = 2;
        $background = $this->getAffectationChantier()->getChantierCouleur();
        if ($this->getAffectationChantier()->getChantierEtat() == 1):
            $border = '1px solid ' . $this->getAffectationChantier()->getChantierCouleurSecondaire();
            $background = $CI->own->hex2rgba($this->getAffectationChantier()->getChantierCouleur());
        else:
            $border = '1px dashed ' . $this->getAffectationChantier()->getChantierCouleurSecondaire();
            $background = $CI->own->hex2rgba($this->getAffectationChantier()->getChantierCouleur(), 0.2);
        endif;
        $couleur = $this->getAffectationChantier()->getChantierCouleur();


        if ($CI->ion_auth->in_group(array(50))):
            $link = site_url('affaires/ficheAffaire/' . $this->getAffectationAffaire()->getAffaireId());
        else:
            $link = '#';
        endif;

        $txt = '<a href="' . $link . '" data-toggle="tooltip" title="' . $this->getAffectationClient()->getClientNom() . ' [' . $this->getAffectationChantier()->getChantierCategorie() . ' - ' . $this->getAffectationChantier()->getChantierObjet() . ']" style="color:' . $this->getAffectationChantier()->getChantierCouleurSecondaire() . ';">'
                . '<span class="planningDivText ' . ($this->getAffectationChantier()->getChantierEtat() == 2 ? 'surligner"' : '') . '">'
                . substr($this->getAffectationClient()->getClientNom(), 0, floor($taille / 10)) .
                '</span></a>';


        //recentrage des div d'une seule 1/2j
        if ($taille < $largeur) :
            $positionLeft -= 1;
        endif;

        //calcul de la ligne d'apposition
        $ligne = 0;
        while ($personnelsPlanning[$ligne]->getPersonnelId() != $this->affectationPersonnelId):
            $ligne++;
        endwhile;
        $positionTop = $hauteur * $ligne + 42;

        //mode d'affichage de la div (pleine case, 1/2 haut ou 1/2 bas)
        if ($this->affectationAffichage == 1):
            $hauteurDiv = $hauteur - 5;
        else:
            $hauteurDiv = floor($hauteur / 2) - 2;
            if ($this->affectationAffichage == 2):
                $positionTop += floor($hauteur / 2) - 3;
            endif;
        endif;
        $this->affectationHTML = '<div style="'
                . 'position:absolute;'
                . ' top:' . $positionTop . 'px;'
                . ' left:' . $positionLeft . 'px;'
                . ' background-color:' . $background . ';'
                . ' border:' . $border . ';'
                . ' width:' . $taille . 'px;'
                . ' height:' . $hauteurDiv . 'px;'
                . ' z-index :' . $zindex . ';"'
                . ' data-affectationid="' . $this->affectationId . '"'
                . ' data-placement="' . $this->affectationAffichage . '"'
                . ' class="' . $classes . '" >'
                . $txt
                . '</div>';
    }

    function getAffectationId() {
        return $this->affectationId;
    }

    function getAffectationOriginId() {
        return $this->affectationOriginId;
    }

    function getAffectationChantierId() {
        return $this->affectationChantierId;
    }

    function getAffectationChantier() {
        return $this->affectationChantier;
    }

    function getAffectationPersonnelId() {
        return $this->affectationPersonnelId;
    }

    function getAffectationPersonnel() {
        return $this->affectationPersonnel;
    }

    function getAffectationPlaceId() {
        return $this->affectationPlaceId;
    }

    function getAffectationPlace() {
        return $this->affectationPlace;
    }

    function getAffectationNbDemi() {
        return $this->affectationNbDemi;
    }

    function getAffectationDebut() {
        return $this->affectationDebut;
    }

    function getAffectationDebutMoment() {
        return $this->affectationDebutMoment;
    }

    function getAffectationFin() {
        return $this->affectationFin;
    }

    function getAffectationFinMoment() {
        return $this->affectationFinMoment;
    }

    function getAffectationCases() {
        return $this->affectationCases;
    }

    function getAffectationEtat() {
        return $this->affectationEtat;
    }

    function getAffectationChantierEtat() {
        return $this->affectationChantierEtat;
    }

    function getAffectationCommentaire() {
        return $this->affectationCommentaire;
    }

    function getAffectationType() {
        return $this->affectationType;
    }

    function getAffectationAffichage() {
        return $this->affectationAffichage;
    }

    function getAffectationAffaire() {
        return $this->affectationAffaire;
    }

    function getAffectationClient() {
        return $this->affectationClient;
    }

    function getAffectationHTML() {
        return $this->affectationHTML;
    }

    function setAffectationId($affectationId) {
        $this->affectationId = $affectationId;
    }

    function setAffectationOriginId($affectationOriginId) {
        $this->affectationOriginId = $affectationOriginId;
    }

    function setAffectationChantierId($affectationChantierId) {
        $this->affectationChantierId = $affectationChantierId;
    }

    function setAffectationChantier($affectationChantier) {
        $this->affectationChantier = $affectationChantier;
    }

    function setAffectationPersonnelId($affectationPersonnelId) {
        $this->affectationPersonnelId = $affectationPersonnelId;
    }

    function setAffectationPersonnel($affectationPersonnel) {
        $this->affectationPersonnel = $affectationPersonnel;
    }

    function setAffectationPlaceId($affectationPlaceId) {
        $this->affectationPlaceId = $affectationPlaceId;
    }

    function setAffectationPlace($affectationPlace) {
        $this->affectationPlace = $affectationPlace;
    }

    function setAffectationNbDemi($affectationNbDemi) {
        $this->affectationNbDemi = $affectationNbDemi;
    }

    function setAffectationDebut($affectationDebut) {
        $this->affectationDebut = $affectationDebut;
    }

    function setAffectationDebutMoment($affectationDebutMoment) {
        $this->affectationDebutMoment = $affectationDebutMoment;
    }

    function setAffectationFin($affectationFin) {
        $this->affectationFin = $affectationFin;
    }

    function setAffectationFinMoment($affectationFinMoment) {
        $this->affectationFinMoment = $affectationFinMoment;
    }

    function setAffectationCases($affectationCases) {
        $this->affectationCases = $affectationCases;
    }

    function setAffectationEtat($affectationEtat) {
        $this->affectationEtat = $affectationEtat;
    }

    function setAffectationChantierEtat($affectationChantierEtat) {
        $this->affectationChantierEtat = $affectationChantierEtat;
    }

    function setAffectationCommentaire($affectationCommentaire) {
        $this->affectationCommentaire = $affectationCommentaire;
    }

    function setAffectationType($affectationType) {
        $this->affectationType = $affectationType;
    }

    function setAffectationAffichage($affectationAffichage) {
        $this->affectationAffichage = $affectationAffichage;
    }

    function setAffectationAffaire($affectationAffaire) {
        $this->affectationAffaire = $affectationAffaire;
    }

    function setAffectationClient($affectationClient) {
        $this->affectationClient = $affectationClient;
    }

    function setAffectationHTML($affectationHTML) {
        $this->affectationHTML = $affectationHTML;
    }

}
