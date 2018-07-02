<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->postes = array('1' => 'Fabrication', '2' => 'Pose', '3' => 'PAO', '4' => 'Dépannage');
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'une affaire dans la bdd
     *
     * @param int $affaireId ID de l'affaire
     * @return boolean TRUE si l'affaire existe
     */
    public function existAffaire($affaireId) {
        $this->form_validation->set_message('existAffaire', 'Cette affaire est introuvable.');
        if ($this->managerAffaires->count(array('affaireId' => $affaireId)) == 1 || !$affaireId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du composant dans la bdd
     *
     * @param int $id ID du composant
     * @return boolean TRUE si le composant exist
     */
    public function existComposant($composantId) {
        $this->form_validation->set_message('existComposant', 'Ce composant est introuvable.');
        if ($this->managerComposants->count(array('composantId' => $composantId)) == 1 || !$composantId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance de l'article dans la bdd
     *
     * @param int $id ID de l'article
     * @return boolean
     */
    public function existArticle($articleId) {
        $this->form_validation->set_message('existArticle', 'Cet article est introuvable.');
        if ($this->managerArticles->count(array('articleId' => $articleId)) == 1 || !$articleId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance de l'option dans la bdd
     *
     * @param int $id ID de l'option
     * @return boolean
     */
    public function existOption($optionId) {
        $this->form_validation->set_message('existOption', 'Cette option est introuvable.');
        if ($this->managerOptions->count(array('optionId' => $optionId)) == 1 || !$optionId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du client dans la bdd
     *
     * @param int $clientId ID du client
     * @return boolean TRUE si le client existe
     */
    public function existClient($clientId) {
        $this->form_validation->set_message('existClient', 'Ce client est introuvable.');
        if ($this->managerClients->count(array('clientId' => $clientId)) == 1 || !$clientId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du contact dans la bdd
     *
     * @param int $contactId ID du contact
     * @return boolean TRUE si le contact existe
     */
    public function existContact($contactId) {
        $this->form_validation->set_message('existContact', 'Ce contact est introuvable.');
        if ($this->managerContacts->count(array('contactId' => $contactId)) == 1 || !$contactId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du client dans la bdd
     *
     * @param int $factureId ID de la facture
     * @return boolean TRUE si la facture existe
     */
    public function existFacture($factureId) {
        $this->form_validation->set_message('existFacture', 'Cette facture est introuvable.');
        if ($this->managerFactures->count(array('factureId' => $factureId)) == 1 || !$factureId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance du reglement dans la bdd
     *
     * @param int $reglementId ID du réglement
     * @return boolean TRUE si le réglement existe
     */
    public function existReglement($reglementId) {
        $this->form_validation->set_message('existReglement', 'Ce réglement est introuvable.');
        if ($this->managerReglements->count(array('reglementId' => $reglementId)) == 1 || !$reglementId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un avoir dans la bdd
     *
     * @param int $avoirId ID de l'avoir
     * @return boolean TRUE si l'avoir existe
     */
    public function existAvoir($avoirId) {
        $this->form_validation->set_message('existAvoir', 'Cet avoir est introuvable.');
        if ($this->managerAvoirs->count(array('avoirId' => $avoirId)) == 1 || !$avoirId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un dossier dans la bdd
     *
     * @param int $dossierId ID du dossier
     * @return boolean TRUE si le dossier existe
     */
    public function existDossier($dossierId) {
        $this->form_validation->set_message('existDossier', 'Ce dossier est introuvable.');
        if ($this->managerDossiers->count(array('dossierId' => $dossierId)) > 0 || !$dossierId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'une affectation dans la bdd
     *
     * @param int $affectationId ID de l'affectation
     * @return boolean TRUE si l'affectation existe
     */
    public function existAffectation($affectationId) {
        $this->form_validation->set_message('existAffectation', 'Cette affectation est introuvable.');
        if ($this->managerAffectations->count(array('affectationId' => $affectationId)) == 1 || !$affectationId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un recurrent dans la bdd
     *
     * @param int $recurrentId ID du recurrent
     * @return boolean TRUE si le recurrent
     */
    public function existRecurrent($recurrentId) {
        $this->form_validation->set_message('existRecurrent', 'Ce recurrent est introuvable.');
        if ($this->managerRecurrents->count(array('recurrentId' => $recurrentId)) == 1 || !$recurrentId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'une équipe dans la bdd
     *
     * @param int $equipeId ID de l'équipe
     * @return boolean TRUE si l'équipe existe
     */
    public function existEquipe($equipeId) {
        $this->form_validation->set_message('existEquipe', 'Cette équipe est introuvable.');
        if ($this->managerEquipes->count(array('equipeId' => $equipeId)) == 1 || !$equipeId) :
            return true;
        else :
            return false;
        endif;
    }

    public function venteInit() {

        $dataSession = array('affaireId', 'affaireClientId', 'affaireExonerationTVA', 'affaireDate');
        $this->session->unset_userdata($dataSession);
        $this->cart->destroy();
        $this->session->set_userdata(
                array(
                    'pleaseSave' => 0,
                    'affaireType' => 1,
                    'affaireClients' => array(),
                    'affaireObjet' => '',
                    'affairePAO' => 0,
                    'affaireFabrication' => 0,
                    'affairePose' => 0
                )
        );
    }

    /**
     * Recalcule le solde d'une facture et la met à jour dans le BDD
     * @param Facture $facture Facture à traiter
     */
    public function setFactureSolde(Facture $facture) {
        $facture->solde();
        $this->managerFactures->editer($facture);
    }

    /**
     * Renumerote les affectations d'une journée pour un type
     * @param int $type ID du type
     * @param type $date Date
     */
    public function renumerotation($type, $date) {
        /* On recalcule les positions des autres affectations du même jour */
        $others = $this->managerAffectations->liste(array('affectationDate' => $date, 'affectationType' => $type));
        $num = 1;
        if ($others) :
            foreach ($others as $o) :
                $o->setAffectationPosition($num);
                $this->managerAffectations->editer($o);
                $num++;
            endforeach;
        endif;
    }

    /**
     * Calcule la marge d'un item du concepteur
     * @param Cart/Item $item Item du Cart Codeigniter
     * @return int Marge arrondie
     */
    public function majMargeArticle($item) {

        $totalAchats = 0;
        foreach ($item['composants'] as $o):
            if ($o['qte'] > 0):
                $totalAchats += round($o['qte'] * $o['prixAchat'], 2);
            endif;
        endforeach;

        $margeItem = $item['price'] - $totalAchats;
        $arrayMaj = array('rowid' => $item['rowid'], 'marge' => $margeItem);
        $this->cart->update($arrayMaj);

        return round($margeItem);
    }

}
