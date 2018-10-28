<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Chantiers extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(53, 54)))) :
            redirect('organibat/board');
        endif;
    }

    public function ficheChantier($chantierId = null, $action = null) {
        if (!$this->ion_auth->in_group(array(53, 54))):
            redirect('affaires/liste');
        endif;

        if (!$chantierId || !$this->existChantier($chantierId)):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Chantier introuvable');
            redirect('affaires/liste');
        endif;

        $chantier = $this->managerChantiers->getChantierById($chantierId);
        $chantier->hydratePlace();
        $chantier->hydrateAchats();
        if (!empty($chantier->getChantierAchats())):
            foreach ($chantier->getChantierAchats()as $achat):
                $achat->hydrateFournisseur();
            endforeach;
        endif;
        $chantier->hydrateClient(); /* hydrate aussi l'affaire */
        $chantier->getChantierClient()->hydratePlaces();

        $data = array(
            'fournisseurs' => $this->managerFournisseurs->getFournisseurs(),
            'categories' => $this->managerCategories->getCategories(),
            'chantier' => $chantier,
            'affaire' => $chantier->getChantierAffaire(),
            'title' => 'Fiche Chantier',
            'description' => 'Fiche chantier',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );

        if ($action):
            $actionObjet = substr($action, 0, 1);
            $actionRef = substr($action, 1);
            switch ($actionObjet):
                case 'a':
                    if ($this->ion_auth->in_group(array(55))):
                        /* Selection d'un achat à modifier dans la fiche chantier */
                        $data['achat'] = $this->managerAchats->getAchatById($actionRef);
                    endif;
                    break;
            endswitch;
        endif;

        $this->load->view('template/content', $data);
    }

    public function addChantier() {

        if (!$this->ion_auth->in_group(54)):
            redirect('affaires/ficheAffaire/' . $this->input->post('addChantierAffaireId'));
            exit;
        endif;

        if (!$this->form_validation->run('addChantier')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else:

            if ($this->input->post('addChantierId')):
                $chantier = $this->managerChantiers->getChantierById($this->input->post('addChantierId'));
                $chantier->setChantierPlaceId($this->input->post('addChantierPlaceId') ?: null);
                $chantier->setChantierCategorieId($this->input->post('addChantierCategorieId') ?: null);
                $chantier->setChantierObjet(ucfirst($this->input->post('addChantierObjet')));
                $chantier->setChantierPrix($this->input->post('addChantierPrix'));
                $chantier->setChantierCouleur($this->input->post('addChantierCouleur'));
                $chantier->setChantierCouleurSecondaire($this->couleurSecondaire($this->input->post('addChantierCouleur')));
                $chantier->setChantierRemarque($this->input->post('addChantierRemarque'));
                $chantier->setChantierHeuresPrevues($this->input->post('addChantierHeuresPrevues'));
                $chantier->setChantierBudgetAchats($this->input->post('addChantierBudgetAchats'));
                $chantier->setChantierFraisGeneraux($this->input->post('addChantierFraisGeneraux'));
                $chantier->setChantierTauxHoraireMoyen($this->input->post('addChantierTauxHoraireMoyen'));
                $this->managerChantiers->editer($chantier);
            /* La mise à jour de l'etat de l'affaire se fait par les declencheurs MYSQL */

            else:

                $dataChantier = array(
                    'chantierAffaireId' => $this->input->post('addChantierAffaireId'),
                    'chantierPlaceId' => $this->input->post('addChantierPlaceId') ?: null,
                    'chantierCategorieId' => $this->input->post('addChantierCategorieId') ?: null,
                    'chantierObjet' => ucfirst($this->input->post('addChantierObjet')),
                    'chantierPrix' => $this->input->post('addChantierPrix'),
                    'chantierCouleur' => $this->input->post('addChantierCouleur'),
                    'chantierCouleurSecondaire' => $this->couleurSecondaire($this->input->post('addChantierCouleur')),
                    'chantierRemarque' => $this->input->post('addChantierRemarque'),
                    'chantierEtat' => 1,
                    'chantierDateCloture' => null,
                    'chantierHeuresPrevues' => $this->input->post('addChantierHeuresPrevues'),
                    'chantierBudgetAchats' => $this->input->post('addChantierBudgetAchats'),
                    'chantierTauxHoraireMoyen' => $this->input->post('addChantierTauxHoraireMoyen'),
                    'chantierFraisGeneraux' => $this->input->post('addChantierFraisGeneraux')
                );
                $chantier = new Chantier($dataChantier);
                $this->managerChantiers->ajouter($chantier);
            /* La mise à jour de l'etat de l'affaire se fait par les declencheurs MYSQL */
            endif;

            echo json_encode(array('type' => 'success', 'chantierId' => $chantier->getChantierId()));
        endif;
    }

    public function clotureChantier() {
        if (!$this->ion_auth->in_group(54)):
            echo json_encode(array('type' => 'error', 'message' => 'Vous n\'avez pas les droits pour clôturer un chantier'));
        elseif (!$this->form_validation->run('getChantier')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            /* Cloture du chantier */
            $chantier = $this->managerChantiers->getChantierById($this->input->post('chantierId'));
            $chantier->setChantierEtat(2);
            $chantier->setChantierDateCloture(time());

            $this->managerChantiers->editer($chantier);
            /* La mise à jour de l'etat de l'affaire se fait par les declencheurs MYSQL */
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function reouvertureChantier() {
        if (!$this->ion_auth->in_group(54)):
            echo json_encode(array('type' => 'error', 'message' => 'Vous n\'avez pas les droits pour clôturer un chantier'));
        elseif (!$this->form_validation->run('getChantier')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            /* Cloture du chantier */
            $chantier = $this->managerChantiers->getChantierById($this->input->post('chantierId'));
            $chantier->setChantierEtat(1);
            $this->managerChantiers->editer($chantier);
            /* La mise à jour de l'etat de l'affaire se fait par les declencheurs MYSQL */
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function delChantier() {

        if (!$this->ion_auth->in_group(54) || !$this->form_validation->run('getChantier')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $chantier = $this->managerChantiers->getChantierById($this->input->post('chantierId'));
            $this->managerChantiers->delete($chantier);
            /* La mise à jour de l'etat de l'affaire se fait par les declencheurs MYSQL */

            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function addAchat() {

        if (!$this->ion_auth->in_group(55)):
            redirect('chantiers/ficheChantier/' . $this->input->post('addAchatChantierId'));
            exit;
        endif;

        if (!$this->form_validation->run('addAchat')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addAchatId')):
                $achat = $this->managerAchats->getAchatById($this->input->post('addAchatId'));
                $achat->setAchatDate($this->own->mktimeFromInputDate($this->input->post('addAchatDate')));
                $achat->setAchatDescription($this->input->post('addAchatDescription'));
                $achat->setAchatType($this->input->post('addAchatType'));
                $achat->setAchatQte($this->input->post('addAchatQte'));
                $achat->setAchatQtePrevisionnel($this->input->post('addAchatQtePrevisionnel'));
                $achat->setAchatPrix($this->input->post('addAchatPrix'));
                $achat->setAchatPrixPrevisionnel($this->input->post('addAchatPrixPrevisionnel'));
                $achat->setAchatLivraisonDate($this->input->post('addAchatLivraisonDate') ? $this->own->mktimeFromInputDate($this->input->post('addAchatLivraisonDate')) : null);
                $achat->setAchatLivraisonAvancement($this->input->post('addAchatLivraisonAvancement') ?: null );
                $achat->setAchatFournisseurId($this->input->post('addAchatFournisseurId') ?: null );
                $this->managerAchats->editer($achat);
            else:

                $dataAchat = array(
                    'achatChantierId' => $this->input->post('addAchatChantierId'),
                    'achatLivraisonOriginId' => null,
                    'achatDate' => $this->own->mktimeFromInputDate($this->input->post('addAchatDate')),
                    'achatDescription' => $this->input->post('addAchatDescription'),
                    'achatType' => $this->input->post('addAchatType'),
                    'achatQte' => $this->input->post('addAchatQte'),
                    'achatQtePrevisionnel' => $this->input->post('addAchatQtePrevisionnel'),
                    'achatprix' => $this->input->post('addAchatPrix'),
                    'achatPrixPrevisionnel' => $this->input->post('addAchatPrixPrevisionnel'),
                    'achatLivraisonDate' => $this->input->post('addAchatLivraisonDate') ? $this->own->mktimeFromInputDate($this->input->post('addAchatLivraisonDate')) : null,
                    'achatLivraisonAvancement' => $this->input->post('addAchatLivraisonAvancement') ?: null,
                    'achatFournisseurId' => $this->input->post('addAchatFournisseurId') ?: null
                );
                $achat = new Achat($dataAchat);
                $this->managerAchats->ajouter($achat);

            endif;
            $achat->genereHTML();
            echo json_encode(array('type' => 'success', 'achatHTML' => $achat->getAchatHTML()));

        endif;
    }

    public function delAchat() {

        if (!$this->ion_auth->in_group(55)):
            redirect('chantiers/ficheChantier/' . $this->input->post('addAchatChantierId'));
            exit;
        endif;

        if (!$this->form_validation->run('getAchat')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $achat = $this->managerAchats->getAchatById($this->input->post('achatId'));
            $this->managerAchats->delete($achat);
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function listeAchatsChantier() {
        if (!$this->form_validation->run('getChantier')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $achats = $this->managerAchats->getAchats($this->input->post('chantierId'), array(), 'achatLivraisonDate DESC', 'array');
            if (!empty($achats)):
                foreach ($achats as $achat):
                    $achat->achatLivraisonDate = $this->cal->dateFrancais($achat->achatLivraisonDate);
                endforeach;
            endif;
            echo json_encode(array('type' => 'success', 'achats' => $achats));
        endif;
    }

}
