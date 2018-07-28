<?php

/**
 * Classe de gestion des Affaires
 * Manager : Model_Affaires
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*

 */
class Affaire {

    protected $affaireId;
    protected $affaireOriginId;
    protected $affaireEtablissementId;
    protected $affairePlaceId;
    protected $affairePlace;
    protected $affaireCreation;
    protected $affaireClientId;
    protected $affaireClient;
    protected $affaireCategorieId;
    protected $affaireCategorie;
    protected $affaireCommercialId;
    protected $affaireCommercial;
    protected $affaireDevis;
    protected $affaireObjet;
    protected $affairePrix;
    protected $affaireDateSignature;
    protected $affaireDateCloture;
    protected $affaireEtat;
    protected $affaireEtatHtml;
    protected $affaireCouleur;
    protected $affaireCouleurSecondaire;
    protected $affaireRemarque;
    protected $affaireChantiers;
    protected $affairePrixNonAttribue; /* affairePrix - SOMME(chantierPrix) */

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
        $categorie = $CI->managerCategories->getCategorieById($this->affaireCategorieId);
        $this->affaireCategorie = $categorie ? $categorie->getCategorieNom() : '<span class="badge badge-warning">Non classé</span>';
        switch ($this->affaireEtat):
            case 1:
                $this->affaireEtatHtml = '<span class="badge badge-info">Devis</span>';
                break;
            case 2:
                $this->affaireEtatHtml = '<span class="badge badge-success">En cours</span>';
                break;
            case 3:
                $this->affaireEtatHtml = '<span class="badge badge-secondary">Clôturée</span>';
                break;
        endswitch;
    }

    public function hydratePlace() {
        $CI = & get_instance();
        $this->affairePlace = $CI->managerPlaces->getPlaceById($this->affairePlaceId);
    }

    function hydrateChantiers() {
        $CI = & get_instance();
        $this->affaireChantiers = $CI->managerChantiers->getChantiersByAffaireId($this->affaireId);
        $this->affairePrixNonAttribue = $this->affairePrix;
        if (!empty($this->affaireChantiers)):
            foreach ($this->affaireChantiers as $chantier):
                $this->affairePrixNonAttribue -= $chantier->getChantierPrix();
            endforeach;
        endif;
    }

    public function cloturer($dateCloture) {
        $this->affaireEtat = 3;
        $this->affaireDateCloture = $dateCloture;
    }

    public function reouvrir() {
        $this->affaireEtat = 2;
    }

    public function hydrateClient() {
        $CI = & get_instance();
        $this->affaireClient = $CI->managerClients->getClientById($this->affaireClientId);
    }

    public function hydrateCommercial() {
        $CI = & get_instance();
        $this->affaireCommercial = $CI->managerUtilisateurs->getUtilisateurById($this->affaireCommercialId);
    }

    function getAffairePrixNonAttribue() {
        return $this->affairePrixNonAttribue;
    }

    function setAffairePrixNonAttribue($affairePrixNonAttribue) {
        $this->affairePrixNonAttribue = $affairePrixNonAttribue;
    }

    function getAffaireId() {
        return $this->affaireId;
    }

    function getAffaireOriginId() {
        return $this->affaireOriginId;
    }

    function getAffaireEtablissementId() {
        return $this->affaireEtablissementId;
    }

    function getAffairePlaceId() {
        return $this->affairePlaceId;
    }

    function getAffairePlace() {
        return $this->affairePlace;
    }

    function getAffaireCreation() {
        return $this->affaireCreation;
    }

    function getAffaireClientId() {
        return $this->affaireClientId;
    }

    function getAffaireClient() {
        return $this->affaireClient;
    }

    function getAffaireCategorieId() {
        return $this->affaireCategorieId;
    }

    function getAffaireCategorie() {
        return $this->affaireCategorie;
    }

    function getAffaireCommercialId() {
        return $this->affaireCommercialId;
    }

    function getAffaireCommercial() {
        return $this->affaireCommercial;
    }

    function getAffaireDevis() {
        return $this->affaireDevis;
    }

    function getAffaireObjet() {
        return $this->affaireObjet;
    }

    function getAffairePrix() {
        return $this->affairePrix;
    }

    function getAffaireDateSignature() {
        return $this->affaireDateSignature;
    }

    function getAffaireDateCloture() {
        return $this->affaireDateCloture;
    }

    function getAffaireEtat() {
        return $this->affaireEtat;
    }

    function getAffaireEtatHtml() {
        return $this->affaireEtatHtml;
    }

    function getAffaireCouleur() {
        return $this->affaireCouleur;
    }

    function getAffaireCouleurSecondaire() {
        return $this->affaireCouleurSecondaire;
    }

    function getAffaireRemarque() {
        return $this->affaireRemarque;
    }

    function getAffaireChantiers() {
        return $this->affaireChantiers;
    }

    function setAffaireId($affaireId) {
        $this->affaireId = $affaireId;
    }

    function setAffaireOriginId($affaireOriginId) {
        $this->affaireOriginId = $affaireOriginId;
    }

    function setAffaireEtablissementId($affaireEtablissementId) {
        $this->affaireEtablissementId = $affaireEtablissementId;
    }

    function setAffairePlaceId($affairePlaceId) {
        $this->affairePlaceId = $affairePlaceId;
    }

    function setAffairePlace($affairePlace) {
        $this->affairePlace = $affairePlace;
    }

    function setAffaireCreation($affaireCreation) {
        $this->affaireCreation = $affaireCreation;
    }

    function setAffaireClientId($affaireClientId) {
        $this->affaireClientId = $affaireClientId;
    }

    function setAffaireClient($affaireClient) {
        $this->affaireClient = $affaireClient;
    }

    function setAffaireCategorieId($affaireCategorieId) {
        $this->affaireCategorieId = $affaireCategorieId;
    }

    function setAffaireCategorie($affaireCategorie) {
        $this->affaireCategorie = $affaireCategorie;
    }

    function setAffaireCommercialId($affaireCommercialId) {
        $this->affaireCommercialId = $affaireCommercialId;
    }

    function setAffaireCommercial($affaireCommercial) {
        $this->affaireCommercial = $affaireCommercial;
    }

    function setAffaireDevis($affaireDevis) {
        $this->affaireDevis = $affaireDevis;
    }

    function setAffaireObjet($affaireObjet) {
        $this->affaireObjet = $affaireObjet;
    }

    function setAffairePrix($affairePrix) {
        $this->affairePrix = $affairePrix;
    }

    function setAffaireDateSignature($affaireDateSignature) {
        $this->affaireDateSignature = $affaireDateSignature;
    }

    function setAffaireDateCloture($affaireDateCloture) {
        $this->affaireDateCloture = $affaireDateCloture;
    }

    function setAffaireEtat($affaireEtat) {
        $this->affaireEtat = $affaireEtat;
    }

    function setAffaireEtatHtml($affaireEtatHtml) {
        $this->affaireEtatHtml = $affaireEtatHtml;
    }

    function setAffaireCouleur($affaireCouleur) {
        $this->affaireCouleur = $affaireCouleur;
    }

    function setAffaireCouleurSecondaire($affaireCouleurSecondaire) {
        $this->affaireCouleurSecondaire = $affaireCouleurSecondaire;
    }

    function setAffaireRemarque($affaireRemarque) {
        $this->affaireRemarque = $affaireRemarque;
    }

    function setAffaireChantiers($affaireChantiers) {
        $this->affaireChantiers = $affaireChantiers;
    }

}
