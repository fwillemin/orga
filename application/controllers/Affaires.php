<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Affaires extends My_Controller {

    const tauxTVA = 20;

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) :
            redirect('secure/login');
        endif;

        $this->affaireError = ''; /* Erreurs lors de l'enregistrement d'une affaire */
    }

    /**
     * Verifie que l'affaire en cours est correctement saisie
     * @return boolean
     */
    private function isAffaireCorrectlySet() {

        $this->affaireError = '';

        if ($this->session->userdata('affaireId') && !$this->existAffaire($this->session->userdata('affaireId'))):
            $this->affaireError .= 'Cette affaire est introuvable<br>';
        endif;

        if (!$this->session->userdata('affaireClients')):
            $this->affaireError .= 'Vous devez selectionnez au moins 1 client.<br>';
        endif;

        if (empty($this->cart->contents())):
            $this->affaireError .= 'Il n\'y a aucun article dans le concepteur.<br>';
        endif;

        if ($this->affaireError != ''):
            return false;
        else:
            return true;
        endif;
    }

    /**
     * Retourne les montants TVA et TTC pour un montant HT fourni
     * @param float $totalHT Montant HT à traiter
     * @return array $totaux Montants HT, TVA et TTC
     */
    private function getAffaireTotaux($totalHT) {

        $totaux['ht'] = $totalHT;
        $totaux['tva'] = round($totalHT * (self::tauxTVA / 100), 2);
        $totaux['ttc'] = $totaux['ht'] + $totaux['tva'];

        return $totaux;
    }

    /**
     * Retourne la liste de toutes les affaires
     */
    public function getAllAffaires() {
        $affaires = $this->managerAffaires->listeAll(array('affaireCloture' => 0), 'affairedate DESC', 'array');
        if (!empty($affaires)):
            foreach ($affaires as $a):

                /* Gestion de l'avancement avec les affectations */
                $a->affaireTerminee = 1;
                if ($a->affairePAO):
                    $affectations = $this->managerAffectations->liste(array('affectationAffaireId' => $a->affaireId, 'affectationType' => 3));
                    if (empty($affectations)):
                        $a->affaireTerminee = 0;
                    else:
                        foreach ($affectations as $affect):
                            if ($affect->getAffectationEtat() < 3):
                                $a->affaireTerminee = 0;
                                continue;
                            endif;
                        endforeach;
                    endif;
                endif;
                if ($a->affaireTerminee == 1 && $a->affaireFabrication):
                    $affectations = $this->managerAffectations->liste(array('affectationAffaireId' => $a->affaireId, 'affectationType' => 1));
                    if (empty($affectations)):
                        $a->affaireTerminee = 0;
                    else:
                        foreach ($affectations as $affect):
                            if ($affect->getAffectationEtat() < 3):
                                $a->affaireTerminee = 0;
                                continue;
                            endif;
                        endforeach;
                    endif;
                endif;
                if ($a->affaireTerminee == 1 && $a->affairePose):
                    $affectations = $this->managerAffectations->liste(array('affectationAffaireId' => $a->affaireId, 'affectationType' => 2));
                    if (empty($affectations)):
                        $a->affaireTerminee = 0;
                    else:
                        foreach ($affectations as $affect):
                            if ($affect->getAffectationEtat() < 3):
                                $a->affaireTerminee = 0;
                                continue;
                            endif;
                        endforeach;
                    endif;
                endif;

                if ($a->affaireCloture == 1):
                    $a->avancement = 'Clôturée';
                elseif ($a->affaireTerminee == 1 && $a->affaireCommandeId > 0 && $a->affaireDevisId > 0):
                    $a->avancement = '<span style="color: green;">Terminée</span>';
                elseif ($a->affaireCommandeId > 0):
                    $a->avancement = '<span style="color: purple;">En cours</span>';
                elseif ($a->affaireDevisId > 0):
                    $a->avancement = '<span style="color: orange;">Devis envoyé le ' . date('d/m/y', $a->affaireDevisDate) . '</span>';
                else:
                    $a->avancement = '<span style="color: steelblue;">Conception</span>';
                endif;
                $a->totalEnFacture = $this->managerFactures->getSommeFacturesByAffaireId($a->affaireId);
            endforeach;
        endif;
        echo json_encode($affaires);
    }

    /**
     * Enregistre en BDD l'affaire en cours dans le concepteur
     */
    public function addAffaire() {

        if ($this->isAffaireCorrectlySet()):

            $totauxAffaire = $this->getAffaireTotaux($this->cart->total());

            if ($this->session->userdata('affaireId')):

                $affaire = $this->managerAffaires->getAffaireById($this->session->userdata('affaireId'));
                $affaire->setAffaireType($this->session->userdata('affaireType'));
                $affaire->setAffaireObjet($this->session->userdata('affaireObjet'));
                $affaire->setAffaireTotalHT($totauxAffaire['ht']);
                $affaire->setAffaireTotalTVA($totauxAffaire['tva']);
                $affaire->setAffaireTotalTTC($totauxAffaire['ttc']);
                $affaire->setAffairePAO($this->session->userdata('affairePAO'));
                $affaire->setAffaireFabrication($this->session->userdata('affaireFabrication'));
                $affaire->setAffairePose($this->session->userdata('affairePose'));

                $this->managerAffaires->editer($affaire);

            else:

                $dataAffaire = array(
                    'affaireType' => $this->session->userdata('affaireType'),
                    'affaireObjet' => $this->session->userdata('affaireObjet'),
                    'affaireDate' => time(),
                    'affaireTauxTVA' => self::tauxTVA,
                    'affaireTotalHT' => $totauxAffaire['ht'],
                    'affaireTotalTVA' => $totauxAffaire['tva'],
                    'affaireTotalTTC' => $totauxAffaire['ttc'],
                    'affairePAO' => $this->session->userdata('affairePAO'),
                    'affaireFabrication' => $this->session->userdata('affaireFabrication'),
                    'affairePose' => $this->session->userdata('affairePose')
                );

                $affaire = new Affaire($dataAffaire);
                $this->managerAffaires->ajouter($affaire);

            endif;

            $this->enregistrementArticlesConcepteur($affaire);
            $this->enregistrementClientsConcepteur($affaire);

            $this->session->set_userdata('affaireId', $affaire->getAffaireId());

            $this->session->set_userdata('pleaseSave', 0);
            echo json_encode(array('type' => 'success', 'affaireId' => $affaire->getAffaireId()));
            exit;

        else:
            echo json_encode(array('type' => 'error', 'message' => $this->affaireError));
            exit;
        endif;
    }

    /**
     * Enregistre tous les articles et options du concepteur (Cart) sur une affaire
     * @param Affaire $affaire Affaire à associer aux Articles et Options
     */
    private function enregistrementArticlesConcepteur(Affaire $affaire) {

        //$this->managerAffaireArticles->resetAffaire($affaire);

        $this->db->trans_begin();

        foreach ((array) $this->cart->contents() as $item):

            if ($item['articleHT'] != $item['prixVendu']):
                $force = true;
            else:
                $force = false;
            endif;

            if ($item['affaireArticleId']):

                $affaireArticle = $this->managerAffaireArticles->getAffaireArticleById($item['affaireArticleId']);
                if (empty($affaireArticle)):
                    log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' ' . 'Modification d\'un article inexistant : ' . $item['affaireArticleId']);
                    return false;
                endif;

                $affaireArticle->setAffaireArticleDesignation($item['name']);
                $affaireArticle->setAffaireArticleDescription($item['description']);
                $affaireArticle->setAffaireArticleQte($item['qty']);
                $affaireArticle->setAffaireArticleTarif($item['prixVendu']);
                $affaireArticle->setAffaireArticleRemise($item['remise']);
                $affaireArticle->setAffaireArticlePU($item['price']);
                $affaireArticle->setAffaireArticleTotalHT($item['subtotal']);
                $affaireArticle->setAffaireArticlePrixForce($force);

                $this->managerAffaireArticles->editer($affaireArticle);

                foreach ((array) $item['composants'] as $key => $option):

                    if ($option['affaireOptionId']):
                        /* Option existante */
                        $affaireOption = $this->managerAffaireOptions->getAffaireOptionById($option['affaireOptionId']);
                        if (!$affaireOption):
                            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' ' . 'Erreur dans la récupération de l\'option ' . $option['affaireOptionId']);
                        else:

                            $affaireOption->setAffaireOptionQte($option['qte']);
                            $this->managerAffaireOptions->editer($affaireOption);
                            $listeOptionsOk[] = $affaireOption->getAffaireOptionId();

                        endif;

                    else:

                        /* Nouvelle option pour l'article */
                        $newOptionId = $this->saveOption($affaireArticle, $option);
                        $option['affaireOptionId'] = $newOptionId;
                        $item['composants'][$key] = $option;

                        $listeOptionsOk[] = $newOptionId;

                    endif;

                endforeach;

                /* On efface les eventuelles options qui ne sont pas conservées */
                $this->deleteOldOptionsArticleAffaire($affaireArticle, $listeOptionsOk);

            else:

                /**
                 * Nouvel article et nouvelles options
                 */
                $dataArticle = array(
                    'affaireArticleAffaireId' => $affaire->getAffaireId(),
                    'affaireArticleArticleId' => explode('-', $item['id'])[0],
                    'affaireArticleDesignation' => $item['name'],
                    'affaireArticleDescription' => $item['description'],
                    'affaireArticleQte' => $item['qty'],
                    'affaireArticleTarif' => $item['prixVendu'],
                    'affaireArticleRemise' => $item['remise'],
                    'affaireArticlePU' => $item['price'], /* Prix vendu - remise */
                    'affaireArticleTotalHT' => $item['subtotal'],
                    'affaireArticlePrixForce' => $force
                );

                $affaireArticle = new AffaireArticle($dataArticle);
                $this->managerAffaireArticles->ajouter($affaireArticle);

                $this->cart->update(array('rowid' => $item['rowid'], 'affaireArticleId' => $affaireArticle->getAffaireArticleId()));

                foreach ((array) $item['composants'] as $key => $option):
                    $newOptionId = $this->saveOption($affaireArticle, $option);
                    $option['affaireOptionId'] = $newOptionId;
                    $item['composants'][$key] = $option;
                endforeach;

            endif;

            //log_message('error', __CLASS__.'/'.__FUNCTION__.print_r($item['composants'], 1));
            $this->cart->update(array('rowid' => $item['rowid'], 'composants' => $item['composants']));

            $listeAffaireArticleId[] = $this->cart->get_item($item['rowid'])['affaireArticleId'];

        endforeach;


        /* On supprime les articles de l'affaire qui ne sont plus dans le cart */
        /* Ce sont les articles supprimés dans le concepteur depuis la dernière sauvagarde */
        $this->deleteOldArticlesAffaire($affaire, $listeAffaireArticleId);

        if ($this->db->trans_status() === FALSE):
            $this->db->trans_rollback();
        else:
            $this->db->trans_commit();
        endif;
    }

    /**
     * Enregistre une option pour un article lors de l'enregistrement d'une affaire
     * @param AffaireArticle $affaireArticle Article d'une affaire
     * @param array $option Tableau issu du cart et correspondant à un composant d'un article
     */
    private function saveOption(AffaireArticle $affaireArticle, $option) {

        $optionBase = $this->managerOptions->getOptionById($option['optionId']);
        $dataOption = array(
            'affaireOptionAffaireId' => $affaireArticle->getAffaireArticleAffaireId(),
            'affaireOptionArticleId' => $affaireArticle->getAffaireArticleId(),
            'affaireOptionOptionId' => $option['optionId'],
            'affaireOptionComposantId' => $optionBase->getOptionComposantId(),
            'affaireOptionQte' => $option['qte'],
            'affaireOptionPU' => $option['prix'],
            'affaireOptionOriginel' => $option['originel']
        );

        $newOption = new AffaireOption($dataOption);
        $this->managerAffaireOptions->ajouter($newOption);

        return $newOption->getAffaireOptionId();
    }

    /**
     * Efface de la BDD les articles d'une affaire qui ne sont plus dans le concepteur au moment de la sauvegarde de l'affaire
     * @param Affaire $affaire
     * @param type $listeArticlesOK
     */
    private function deleteOldArticlesAffaire(Affaire $affaire, $listeArticlesOK) {

        $articlesInBDD = $this->managerAffaireArticles->getAffaireArticlesByAffaireId($affaire->getAffaireId());
        if ($articlesInBDD):

            foreach ($articlesInBDD as $a):
                if (!in_array($a->getAffaireArticleId(), $listeArticlesOK)):
                    $this->managerAffaireArticles->delete($a);
                endif;
            endforeach;

        endif;
    }

    /**
     * Efface de la BDD les options d'un article qui ne sont plus dans le concepteur au moment de la sauvegarde de l'affaire
     * @param AffaireArticle $affaireArticle Article de l'affaire à nettoyer de ses options obsolètes
     * @param array $listeOptionsOK Liste des ID des options qui sont conservées.
     */
    private function deleteOldOptionsArticleAffaire(AffaireArticle $affaireArticle, $listeOptionsOK) {

        $optionsInBDD = $this->managerAffaireOptions->getAffaireOptionByAffaireArticleId($affaireArticle->getAffaireArticleId());
        if ($optionsInBDD):

            foreach ($optionsInBDD as $o):
                if (!in_array($o->getAffaireOptionId(), $listeOptionsOK)):
                    $this->managerAffaireOptions->delete($o);
                endif;
            endforeach;

        endif;
    }

    /**
     * Enregistre la nouvelle association d'un client à une affaire.
     * @param Affaire $affaire
     */
    private function enregistrementClientsConcepteur(Affaire $affaire) {

        $this->managerAffaireClients->resetAffaire($affaire);

        foreach ($this->session->userdata('affaireClients') as $newC):

            $new = new AffaireClient(array('affaireClientAffaireId' => $affaire->getAffaireId(), 'affaireClientClientId' => $newC->clientId, 'affaireClientPrincipal' => $newC->principal));
            $this->managerAffaireClients->ajouter($new);
            unset($new);

        endforeach;
    }

    /**
     * Génère un devis pour l'affaire en cours.
     */
    public function genererDevis() {

        if ($this->form_validation->run('getAffaire')):

            $lastDevis = $this->managerAffaires->getLastDevis()->affaireDevisId;
            if ($lastDevis):
                $nextDevisId = $lastDevis + 1;
            else:
                $nextDevisId = 1;
            endif;
            $affaire = $this->managerAffaires->getAffaireById($this->input->post('affaireId'));
            $affaire->setAffaireDevisId($nextDevisId);
            $affaire->setAffaireDevisDate(time());
            $this->managerAffaires->editer($affaire);

            echo json_encode(array('type' => 'success'));
            exit;

        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    /**
     * Génère une commande pour l'affaire en cours
     */
    public function genererCommande() {

        if ($this->form_validation->run('getAffaire')):

            $lastCommande = $this->managerAffaires->getLastCommande()->affaireCommandeId;
            if ($lastCommande):
                $nextCommandeId = $lastCommande + 1;
            else:
                $nextCommandeId = 1;
            endif;
            $affaire = $this->managerAffaires->getAffaireById($this->input->post('affaireId'));
            $affaire->setAffaireCommandeId($nextCommandeId);
            $affaire->setAffaireCommandeDate(time());
            $this->managerAffaires->editer($affaire);

            echo json_encode(array('type' => 'success'));
            exit;

        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    /**
     * Modifie la date du devis ou du bon de commande d'une affaire.
     */
    public function modifierDevis() {
        $affaire = $this->managerAffaires->getAffaireById($this->input->post('affaireId'));
        $affaire->setAffaireDevisDate($this->xth->mktimeFromInputDate($this->input->post('modDevisDate')));
        $affaire->setAffaireDevisTauxAcompte($this->input->post('modDevisAcompte'));
        $affaire->setAffaireDevisEnvoye($this->input->post('modDevisEnvoye') ? 1 : 0);

        $this->managerAffaires->editer($affaire);
        redirect('ventes/concepteur');
        exit;
    }

    /**
     * Modifie la date du devis ou du bon de commande d'une affaire.
     */
    public function modifierCommande() {
        $affaire = $this->managerAffaires->getAffaireById($this->input->post('affaireId'));

        $affaire->setAffaireCommandeDate($this->xth->mktimeFromInputDate($this->input->post('modCommandeDate')));
        $affaire->setAffaireCommandeCertifiee($this->input->post('modCommandeCertifiee') ?: 0 );
        $this->managerAffaires->editer($affaire);
        redirect('ventes/concepteur');
        exit;
    }

    public function dupliquerAffaire($affaireId = null) {
        if (!$affaireId || !$this->existAffaire($affaireId)):
            redirect('ventes/listeAffaires');
            exit;
        endif;
        $affaire = $this->managerAffaires->getAffaireById($affaireId);

        $affaireClone = clone $affaire;
        $affaireClone->setAffaireDate(time());
        $affaireClone->setAffaireDevisId(NULL);
        $affaireClone->setAffaireCommandeId(NULL);
        $affaireClone->setAffaireCloture(0);

        $this->db->trans_start();

        $this->managerAffaires->ajouter($affaireClone);
        foreach ($this->managerAffaireClients->getClientsByAffaireId($affaire->getAffaireId()) as $client):
            $dataAffaireClient = array(
                'affaireClientAffaireId' => $affaireClone->getAffaireId(),
                'affaireClientClientId' => $client->getClientId(),
                'affaireClientPrincipal' => $client->getClientPrincipal()
            );
            $this->managerAffaireClients->ajouter(new AffaireClient($dataAffaireClient));
        endforeach;

        /* Clone des lignes */
        $affaireArticles = $this->managerAffaireArticles->getAffaireArticlesByAffaireId($affaire->getAffaireId());
        if ($affaireArticles):
            foreach ($affaireArticles as $a):
                $articleClone = clone $a;
                $articleClone->setAffaireArticleAffaireId($affaireClone->getAffaireId());
                $this->managerAffaireArticles->ajouter($articleClone);

                $a->hydrateAffaireOptions();
                foreach ($a->getAffaireArticleOptions() as $option):
                    $optionClone = clone $option;
                    $optionClone->setAffaireOptionAffaireId($affaireClone->getAffaireId());
                    $optionClone->setAffaireOptionArticleId($articleClone->getAffaireArticleId());
                    $this->managerAffaireOptions->ajouter($optionClone);
                    unset($optionClone);
                endforeach;
                unset($articleClone);
            endforeach;
        endif;

        $this->db->trans_complete();

        redirect('ventes/reloadAffaire/' . $affaireClone->getAffaireId());
        exit;
    }

    public function cloturerAffaire($affaireId = null) {
        if ($affaireId && $this->existAffaire($affaireId)) {
            $affaire = $this->managerAffaires->getAffaireById($affaireId);
            $affaire->setAffaireCloture(abs($affaire->getAffaireCloture() - 1));
            $this->managerAffaires->editer($affaire);
        }
        redirect('ventes/listeAffaires');
        exit;
    }

}
