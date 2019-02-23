<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Secure extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__);

        if ($this->ion_auth->logged_in()) :
            redirect('planning/base');
            exit;
        endif;
    }

    /**
     * page de login
     */
    public function login() {
        $data = array(
            'type' => 'website',
            'url' => site_url('acces-client'),
            'image' => '',
            'title' => 'Connexion à la console.',
            'description' => 'Saississez vos identifiants pour accèder à la console.',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/contentShowroom', $data);
    }

    public function tryLogin() {
        if (!$this->form_validation->run('identification')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else :

            /* On teste la demande de connexion */
            if ($this->ion_auth->login($this->input->post('login'), $this->input->post('pass'), 0)) :

                $user = $this->managerUtilisateurs->getUtilisateurById($this->session->userdata('user_id'));
                foreach ($this->ion_auth->get_users_groups($user->getId())->result() as $group):
                    $groups[] = $group->id;
                endforeach;
                $etablissement = $this->managerEtablissements->getEtablissementById($user->getUserEtablissementId());
                $etablissement->hydrateRs();

                if (date('m') < $etablissement->getEtablissementRs()->getRsMoisFiscal()):
                    $annee = date('Y') - 1;
                else:
                    $annee = date('Y');
                endif;

                $this->session->set_userdata(
                        array(
                            'loaderIcon' => 'fa-repeat',
                            'utilisateurPrenom' => $user->getUserPrenom(),
                            'utilisateurNom' => $user->getUserNom(),
                            'rsId' => $etablissement->getEtablissementRsId(),
                            'moisFiscal' => $etablissement->getEtablissementRs()->getRsMoisFiscal(),
                            'debutFiscale' => $this->cal->debutFinExercice($etablissement->getEtablissementRs()->getRsMoisFiscal(), $annee, 'debut'),
                            'finFiscale' => $this->cal->debutFinExercice($etablissement->getEtablissementRs()->getRsMoisFiscal(), $annee, 'fin'),
                            'debutFiscaleN' => $this->cal->debutFinExercice($etablissement->getEtablissementRs()->getRsMoisFiscal(), ($annee - 1), 'debut'),
                            'finFiscaleN' => $this->cal->debutFinExercice($etablissement->getEtablissementRs()->getRsMoisFiscal(), ($annee - 1), 'fin'),
                            'etablissementId' => $user->getUserEtablissementId(),
                            'etablissementGPS' => $etablissement->getEtablissementGps(),
                            'etablissementTFG' => $etablissement->getEtablissementTauxFraisGeneraux(),
                            'etablissementTHM' => $etablissement->getEtablissementTauxHoraireMoyen(),
                            'etablissementBaseHebdomadaire' => $etablissement->getEtablissementBaseHebdomadaire(),
                            'affaireDiversId' => $etablissement->getEtablissementAffaireDiversId(),
                            'droits' => $groups,
                            'smsCredits' => 0,
                            'rechAffaireEtat' => 2,
                            'analysePersonnelsAnnee' => date('Y'),
                            'analyseAnnee' => $annee
                        )
                );
                $this->session->set_userdata('parametres', (array) $this->managerParametres->getParametres('array'));
                echo json_encode(array('type' => 'success'));
            else :
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' MAUVAIS ID DE CONNEXION : ' . $this->input->post('login') . ' | ' . $this->input->post('pass'));
                echo json_encode(array('type' => 'error', 'message' => 'Identifiants de connexion invalides.'));
            endif;

        endif;
    }

}
