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
    protected $affectationDebutDate;
    protected $affectationDebutMoment; /* 1 Matin, 2 Aprem */
    protected $affectationDebutMomentText;
    protected $affectationFinDate;
    protected $affectationFinMoment; /* 1 Matin, 2 Aprem */
    protected $affectationFinMomentText;
    protected $affectationCases; /* Nombre de case du planning incluant les week-end */
    /* --  */
    protected $affectationChantierEtat; /* Etat du chantier pere */
    protected $affectationCommentaire;
    protected $affectationType;
    protected $affectationTypeText;
    protected $affectationAffichage; /* 1 FULL, 2 BAS, 3 HAUT */
    /* --- */
    protected $affectationAffaire;
    protected $affectationClient;
    protected $affectationHTML;
    protected $affectationHeures;
    protected $affectationLivraisons;

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
        switch ($this->affectationType):
            case 1:
                $this->affectationTypeText = 'Chantier';
                break;
            case 2:
                $this->affectationTypeText = 'Atelier';
                break;
            case 3:
                $this->affectationTypeText = 'Service après-vente';
                break;
        endswitch;
        switch ($this->affectationDebutMoment):
            case 1:
                $this->affectationDebutMomentText = 'matin';
                break;
            case 2:
                $this->affectationDebutMomentText = 'après-midi';
                break;
        endswitch;
        switch ($this->affectationFinMoment):
            case 1:
                $this->affectationFinMomentText = 'matin';
                break;
            case 2:
                $this->affectationFinMomentText = 'après-midi';
                break;
        endswitch;
    }

    public function hydrateChantier() {
        $CI = & get_instance();
        $this->affectationChantier = $CI->managerChantiers->getChantierById($this->affectationChantierId);
    }

    public function hydratePersonnel() {
        $CI = & get_instance();
        $this->affectationPersonnel = $CI->managerPersonnels->getPersonnelById($this->affectationPersonnelId);
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

    public function hydrateHeures() {
        $CI = & get_instance();
        $this->affectationHeures = $CI->managerHeures->getHeuresByAffectationId($this->affectationId);
    }

    public function hydrateLivraisons() {
        $CI = & get_instance();
        $this->affectationLivraisons = $CI->managerLivraisons->getLivraisonsByAffectationId($this->affectationId);
    }

    /**
     *
     * @param type $premierJourPlanning
     * @param type $personnelsPlanning Liste du personnel du planning pour calculer le numéro de ligne si on ne l'a pas dans la varible "ligne"
     * @param int $ligne Numéro de ligne dans le planning
     * @param type $hauteur
     * @param type $largeur
     */
    public function getHTML($premierJourPlanning = null, $personnelsPlanning = array(), $numLigne = null, $hauteur, $largeur) {
        $CI = & get_instance();
        if (empty($this->affectationAffaire) || empty($this->affectationChantier)):
            $this->hydrateOrigines();
        endif;
        $this->hydrateHeures();

        /* Un décallage de position apparait dans le cas ou la premier jour de planning est en heure d'hiver et que l'affectation est en haure d'été */
        if (date('I', $premierJourPlanning) == 0 && date('I', $this->affectationDebutDate) == 1):
            $positionLeft = ceil(($this->affectationDebutDate - $premierJourPlanning) / 86400) * ($largeur * 2 + 2) + 2;
        else:
            $positionLeft = floor(($this->affectationDebutDate - $premierJourPlanning) / 86400) * ($largeur * 2 + 2) + 2;
        endif;

        //si on commence de l'aprem, on ajoute une 1/2 journée
        if ($this->affectationDebutMoment == 2) {
            $positionLeft += $largeur;
        }

        $classes = 'affectation';
        $attributs = 'js-affectationid="' . $this->affectationId . '" js-chantierid="' . $this->affectationChantierId; /* ac signifie affectation du chantier + l'id du chantier associé => utilisé pour mettre toutes les affectations les elements d'un chantier en surbrillance lors du click dans le slide gauche */
        $taille = $this->affectationCases * ($largeur + 1) - 3;
        $background = $this->affectationChantier->getChantierCouleur();
        if ($this->getAffectationChantier()->getChantierEtat() == 1):
            $classes .= ' resizable';
            if (empty($this->affectationHeures)):
                $classes .= ' draggable';
            else:
                $classes .= ' draggableHorizontal';
            endif;
            $border = 'border : 1px solid ' . $this->getAffectationChantier()->getChantierCouleurSecondaire() . ';';
            $background = $CI->own->hex2rgba($this->getAffectationChantier()->getChantierCouleur(), 0.85);
        else:
            $border = 'border : 1px dashed ' . $this->getAffectationChantier()->getChantierCouleurSecondaire() . '; border-left: 3px;';
            $background = $CI->own->hex2rgba($this->getAffectationChantier()->getChantierCouleur(), 0.2);
        endif;
        if (!empty($this->affectationHeures)):
            $border .= ' border-left: 3px solid ' . $this->getAffectationChantier()->getChantierCouleurSecondaire() . ';';
        endif;

        $txt = '<span class="planningDivText" data-toggle="tooltip" title="' . $this->getAffectationClient()->getClientNom() . ' [' . $this->getAffectationChantier()->getChantierCategorie() . ' - ' . $this->getAffectationChantier()->getChantierObjet() . ']" style="color:' . $this->getAffectationChantier()->getChantierCouleurSecondaire() . ';">'
                . substr($this->getAffectationClient()->getClientNom(), 0, floor($taille / 10))
                . '</span>';

        if (!$numLigne):
            $ligne = 1;
            while ($personnelsPlanning[$ligne - 1]->getPersonnelId() != $this->affectationPersonnelId):
                $ligne++;
            endwhile;
        else:
            $ligne = $numLigne;
        endif;
        $positionTop = $hauteur * ($ligne - 1) + 41;

        //mode d'affichage de la div (pleine case, 1/2 haut ou 1/2 bas)
        if ($this->affectationAffichage == 1):
            $hauteurDiv = $hauteur - 3;
        else:
            $hauteurDiv = floor($hauteur / 2) - 2;
            if ($this->affectationAffichage == 2):
                $positionTop += floor($hauteur / 2) - 1;
            endif;
        endif;
        $this->affectationHTML = '<div style="'
                . 'top:' . $positionTop . 'px;'
                . ' left:' . $positionLeft . 'px;'
                . ' background-color:' . $background . ';'
                . $border
                . ' width:' . $taille . 'px;'
                . ' height:' . $hauteurDiv . 'px;"'
                . ' data-affectationid="' . $this->affectationId . '"'
                . ' data-ligne="' . $ligne . '"'
                . ' class="' . $classes . '" >'
                . $txt
                . '</div>';
    }

    public function toggleAffichage() {
        $this->affectationAffichage++;
        if ($this->affectationAffichage > 3):
            $this->affectationAffichage = 1;
        endif;
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

    function getAffectationDebutDate() {
        return $this->affectationDebutDate;
    }

    function getAffectationDebutMoment() {
        return $this->affectationDebutMoment;
    }

    function getAffectationFinDate() {
        return $this->affectationFinDate;
    }

    function getAffectationFinMoment() {
        return $this->affectationFinMoment;
    }

    function getAffectationCases() {
        return $this->affectationCases;
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

    function getAffectationHeures() {
        return $this->affectationHeures;
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

    function setAffectationDebutDate($affectationDebutDate) {
        $this->affectationDebutDate = $affectationDebutDate;
    }

    function setAffectationDebutMoment($affectationDebutMoment) {
        $this->affectationDebutMoment = $affectationDebutMoment;
    }

    function setAffectationFinDate($affectationFinDate) {
        $this->affectationFinDate = $affectationFinDate;
    }

    function setAffectationFinMoment($affectationFinMoment) {
        $this->affectationFinMoment = $affectationFinMoment;
    }

    function setAffectationCases($affectationCases) {
        $this->affectationCases = $affectationCases;
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

    function setAffectationHeures($affectationHeures) {
        $this->affectationHeures = $affectationHeures;
    }

    function getAffectationTypeText() {
        return $this->affectationTypeText;
    }

    function setAffectationTypeText($affectationTypeText) {
        $this->affectationTypeText = $affectationTypeText;
    }

    function getAffectationDebutMomentText() {
        return $this->affectationDebutMomentText;
    }

    function getAffectationFinMomentText() {
        return $this->affectationFinMomentText;
    }

    function setAffectationDebutMomentText($affectationDebutMomentText) {
        $this->affectationDebutMomentText = $affectationDebutMomentText;
    }

    function setAffectationFinMomentText($affectationFinMomentText) {
        $this->affectationFinMomentText = $affectationFinMomentText;
    }

    function getAffectationLivraisons() {
        return $this->affectationLivraisons;
    }

    function setAffectationLivraisons($affectationLivraisons) {
        $this->affectationLivraisons = $affectationLivraisons;
    }

}
