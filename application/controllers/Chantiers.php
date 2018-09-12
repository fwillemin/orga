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
        $chantier->hydrateClient(); /* hydrate aussi l'affaire */
        $chantier->getChantierClient()->hydratePlaces();

        $data = array(
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

                $affaire = $this->managerAffaires->getAffaireById($chantier->getChantierAffaireId());
                if ($affaire->getAffaireEtat() == 1):
                    $affaire->setAffaireEtat(2);
                    $this->managerAffaires->editer($affaire);
                endif;

            endif;

            echo json_encode(array('type' => 'success', 'chantierId' => $chantier->getChantierId()));
        endif;
    }

    public function clotureChantier() {

    }

    public function delChantier() {

        if (!$this->ion_auth->in_group(54) || !$this->form_validation->run('getChantier')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $chantier = $this->managerChantiers->getChantierById($this->input->post('chantierId'));
            $this->managerChantiers->delete($chantier);

            $nbChantiersAffaireRestants = $this->managerChantiers->count(array('chantierAffaireId' => $chantier->getChantierAffaireId()));
            if ($nbChantiersAffaireRestants == 0):
                $affaire = $this->managerAffaires->getAffaireById($chantier->getChantierAffaireId());
                $affaire->setAffaireEtat(1);
                $this->managerAffaires->editer($affaire);
            endif;

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
                $this->managerAchats->editer($achat);
            else:

                $dataAchat = array(
                    'achatChantierId' => $this->input->post('addAchatChantierId'),
                    'achatDate' => $this->own->mktimeFromInputDate($this->input->post('addAchatDate')),
                    'achatDescription' => $this->input->post('addAchatDescription'),
                    'achatType' => $this->input->post('addAchatType'),
                    'achatQte' => $this->input->post('addAchatQte'),
                    'achatQtePrevisionnel' => $this->input->post('addAchatQtePrevisionnel'),
                    'achatprix' => $this->input->post('addAchatPrix'),
                    'achatPrixPrevisionnel' => $this->input->post('addAchatPrixPrevisionnel')
                );
                $achat = new Achat($dataAchat);
                $this->managerAchats->ajouter($achat);
            endif;
            echo json_encode(array('type' => 'success'));

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

}
