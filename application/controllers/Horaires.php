<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Horaires extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(20, 21)))) :
            redirect('organibat/board');
        endif;
    }

    public function liste() {

        $horaires = $this->managerHoraires->getHoraires();

        $data = array(
            'horaires' => $horaires,
            'title' => 'Horaires',
            'description' => 'Liste des horaires de travail de l\'entreprise',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheHoraire($horaireId = null) {


        if (!$this->ion_auth->in_group(21)):
            redirect('horaires/liste');
        endif;

        if (!$horaireId || !$this->existHoraire($horaireId)):
            redirect('horaires/liste');
        endif;

        $horaire = $this->managerHoraires->getHoraireById($horaireId);

        $data = array(
            'horaire' => $horaire,
            'title' => 'Horaire ' . $horaire->getHoraireNom(),
            'description' => 'Fiche horaire',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addHoraire() {

        if (!$this->form_validation->run('addHoraire')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addHoraireId')):
                $horaire = $this->managerHoraires->getHoraireById($this->input->post('addHoraireId'));
                $horaire->setHoraireNom($this->input->post('addHoraireNom'));
                $horaire->setHoraireLun1($this->input->post('addHoraireLun1'));
                $horaire->setHoraireLun2($this->input->post('addHoraireLun2'));
                $horaire->setHoraireLun3($this->input->post('addHoraireLun3'));
                $horaire->setHoraireLun4($this->input->post('addHoraireLun4'));
                $horaire->setHoraireMar1($this->input->post('addHoraireMar1'));
                $horaire->setHoraireMar2($this->input->post('addHoraireMar2'));
                $horaire->setHoraireMar3($this->input->post('addHoraireMar3'));
                $horaire->setHoraireMar4($this->input->post('addHoraireMar4'));
                $horaire->setHoraireMer1($this->input->post('addHoraireMer1'));
                $horaire->setHoraireMer2($this->input->post('addHoraireMer2'));
                $horaire->setHoraireMer3($this->input->post('addHoraireMer3'));
                $horaire->setHoraireMer4($this->input->post('addHoraireMer4'));
                $horaire->setHoraireJeu1($this->input->post('addHoraireJeu1'));
                $horaire->setHoraireJeu2($this->input->post('addHoraireJeu2'));
                $horaire->setHoraireJeu3($this->input->post('addHoraireJeu3'));
                $horaire->setHoraireJeu4($this->input->post('addHoraireJeu4'));
                $horaire->setHoraireVen1($this->input->post('addHoraireVen1'));
                $horaire->setHoraireVen2($this->input->post('addHoraireVen2'));
                $horaire->setHoraireVen3($this->input->post('addHoraireVen3'));
                $horaire->setHoraireVen4($this->input->post('addHoraireVen4'));
                $horaire->setHoraireSam1($this->input->post('addHoraireSam1'));
                $horaire->setHoraireSam2($this->input->post('addHoraireSam2'));
                $horaire->setHoraireSam3($this->input->post('addHoraireSam3'));
                $horaire->setHoraireSam4($this->input->post('addHoraireSam4'));
                $horaire->setHoraireDim1($this->input->post('addHoraireDim1'));
                $horaire->setHoraireDim2($this->input->post('addHoraireDim2'));
                $horaire->setHoraireDim3($this->input->post('addHoraireDim3'));
                $horaire->setHoraireDim4($this->input->post('addHoraireDim4'));
                $this->managerHoraires->editer($horaire);

            else:

                $dataHoraire = array(
                    'horaireNom' => $this->input->post('addHoraireNom'),
                    'horaireLun1' => $this->input->post('addHoraireLun1'),
                    'horaireLun2' => $this->input->post('addHoraireLun2'),
                    'horaireLun3' => $this->input->post('addHoraireLun3'),
                    'horaireLun4' => $this->input->post('addHoraireLun4'),
                    'horaireMar1' => $this->input->post('addHoraireMar1'),
                    'horaireMar2' => $this->input->post('addHoraireMar2'),
                    'horaireMar3' => $this->input->post('addHoraireMar3'),
                    'horaireMar4' => $this->input->post('addHoraireMar4'),
                    'horaireMer1' => $this->input->post('addHoraireMer1'),
                    'horaireMer2' => $this->input->post('addHoraireMer2'),
                    'horaireMer3' => $this->input->post('addHoraireMer3'),
                    'horaireMer4' => $this->input->post('addHoraireMer4'),
                    'horaireJeu1' => $this->input->post('addHoraireJeu1'),
                    'horaireJeu2' => $this->input->post('addHoraireJeu2'),
                    'horaireJeu3' => $this->input->post('addHoraireJeu3'),
                    'horaireJeu4' => $this->input->post('addHoraireJeu4'),
                    'horaireVen1' => $this->input->post('addHoraireVen1'),
                    'horaireVen2' => $this->input->post('addHoraireVen2'),
                    'horaireVen3' => $this->input->post('addHoraireVen3'),
                    'horaireVen4' => $this->input->post('addHoraireVen4'),
                    'horaireSam1' => $this->input->post('addHoraireSam1'),
                    'horaireSam2' => $this->input->post('addHoraireSam2'),
                    'horaireSam3' => $this->input->post('addHoraireSam3'),
                    'horaireSam4' => $this->input->post('addHoraireSam4'),
                    'horaireDim1' => $this->input->post('addHoraireDim1'),
                    'horaireDim2' => $this->input->post('addHoraireDim2'),
                    'horaireDim3' => $this->input->post('addHoraireDim3'),
                    'horaireDim4' => $this->input->post('addHoraireDim4'),
                );
                $horaire = new Horaire($dataHoraire);
                $this->managerHoraires->ajouter($horaire);

            endif;

            echo json_encode(array('type' => 'success'));

        endif;
    }

}
