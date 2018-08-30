<?php

/**
 * Classe de gestion des Affaires
 * Manager : Model_Affaires
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Chantier {

    protected $chantierId;
    protected $chantierOriginId;
    protected $chantierPlaceId;
    protected $chantierPlace;
    protected $chantierAffaireId;
    protected $chantierAffaire;
    protected $chantierClient;
    protected $chantierCategorieId;
    protected $chantierCategorie;
    protected $chantierObjet;
    protected $chantierPrix;
    protected $chantierCouleur;
    protected $chantierCouleurSecondaire;
    protected $chantierEtat;
    protected $chantierDateCloture;
    protected $chantierHeuresPrevues;
    protected $chantierBudgetAchats;
    protected $chantierFraisGeneraux;
    protected $chantierTauxHoraireMoyen;
    protected $chantierRemarque;
    protected $chantierAchats;
    /* TRIGGERED */
    protected $chantierBudgetPrevu; /* Somme des achats prévus */
    protected $chantierBudgetConsomme; /* Somme des achats réalisés */

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
        $CI = & get_instance();
        $categorie = $CI->managerCategories->getCategorieById($this->chantierCategorieId);
        $this->chantierCategorie = $categorie ? $categorie->getCategorieNom() : '<span class="badge badge-warning">Non classé</span>';
        switch ($this->chantierEtat):
            case 1:
                $this->chantierEtatHtml = '<span class="badge badge-success">En cours</span>';
                break;
            case 2:
                $this->chantierEtatHtml = '<span class="badge badge-secondary">Cloturé</span>';
                break;
        endswitch;
    }

    public function hydratePlace() {
        $CI = & get_instance();
        $this->chantierPlace = $CI->managerPlaces->getPlaceById($this->chantierPlaceId);
    }

    public function hydrateAffaire() {
        $CI = & get_instance();
        $this->chantierAffaire = $CI->managerAffaires->getAffaireById($this->chantierAffaireId);
    }

    public function hydrateClient() {
        $CI = & get_instance();

        if (!$this->chantierAffaire):
            $this->hydrateAffaire();
        endif;
        $this->chantierClient = $CI->managerClients->getClientById($this->chantierAffaire->getAffaireClientId());
    }

    public function hydrateAchats() {
        $CI = & get_instance();
        $this->chantierAchats = $CI->managerAchats->getAchats($this->chantierId);
    }

    public function cloturer($dateCloture) {
        $this->chantierEtat = 3;
        $this->chantierDateCloture = $dateCloture;
    }

    public function reouvrir() {
        $this->chantierEtat = 2;
    }

    function getChantierId() {
        return $this->chantierId;
    }

    function getChantierOriginId() {
        return $this->chantierOriginId;
    }

    function getChantierPlaceId() {
        return $this->chantierPlaceId;
    }

    function getChantierPlace() {
        return $this->chantierPlace;
    }

    function getChantierAffaireId() {
        return $this->chantierAffaireId;
    }

    function getChantierAffaire() {
        return $this->chantierAffaire;
    }

    function getChantierClient() {
        return $this->chantierClient;
    }

    function getChantierCategorieId() {
        return $this->chantierCategorieId;
    }

    function getChantierCategorie() {
        return $this->chantierCategorie;
    }

    function getChantierObjet() {
        return $this->chantierObjet;
    }

    function getChantierPrix() {
        return $this->chantierPrix;
    }

    function getChantierCouleur() {
        return $this->chantierCouleur;
    }

    function getChantierCouleurSecondaire() {
        return $this->chantierCouleurSecondaire;
    }

    function getChantierEtat() {
        return $this->chantierEtat;
    }

    function getChantierDateCloture() {
        return $this->chantierDateCloture;
    }

    function getChantierHeuresPrevues() {
        return $this->chantierHeuresPrevues;
    }

    function getChantierBudgetAchats() {
        return $this->chantierBudgetAchats;
    }

    function getChantierFraisGeneraux() {
        return $this->chantierFraisGeneraux;
    }

    function getChantierTauxHoraireMoyen() {
        return $this->chantierTauxHoraireMoyen;
    }

    function getChantierRemarque() {
        return $this->chantierRemarque;
    }

    function getChantierAchats() {
        return $this->chantierAchats;
    }

    function setChantierId($chantierId) {
        $this->chantierId = $chantierId;
    }

    function setChantierOriginId($chantierOriginId) {
        $this->chantierOriginId = $chantierOriginId;
    }

    function setChantierPlaceId($chantierPlaceId) {
        $this->chantierPlaceId = $chantierPlaceId;
    }

    function setChantierPlace($chantierPlace) {
        $this->chantierPlace = $chantierPlace;
    }

    function setChantierAffaireId($chantierAffaireId) {
        $this->chantierAffaireId = $chantierAffaireId;
    }

    function setChantierAffaire($chantierAffaire) {
        $this->chantierAffaire = $chantierAffaire;
    }

    function setChantierClient($chantierClient) {
        $this->chantierClient = $chantierClient;
    }

    function setChantierCategorieId($chantierCategorieId) {
        $this->chantierCategorieId = $chantierCategorieId;
    }

    function setChantierCategorie($chantierCategorie) {
        $this->chantierCategorie = $chantierCategorie;
    }

    function setChantierObjet($chantierObjet) {
        $this->chantierObjet = $chantierObjet;
    }

    function setChantierPrix($chantierPrix) {
        $this->chantierPrix = $chantierPrix;
    }

    function setChantierCouleur($chantierCouleur) {
        $this->chantierCouleur = $chantierCouleur;
    }

    function setChantierCouleurSecondaire($chantierCouleurSecondaire) {
        $this->chantierCouleurSecondaire = $chantierCouleurSecondaire;
    }

    function setChantierEtat($chantierEtat) {
        $this->chantierEtat = $chantierEtat;
    }

    function setChantierDateCloture($chantierDateCloture) {
        $this->chantierDateCloture = $chantierDateCloture;
    }

    function setChantierHeuresPrevues($chantierHeuresPrevues) {
        $this->chantierHeuresPrevues = $chantierHeuresPrevues;
    }

    function setChantierBudgetAchats($chantierBudgetAchats) {
        $this->chantierBudgetAchats = $chantierBudgetAchats;
    }

    function setChantierFraisGeneraux($chantierFraisGeneraux) {
        $this->chantierFraisGeneraux = $chantierFraisGeneraux;
    }

    function setChantierTauxHoraireMoyen($chantierTauxHoraireMoyen) {
        $this->chantierTauxHoraireMoyen = $chantierTauxHoraireMoyen;
    }

    function setChantierRemarque($chantierRemarque) {
        $this->chantierRemarque = $chantierRemarque;
    }

    function setChantierAchats($chantierAchats) {
        $this->chantierAchats = $chantierAchats;
    }

    function getChantierBudgetPrevu() {
        return $this->chantierBudgetPrevu;
    }

    function getChantierBudgetConsomme() {
        return $this->chantierBudgetConsomme;
    }

    function setChantierBudgetPrevu($chantierBudgetPrevu) {
        $this->chantierBudgetPrevu = $chantierBudgetPrevu;
    }

    function setChantierBudgetConsomme($chantierBudgetConsomme) {
        $this->chantierBudgetConsomme = $chantierBudgetConsomme;
    }

}
