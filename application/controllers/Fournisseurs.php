<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Fournisseurs extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(61, 70)))) :
            redirect('organibat/board');
        endif;
    }

    public function listeFst() {

        $fournisseurs = $this->managerFournisseurs->getFournisseurs();
        $data = array(
            'fournisseurs' => $fournisseurs,
            'title' => 'Liste des fournisseurs',
            'description' => 'Liste des fournisseurs',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheFournisseur($fournisseurId = null) {
        if (!$this->ion_auth->in_group(array(61))):
            redirect('fournisseurs/liste');
        endif;

        if (!$fournisseurId || !$this->existFournisseur($fournisseurId)):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Fournisseur introuvable');
            redirect('fournisseurs/listeFst');
        endif;

        $fournisseur = $this->managerFournisseurs->getFournisseurById($fournisseurId);
        $fournisseur->hydrateAchats();

        $data = array(
            'fournisseur' => $fournisseur,
            'title' => $fournisseur->getFournisseurNom(),
            'description' => 'Fiche du fournisseur ' . $fournisseur->getFournisseurNom(),
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addFournisseur() {

        if (!$this->ion_auth->in_group(70)):
            redirect('fournisseurs/listeFst/');
            exit;
        endif;

        if ($this->form_validation->run('addFournisseur')):

            if ($this->input->post('addFournisseurId')):
                $fournisseur = $this->managerFournisseurs->getFournisseurById($this->input->post('addFournisseurId'));
                $fournisseur->setFournisseurNom(strtoupper($this->input->post('addFournisseurNom')));
                $fournisseur->setFournisseurAdresse($this->input->post('addFournisseurAdresse'));
                $fournisseur->setFournisseurCp($this->input->post('addFournisseurCp'));
                $fournisseur->setFournisseurVille($this->input->post('addFournisseurVille'));
                $fournisseur->setFournisseurTelephone($this->input->post('addFournisseurTelephone'));
                $fournisseur->setFournisseurEmail($this->input->post('addFournisseurEmail'));
                $this->managerFournisseurs->editer($fournisseur);

            else:

                $dataFournisseur = array(
                    'fournisseurOriginId' => null,
                    'fournisseurEtablissementId' => $this->session->userdata('etablissementId'),
                    'fournisseurNom' => strtoupper($this->input->post('addFournisseurNom')),
                    'fournisseurAdresse' => $this->input->post('addFournisseurAdresse'),
                    'fournisseurCp' => $this->input->post('addFournisseurCp'),
                    'fournisseurVille' => $this->input->post('addFournisseurVille'),
                    'fournisseurTelephone' => $this->input->post('addFournisseurTelephone'),
                    'fournisseurEmail' => $this->input->post('addFournisseurEmail')
                );
                $fournisseur = new Fournisseur($dataFournisseur);
                $this->managerFournisseurs->ajouter($fournisseur);

            endif;

            echo json_encode(array('type' => 'success', 'fournisseurId' => $fournisseur->getFournisseurId()));

        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
    }

    public function delFournisseur() {

        if (!$this->ion_auth->in_group(54) || !$this->form_validation->run('getFournisseur')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $fournisseur = $this->managerFournisseurs->getFournisseurById($this->input->post('chantierId'));
            $this->managerFournisseurs->delete($fournisseur);
            /* La mise à jour de l'etat de l'affaire se fait par les declencheurs MYSQL */

            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function addAchat() {

        if (!$this->ion_auth->in_group(55)):
            redirect('fournisseurs/ficheFournisseur/' . $this->input->post('addAchatFournisseurId'));
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
                    'achatFournisseurId' => $this->input->post('addAchatFournisseurId'),
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
            redirect('fournisseurs/ficheFournisseur/' . $this->input->post('addAchatFournisseurId'));
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
