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
    protected $achatDate;
    protected $achatChantierId;
    protected $achatChantier;
    protected $achatFournisseurId;
    protected $achatFournisseur;
    protected $achatLivraisonOriginId;
    protected $achatLivraisonDate;
    protected $achatLivraisonAvancement;
    protected $achatLivraisonAvancementText;
    protected $achatDescription;
    protected $achatType;
    protected $achatTypeText;
    protected $achatQte;
    protected $achatQtePrevisionnel;
    protected $achatPrix;
    protected $achatPrixPrevisionnel;
    protected $achatTotal;
    protected $achatTotalPrevisionnel;
    protected $achatNbContraintes;
    protected $achatContraintesIds;
    protected $achatHTML;

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

        switch ($this->achatLivraisonAvancement):
            case 1:
                $this->achatLivraisonAvancementText = '<span class="badge badge-secondary">En attente</span>';
                break;
            case 2:
                $this->achatLivraisonAvancementText = '<span class="badge badge-info">Confirmée</span>';
                break;
            case 3:
                $this->achatLivraisonAvancementText = '<span class="badge badge-success">Receptionnée</span>';
                break;
            default :
                $this->achatLivraisonAvancementText = '-';
        endswitch;
        switch ($this->achatType):
            case 1:
                $this->achatTypeText = 'Matière première';
                break;
            case 2:
                $this->achatTypeText = 'Matériel';
                break;
            case 3:
                $this->achatTypeText = 'Outillage';
                break;
            case 4:
                $this->achatTypeText = 'Sous-traitance';
                break;
        endswitch;
        $CI = & get_instance();
        $this->achatContraintesIds = array();
        $query = $CI->db->select('affectationId')
                ->from('achats_affectations')
                ->where('achatId', $this->achatId)
                ->get();

        foreach ($query->result() AS $row):
            $this->achatContraintesIds[] = $row->affectationId;
        endforeach;
    }

    public function genereHTML() {

        if ($this->achatLivraisonDate):
            if (!$this->achatChantier):
                $this->hydrateChantier();
            endif;
            $html = '<div style="border:1px solid ' . $this->achatChantier->getChantierCouleurSecondaire() . ';'
                    . ' color: ' . $this->achatChantier->getChantierCouleurSecondaire() . ';'
                    . ' background-color: ' . $this->achatChantier->getChantierCouleur() . '"'
                    . ' class="livraison draggable"'
                    . ' data-achatid="' . $this->achatId . '"'
                    . ' data-chantierid="' . $this->achatChantierId . '"'
                    . ' data-contraintes = "' . implode(',', $this->achatContraintesIds) . '">';

            if (sizeof($this->achatContraintesIds) > 0):
                $html .= '<i class="fas fa-link"></i>';
            endif;
            $html .= '</div>';
            $this->achatHTML = $html;
        else:
            $this->achatHTML = null;
        endif;
    }

    public function hydrateFournisseur() {
        if ($this->achatFournisseurId):
            $CI = & get_instance();
            $this->achatFournisseur = $CI->managerFournisseurs->getFournisseurById($this->achatFournisseurId);
        else:
            $this->achatFournisseur = null;
        endif;
    }

    public function hydrateChantier() {
        $CI = & get_instance();
        $this->achatChantier = $CI->managerChantiers->getChantierById($this->achatChantierId);
    }

    function getAchatId() {
        return $this->achatId;
    }

    function getAchatDate() {
        return $this->achatDate;
    }

    function getAchatChantierId() {
        return $this->achatChantierId;
    }

    function getAchatChantier() {
        return $this->achatChantier;
    }

    function getAchatFournisseurId() {
        return $this->achatFournisseurId;
    }

    function getAchatFournisseur() {
        return $this->achatFournisseur;
    }

    function getAchatLivraisonOriginId() {
        return $this->achatLivraisonOriginId;
    }

    function getAchatLivraisonDate() {
        return $this->achatLivraisonDate;
    }

    function getAchatLivraisonAvancement() {
        return $this->achatLivraisonAvancement;
    }

    function getAchatLivraisonAvancementText() {
        return $this->achatLivraisonAvancementText;
    }

    function getAchatDescription() {
        return $this->achatDescription;
    }

    function getAchatType() {
        return $this->achatType;
    }

    function getAchatTypeText() {
        return $this->achatTypeText;
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

    function getAchatNbContraintes() {
        return $this->achatNbContraintes;
    }

    function getAchatContraintesIds() {
        return $this->achatContraintesIds;
    }

    function setAchatId($achatId) {
        $this->achatId = $achatId;
    }

    function setAchatDate($achatDate) {
        $this->achatDate = $achatDate;
    }

    function setAchatChantierId($achatChantierId) {
        $this->achatChantierId = $achatChantierId;
    }

    function setAchatChantier($achatChantier) {
        $this->achatChantier = $achatChantier;
    }

    function setAchatFournisseurId($achatFournisseurId) {
        $this->achatFournisseurId = $achatFournisseurId;
    }

    function setAchatFournisseur($achatFournisseur) {
        $this->achatFournisseur = $achatFournisseur;
    }

    function setAchatLivraisonOriginId($achatLivraisonOriginId) {
        $this->achatLivraisonOriginId = $achatLivraisonOriginId;
    }

    function setAchatLivraisonDate($achatLivraisonDate) {
        $this->achatLivraisonDate = $achatLivraisonDate;
    }

    function setAchatLivraisonAvancement($achatLivraisonAvancement) {
        $this->achatLivraisonAvancement = $achatLivraisonAvancement;
    }

    function setAchatLivraisonAvancementText($achatLivraisonAvancementText) {
        $this->achatLivraisonAvancementText = $achatLivraisonAvancementText;
    }

    function setAchatDescription($achatDescription) {
        $this->achatDescription = $achatDescription;
    }

    function setAchatType($achatType) {
        $this->achatType = $achatType;
    }

    function setAchatTypeText($achatTypeText) {
        $this->achatTypeText = $achatTypeText;
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

    function setAchatNbContraintes($achatNbContraintes) {
        $this->achatNbContraintes = $achatNbContraintes;
    }

    function setAchatContraintesIds($achatContraintesIds) {
        $this->achatContraintesIds = $achatContraintesIds;
    }

    function getAchatHTML() {
        return $this->achatHTML;
    }

    function setAchatHTML($achatHTML) {
        $this->achatHTML = $achatHTML;
    }

}
