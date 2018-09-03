<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Secure extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__);

        if ($this->ion_auth->logged_in()) :
            redirect('organibat/board');
            exit;
        endif;
    }

    /**
     * page de login
     */
    public function login() {
        $data = array(
            'map_enable' => '',
            'title' => 'Connexion à la console.',
            'description' => 'Saississez vos identifiants pour accèder à la console.',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function tryLogin() {
        if (!$this->form_validation->run('identification')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            /* On teste la demande de connexion */
            if ($this->ion_auth->login($this->input->post('login'), $this->input->post('pass'), 0)) :

                $user = $this->managerUtilisateurs->getUtilisateurById($this->session->userdata('user_id'));
                foreach ($this->ion_auth->get_users_groups($user->getId())->result() as $group):
                    $groups[] = $group->id;
                endforeach;
                $etablissement = $this->managerEtablissements->getEtablissementById($user->getUserEtablissementId());
                $this->session->set_userdata(
                        array(
                            'utilisateurPrenom' => $user->getUserPrenom(),
                            'utilisateurNom' => $user->getUserNom(),
                            'rsId' => $etablissement->getEtablissementRsId(),
                            'etablissementId' => $user->getUserEtablissementId(),
                            'etablissementGPS' => $etablissement->getEtablissementGps(),
                            'etablissementTFG' => $etablissement->getEtablissementTauxFraisGeneraux(),
                            'etablissementTHM' => $etablissement->getEtablissementTauxHoraireMoyen(),
                            'droits' => $groups,
                            'rechAffaireEtat' => 2
                        )
                );
                $this->session->set_userdata('parametres', (array) $this->managerParametres->getParametres('array'));

                echo json_encode(array('type' => 'success'));
            else :
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' MAUVAIS ID DE CONNEXION');
                echo json_encode(array('type' => 'error', 'message' => 'Identifiants de connexion invalides.'));
            endif;
        endif;
    }

}
