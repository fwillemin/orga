<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Utilisateurs extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(10, 11)))) :
            redirect('organibat/board');
        endif;
    }

    public function index() {
        redirect('personnels/liste');
    }

    public function liste() {

        $utilisateurs = $this->managerUtilisateurs->getUtilisateurs();
        foreach ($utilisateurs as $utilisateur):
            $utilisateur->hydrateGroups();
            foreach ($utilisateur->getUserGroups() as $groupe):
                switch ($groupe->id):
                    case 1:
                        $utilisateur->setUserType('Direction');
                        break;
                    case 2:
                        $utilisateur->setUserType('Personnel administratif');
                        break;
                    case 4:
                        $utilisateur->setUserType('Accès chantier');
                        break;
                    case 9:
                        $utilisateur->setUserType('Inactif');
                endswitch;
            endforeach;
        endforeach;

        $data = array(
            'utilisateurs' => $utilisateurs,
            'title' => 'Utilisateurs',
            'description' => 'Liste des utilisateurs',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheUtilisateur($userId = null) {

        if (!$this->ion_auth->in_group(11)):
            redirect('utilisateurs/liste');
        endif;

        if (!$userId || !$this->existUtilisateur($userId)):
            redirect('utilisateurs/liste');
        endif;

        $utilisateur = $this->managerUtilisateurs->getUtilisateurById($userId);
        $utilisateur->hydrateGroups();

        $data = array(
            'utilisateur' => $utilisateur,
            //'listeGroupes' => $this->db->select('*')->from('groups')->where('id > ', 9)->get()->result(),
            'title' => 'Fiche ' . $utilisateur->getUserNom(),
            'description' => 'Fiche utilisateur',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addUtilisateur() {

        if (!$this->form_validation->run('addUtilisateur')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addUserId')):
                $utilisateur = $this->managerUtilisateurs->getUtilisateurById($this->input->post('addUserId'));
                $utilisateur->setUserNom(strtoupper($this->input->post('addUserNom')));
                $utilisateur->setUserPrenom(ucfirst($this->input->post('addUserPrenom')));
                $utilisateur->setEmail($this->input->post('addUserEmail'));
                $this->managerUtilisateurs->editer($utilisateur);

                if ($this->input->post('addUserPassword')):
                    /* Modification du mot de passe */
                    $this->ion_auth_model->reset_password($utilisateur->getUsername(), $this->input->post('addUserPassword'));
                    $utilisateur->setUserClairMdp($this->input->post('addUserPassword'));
                    $this->managerUtilisateurs->editer($utilisateur);
                endif;

            else:

                if (!$this->input->post('addUserPassword')):
                    echo json_encode(array('type' => 'error', 'message' => 'Vous devez choisir un mot de passe'));
                    exit;
                endif;

                $additional_data = array(
                    'userNom' => strtoupper($this->input->post('addUserNom')),
                    'userPrenom' => ucfirst($this->input->post('addUserPrenom')),
                    'userEtablissementId' => $this->session->userdata('etablissementId'),
                    'userOriginId' => null,
                    'userClairMdp' => $this->input->post('addUserPassword'),
                    'userCode' => 0000
                );

                $this->ion_auth->register($this->input->post('addUserEmail'), $this->input->post('addUserPassword'), $this->input->post('addUserEmail'), $additional_data, array('2'));

            endif;
            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function modifierAcces() {
        if (!$this->form_validation->run('modAcces')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;

        if (in_array($this->input->post('groupeId'), [1, 2, 4, 9])):
            $this->db->where(array('group_id' => 2, 'user_id' => $this->input->post('userId')))->delete('users_groups');
            $this->db->where(array('group_id' => 4, 'user_id' => $this->input->post('userId')))->delete('users_groups');
            $this->db->where(array('group_id' => 1, 'user_id' => $this->input->post('userId')))->delete('users_groups');
            $this->db->where(array('group_id' => 9, 'user_id' => $this->input->post('userId')))->delete('users_groups');
            $this->db->set('user_id', $this->input->post('userId'))->set('group_id', $this->input->post('groupeId'))->insert('users_groups');

            switch ($this->input->post('groupeId')):
                case 4:
                case 9:
                    $this->db->where(array('group_id <>' => $this->input->post('groupeId'), 'user_id' => $this->input->post('userId')))->delete('users_groups');
                    break;
            endswitch;

        else:
            if ($this->input->post('acces') == '0'):
                $this->db->where(array('group_id' => $this->input->post('groupeId'), 'user_id' => $this->input->post('userId')))->delete('users_groups');
            else:
                $this->db->set('user_id', $this->input->post('userId'))->set('group_id', $this->input->post('groupeId'))->insert('users_groups');
            endif;
        endif;
        echo json_encode(array('type' => 'success'));
    }

}
