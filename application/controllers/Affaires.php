<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Affaires extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(50, 51, 52)))) :
            redirect('organibat/board');
        endif;
    }

    public function liste() {

        $where = array('affaireId <>' => $this->session->userdata('affaireDiversId'));
        if ($this->session->userdata('rechAffaireEtat')):
            $where['affaireEtat'] = $this->session->userdata('rechAffaireEtat');
        endif;

        $affaires = $this->managerAffaires->getAffaires($where);
        if ($affaires):
            foreach ($affaires as $affaire):
                $affaire->hydrateClient();
            endforeach;
        endif;

        $data = array(
            'commerciaux' => $this->managerUtilisateurs->getCommerciaux(),
            'categories' => $this->managerCategories->getCategories(),
            'clients' => $this->managerClients->getClients(),
            'affaires' => $affaires,
            'title' => 'Affaires',
            'description' => 'Liste des affaires',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function rechAffaireEtat() {
        if (in_array($this->input->post('etat'), array(0, 1, 2, 3))):
            $this->session->set_userdata('rechAffaireEtat', $this->input->post('etat'));
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Etat invalide'));
        endif;
    }

    public function ficheAffaire($affaireId = null) {

        if (!$affaireId || !$this->existAffaire($affaireId)):
            redirect('affaires/liste');
        endif;

        $clients = $this->managerClients->getClients();


        $affaire = $this->managerAffaires->getAffaireById($affaireId);
        $affaire->hydrateClient();
        $affaire->getAffaireClient()->hydratePlaces();
        $affaire->hydrateCommercial();
        $affaire->hydrateChantiers();
        $affaire->hydratePlace();

        $data = array(
            'commerciaux' => $this->managerUtilisateurs->getCommerciaux(),
            'clients' => $clients,
            'categories' => $this->managerCategories->getCategories(),
            'affaire' => $affaire,
            'title' => 'Fiche Affaire',
            'description' => 'Fiche affaire',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addAffaire() {

        if (!$this->ion_auth->in_group(51)):
            redirect('affaires/liste');
        endif;

        if (!$this->form_validation->run('addAffaire')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addAffaireId')):
                $affaire = $this->managerAffaires->getAffaireById($this->input->post('addAffaireId'));
                $affaire->setAffaireCommercialId($this->input->post('addAffaireCommercialId') ?: null);
                $affaire->setAffairePlaceId($this->input->post('addAffairePlaceId') ?: null);
                $affaire->setAffaireClientId($this->input->post('addAffaireClientId'));
                $affaire->setAffaireCategorieId($this->input->post('addAffaireCategorieId') ?: null);
                $affaire->setAffaireDevis(strtoupper($this->input->post('addAffaireDevis')));
                $affaire->setAffaireObjet(ucfirst($this->input->post('addAffaireObjet')));
                $affaire->setAffairePrix($this->input->post('addAffairePrix'));
                $affaire->setAffaireDateSignature($this->input->post('addAffaireDateSignature') ? $this->own->mktimeFromInputDate($this->input->post('addAffaireDateSignature')) : null);
                $affaire->setAffaireCouleur($this->input->post('addAffaireCouleur'));
                $affaire->setAffaireCouleurSecondaire($this->couleurSecondaire($this->input->post('addAffaireCouleur')));
                $affaire->setAffaireRemarque($this->input->post('addAffaireRemarque'));
                $this->managerAffaires->editer($affaire);

            else:

                $dataAffaire = array(
                    'affaireEtablissementId' => $this->session->userdata('etablissementId'),
                    'affaireClientId' => $this->input->post('addAffaireClientId'),
                    'affairePlaceId' => $this->input->post('addAffairePlaceId'),
                    'affaireCommercialId' => $this->input->post('addAffaireCommercialId') ?: null,
                    'affaireCategorieId' => $this->input->post('addAffaireCategorieId') ?: null,
                    'affaireDevis' => strtoupper($this->input->post('addAffaireDevis')),
                    'affaireObjet' => ucfirst($this->input->post('addAffaireObjet')),
                    'affairePrix' => $this->input->post('addAffairePrix'),
                    'affaireDateSignature' => $this->input->post('addAffaireDateSignature') ? $this->own->mktimeFromInputDate($this->input->post('addAffaireDateSignature')) : null,
                    'affaireCouleur' => $this->input->post('addAffaireCouleur'),
                    'affaireCouleurSecondaire' => $this->couleurSecondaire($this->input->post('addAffaireCouleur')),
                    'affaireRemarque' => $this->input->post('addAffaireRemarque'),
                    'affaireEtat' => 1
                );
                $affaire = new Affaire($dataAffaire);
                $this->managerAffaires->ajouter($affaire);

            endif;

            echo json_encode(array('type' => 'success', 'affaireId' => $affaire->getAffaireId()));
        endif;
    }

    public function clotureAffaire() {

    }

    public function delAffaire() {
        if (!$this->ion_auth->in_group(57) || !$this->form_validation->run('getAffaire') || $this->input->post('affaireId') == $this->session->userdata('affaireDiversId')):
            echo json_encode(array('type' => 'error', 'message' => $this->messageDroitsInsuffisants));
        else:
            $affaire = $this->managerAffaires->getAffaireById($this->input->post('affaireId'));
            $this->managerAffaires->delete($affaire);
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function modAffaireDivers() {
        if (!$this->ion_auth->in_group(57)):
            $affaire = $this->managerAffaires->getAffaireById($this->session->userdata('affaireDiversId'));
            $affaire->setAffaireCouleur($this->input->post('couleur'));
            $affaire->setAffaireCouleurSecondaire($this->couleurSecondaire($this->input->post('couleur')));
            $this->managerAffaires->editer($affaire);

            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => $this->messageDroitsInsuffisants));
        endif;
    }

}
