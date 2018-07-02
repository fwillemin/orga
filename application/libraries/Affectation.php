<?php

/**
 * Classe de gestion des Affectations
 * Manager : Model_affectations
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
/*
  ALTER TABLE `affectations` CHANGE `affectationType` `affectationType` TINYINT(4) NOT NULL COMMENT '1=Fabrication, 2=Pose, 3=PAO, 4=Depannage';
  UPDATE `affectations` SET `affectationType` = 4 WHERE `affectationEquipeId` = 3
  ALTER TABLE affectations DROP FOREIGN KEY affectations_ibfk_2
  ALTER TABLE `affectations` DROP `affectationEquipeId`
 */
class Affectation {

    const colorAttente = '#dbe5f8';
    const colorEncours = '#F8B038';
    const colorTermine = '#0F6837';

    protected $affectationId;
    protected $affectationDossierId;
    protected $affectationDossier;
    protected $affectationAffaireId;
    protected $affectationAffaire;
    protected $affectationType;
    protected $affectationDate;
    protected $affectationIntervenant;
    protected $affectationPosition;
    protected $affectationCommentaire;
    protected $affectationEtat; /* 1=Attente, 2=Encours, 3=Terminé */
    protected $affectationParentClos;
    protected $affectationClient;
    protected $affectationDescriptif;
    protected $affectationCouleur;
    protected $affectationFontColor;
    protected $affectationDossierClos;

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

        $this->colorier();
    }

    public function hydrateParent() {
        $CI = & get_instance();
        if ($this->affectationAffaireId):
            $affaire = $CI->managerAffaires->getAffaireById($this->affectationAffaireId);
            $this->affectationAffaire = $affaire;
            $affaire->hydrateClients();
            $this->affectationClient = $affaire->getAffaireClients()[0]->getClientRaisonSociale();
            $this->affectationParentClos = $affaire->getAffaireCloture();
            $this->affectationDescriptif = $affaire->getAffaireObjet();
        else:
            $dossier = $CI->managerDossiers->getDossierById($this->affectationDossierId);
            $this->affectationDossier = $dossier;
            $this->affectationClient = $dossier->getDossierClient();
            $this->affectationParentClos = $dossier->getDossierClos();
            $this->affectationDescriptif = $dossier->getDossierDescriptif();
        endif;
    }

    private function colorier() {
        /* Couleur de l'affectation */
        switch ($this->getAffectationEtat()):
            case 1:
                $this->setAffectationCouleur(self::colorAttente);
                $this->setAffectationFontColor('#000000');
                break;
            case 2:
                $this->setAffectationCouleur(self::colorEncours);
                $this->setAffectationFontColor('#000000');
                break;
            case 3:
                $this->setAffectationCouleur(self::colorTermine);
                $this->setAffectationFontColor('#FFFFFF');
                break;
        endswitch;
    }

    function nextStep() {
        switch ($this->getAffectationEtat()):
            case 1:
                $this->setAffectationEtat(2);
                break;
            case 2:
                $this->setAffectationEtat(3);
                break;
            case 3:
                $this->setAffectationEtat(1);
                break;
        endswitch;
        $this->colorier();
    }

    function getAffectationId() {
        return $this->affectationId;
    }

    function getAffectationDossierId() {
        return $this->affectationDossierId;
    }

    function getAffectationDossier() {
        return $this->affectationDossier;
    }

    function getAffectationAffaireId() {
        return $this->affectationAffaireId;
    }

    function getAffectationAffaire() {
        return $this->affectationAffaire;
    }

    function getAffectationType() {
        return $this->affectationType;
    }

    function getAffectationDate() {
        return $this->affectationDate;
    }

    function getAffectationIntervenant() {
        return $this->affectationIntervenant;
    }

    function getAffectationPosition() {
        return $this->affectationPosition;
    }

    function getAffectationCommentaire() {
        return $this->affectationCommentaire;
    }

    function getAffectationEtat() {
        return $this->affectationEtat;
    }

    function getAffectationParentClos() {
        return $this->affectationParentClos;
    }

    function getAffectationClient() {
        return $this->affectationClient;
    }

    function getAffectationDescriptif() {
        return $this->affectationDescriptif;
    }

    function getAffectationCouleur() {
        return $this->affectationCouleur;
    }

    function getAffectationFontColor() {
        return $this->affectationFontColor;
    }

    function getAffectationDossierClos() {
        return $this->affectationDossierClos;
    }

    function setAffectationId($affectationId) {
        $this->affectationId = $affectationId;
    }

    function setAffectationDossierId($affectationDossierId) {
        $this->affectationDossierId = $affectationDossierId;
    }

    function setAffectationDossier($affectationDossier) {
        $this->affectationDossier = $affectationDossier;
    }

    function setAffectationAffaireId($affectationAffaireId) {
        $this->affectationAffaireId = $affectationAffaireId;
    }

    function setAffectationAffaire($affectationAffaire) {
        $this->affectationAffaire = $affectationAffaire;
    }

    function setAffectationType($affectationType) {
        $this->affectationType = $affectationType;
    }

    function setAffectationDate($affectationDate) {
        $this->affectationDate = $affectationDate;
    }

    function setAffectationIntervenant($affectationIntervenant) {
        $this->affectationIntervenant = $affectationIntervenant;
    }

    function setAffectationPosition($affectationPosition) {
        $this->affectationPosition = $affectationPosition;
    }

    function setAffectationCommentaire($affectationCommentaire) {
        $this->affectationCommentaire = $affectationCommentaire;
    }

    function setAffectationEtat($affectationEtat) {
        $this->affectationEtat = $affectationEtat;
    }

    function setAffectationParentClos($affectationParentClos) {
        $this->affectationParentClos = $affectationParentClos;
    }

    function setAffectationClient($affectationClient) {
        $this->affectationClient = $affectationClient;
    }

    function setAffectationDescriptif($affectationDescriptif) {
        $this->affectationDescriptif = $affectationDescriptif;
    }

    function setAffectationCouleur($affectationCouleur) {
        $this->affectationCouleur = $affectationCouleur;
    }

    function setAffectationFontColor($affectationFontColor) {
        $this->affectationFontColor = $affectationFontColor;
    }

    function setAffectationDossierClos($affectationDossierClos) {
        $this->affectationDossierClos = $affectationDossierClos;
    }

}
