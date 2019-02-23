<?php

/**
 * Classe de gestion des Affaires
 * Manager : Model_Affaires
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com

  ALTER TABLE `chantiers` ADD `chantierCoutMo` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Coût des heures pointées généré à la clôture du chantier' AFTER `chantierPerformanceHeures`;

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
    protected $chantierEtatHtml;
    protected $chantierDateCloture;
    protected $chantierHeuresPrevues;
    protected $chantierCoutMo;
    protected $chantierBudgetAchats;
    protected $chantierFraisGeneraux;
    protected $chantierTauxHoraireMoyen;
    protected $chantierRemarque;
//    protected $chantierLivraisons;
    protected $chantierAchats;
    protected $chantierAffectations;
    /* TRIGGERED */
    protected $chantierBudgetPrevu; /* Somme des achats prévus */
    protected $chantierBudgetConsomme; /* Somme des achats réalisés */
    protected $chantierheuresPlanifiees; /* Somme des heures plannifiées */
    protected $chantierheuresPointees; /* Somme des heures pointées */
    /* VIRTUAL */
    protected $chantierDeltaHeures; /* heures pointees - heures prévues */
    protected $chantierPerformanceHeures; /* % de gain/perte des heures pointées par rapport aux heures prévues */
    protected $chantierPerformancesPersonnels;

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
        $this->chantierCategorie = $categorie ? $categorie->getCategorieNom() : 'Non classé';
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

    public function hydrateAffectations() {
        $CI = & get_instance();
        $this->chantierAffectations = $CI->managerAffectations->getAffectations(array('affectationChantierId' => $this->chantierId), 'affectationDebutDate DESC');
    }

    public function hydratePerformancesPersonnels() {
        $CI = & get_instance();
        $this->chantierPerformancesPersonnels = $CI->managerPerformanceChantiersPersonnels->getPerformancesByChantierId($this);
    }

    public function cloture() {
        $CI = & get_instance();
        $this->chantierEtat = 2;
        if (!$this->chantierAffaire):
            $this->hydrateAffaire();
        endif;
        /* On initialisa la date de cloture à la date de création de son affaire dans le cas ou aucune heure ne serait saisie */
        $dateCloture = $this->chantierAffaire->getAffaireCreation();
        $coutMo = 0;

        if (!$this->chantierAffectations):
            $this->hydrateAffectations();
        endif;
        if (!empty($this->chantierAffectations)):
            foreach ($this->chantierAffectations as $affectation):
                $affectation->hydrateHeures();

                if (!empty($affectation->getAffectationHeures())):
                    foreach ($affectation->getAffectationHeures() as $heure):
                        /* Date de cloture */
                        if ($heure->getHeureDate() > $dateCloture):
                            $dateCloture = $heure->getHeureDate();
                        endif;
                        /* Cout Mo */
                        $taux = $CI->managerTauxHoraires->getTauxHoraireCible($affectation->getAffectationPersonnelId(), $heure->getHeureDate());
                        if (empty($taux)):
                            $taux = $CI->session->userdata('etablissementTHM');
                        endif;
                        $coutMo += round($heure->getHeureDuree() * $taux / 60, 2);
                    endforeach;
                endif;

            endforeach;
        endif;
        $this->chantierDateCloture = $dateCloture;
        $this->chantierCoutMo = $coutMo;
    }

    public function reouvrir() {
        $this->chantierEtat = 2;
        $this->chantierCoutMo = NULL;
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

    function getChantierEtatHtml() {
        return $this->chantierEtatHtml;
    }

    function getChantierDateCloture() {
        return $this->chantierDateCloture;
    }

    function getChantierHeuresPrevues() {
        return $this->chantierHeuresPrevues;
    }

    function getChantierCoutMo() {
        return $this->chantierCoutMo;
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

    function getChantierAffectations() {
        return $this->chantierAffectations;
    }

    function getChantierBudgetPrevu() {
        return $this->chantierBudgetPrevu;
    }

    function getChantierBudgetConsomme() {
        return $this->chantierBudgetConsomme;
    }

    function getChantierheuresPlanifiees() {
        return $this->chantierheuresPlanifiees;
    }

    function getChantierheuresPointees() {
        return $this->chantierheuresPointees;
    }

    function getChantierDeltaHeures() {
        return $this->chantierDeltaHeures;
    }

    function getChantierPerformanceHeures() {
        return $this->chantierPerformanceHeures;
    }

    function getChantierPerformancesPersonnels() {
        return $this->chantierPerformancesPersonnels;
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

    function setChantierEtatHtml($chantierEtatHtml) {
        $this->chantierEtatHtml = $chantierEtatHtml;
    }

    function setChantierDateCloture($chantierDateCloture) {
        $this->chantierDateCloture = $chantierDateCloture;
    }

    function setChantierHeuresPrevues($chantierHeuresPrevues) {
        $this->chantierHeuresPrevues = $chantierHeuresPrevues;
    }

    function setChantierCoutMo($chantierCoutMo) {
        $this->chantierCoutMo = $chantierCoutMo;
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

    function setChantierAffectations($chantierAffectations) {
        $this->chantierAffectations = $chantierAffectations;
    }

    function setChantierBudgetPrevu($chantierBudgetPrevu) {
        $this->chantierBudgetPrevu = $chantierBudgetPrevu;
    }

    function setChantierBudgetConsomme($chantierBudgetConsomme) {
        $this->chantierBudgetConsomme = $chantierBudgetConsomme;
    }

    function setChantierheuresPlanifiees($chantierheuresPlanifiees) {
        $this->chantierheuresPlanifiees = $chantierheuresPlanifiees;
    }

    function setChantierheuresPointees($chantierheuresPointees) {
        $this->chantierheuresPointees = $chantierheuresPointees;
    }

    function setChantierDeltaHeures($chantierDeltaHeures) {
        if (substr($chantierDeltaHeures, 0, 1) == '-'):
            $this->chantierDeltaHeures = $chantierDeltaHeures;
        else:
            $this->chantierDeltaHeures = '+' . $chantierDeltaHeures;
        endif;
    }

    function setChantierPerformanceHeures($chantierPerformanceHeures) {
        if (substr($chantierPerformanceHeures, 0, 1) == '-'):
            $this->chantierPerformanceHeures = $chantierPerformanceHeures;
        else:
            $this->chantierPerformanceHeures = '+' . $chantierPerformanceHeures;
        endif;
    }

    function setChantierPerformancesPersonnels($chantierPerformancesPersonnels) {
        $this->chantierPerformancesPersonnels = $chantierPerformancesPersonnels;
    }

}
