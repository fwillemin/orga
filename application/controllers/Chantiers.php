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

    public function ficheChantier($chantierId = null) {
        if (!$this->ion_auth->in_group(array(53, 54))):
            redirect('affaires/liste');
        endif;

        if (!$chantierId || !$this->existChantier($chantierId)):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Chantier introuvable');
            redirect('affaires/liste');
        endif;

        $chantier = $this->managerChantiers->getChantierById($chantierId);
        $chantier->hydratePlace();
        $chantier->hydrateClient(); /* hydrate aussi l'affaire */
        $chantier->getChantierClient()->hydratePlaces();

        log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($chantier, true));

        $data = array(
            'categories' => $this->managerCategories->getCategories(),
            'chantier' => $chantier,
            'affaire' => $chantier->getChantierAffaire(),
            'title' => 'Fiche Chantier',
            'description' => 'Fiche chantier',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
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
                $chantier->setChantierAffaireId($this->input->post('addChantierAffaireId'));
                $chantier->setChantierPlaceId($this->input->post('addChantierPlaceId') ?: null);
                $chantier->setChantierCategorieId($this->input->post('addChantierCategorieId') ?: null);
                $chantier->setChantierObjet(ucfirst($this->input->post('addChantierObjet')));
                $chantier->setChantierPrix($this->input->post('addChantierPrix'));
                $chantier->setChantierCouleur($this->input->post('addChantierCouleur'));
                $chantier->setChantierCouleurSecondaire($this->couleurSecondaire($this->input->post('addChantierCouleur')));
                $chantier->setChantierRemarque($this->input->post('addChantierRemarque'));
                $chantier->setChantierHeuresPrevues($this->input->post('addChantierHeuresPrevues'));
                $chantier->setChantierBudgetAchats($this->input->post('addChantierBudgetAchats'));
                $chantier->setChantierBudgetFraisGeneraux($this->input->post('addChantierFraisGeneraux'));
                $chantier->setChantierBudgetTauxHoraireMoyen($this->input->post('addChantierTauxHoraireMoyen'));
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

            endif;

            echo json_encode(array('type' => 'success', 'chantierId' => $chantier->getChantierId()));
        endif;
    }

    public function clotureChantier() {

    }

}
