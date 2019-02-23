<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Statistiques extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(1)))) :
            redirect('organibat/board');
        endif;

        $this->lang->load('calendar_lang', 'french');
        $this->grilleMensuelle = array();
        $this->labelsMois = '';
        $mois = date('m', $this->session->userdata('debutFiscale'));
        for ($i = 0; $i < 12; $i++):
            if (($mois + $i) <= 12):
                $index = $mois + $i;
            else:
                $index = $mois + $i - 12;
            endif;
            if ($this->labelsMois != ''):
                $this->labelsMois .= ',';
            endif;
            $this->grilleMensuelle[str_pad($index, 2, '0', STR_PAD_LEFT)] = 0;
            $this->labelsMois .= $this->lang->line('cal_' . strtolower(date('F', mktime(0, 0, 0, $index, 1, date('Y')))));
        endfor;

        $this->debutAnalyse = $this->cal->debutFinExercice($this->session->userdata('moisFiscal'), $this->session->userdata('analyseAnnee'), 'debut');
        $this->finAnalyse = $this->cal->debutFinExercice($this->session->userdata('moisFiscal'), $this->session->userdata('analyseAnnee'), 'fin');
        $this->debutAnalyseN = $this->cal->debutFinExercice($this->session->userdata('moisFiscal'), $this->session->userdata('analyseAnnee') - 1, 'debut');
        $this->finAnalyseN = $this->cal->debutFinExercice($this->session->userdata('moisFiscal'), $this->session->userdata('analyseAnnee') - 1, 'fin');
    }

    public function changeAnneeAnalyse() {
        $this->session->set_userdata('analyseAnnee', $this->input->post('annee'));
        echo json_encode(array('type' => 'success'));
    }

    public function index() {
        $data = array(
            'title' => 'Statistiques',
            'description' => 'Module Statistiques',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function caParMois() {

        $ca = $marge = $nbChantiers = $caN = $margeN = $nbChantiersN = $this->grilleMensuelle;

        $dataAffaires = $this->managerAffaires->getAffairesStats(array('affaireEtat' => 3, 'affaireDateCloture >=' => $this->debutAnalyse, 'affaireDateCloture <=' => $this->finAnalyse));
        if (!empty($dataAffaires)):
            foreach ($dataAffaires as $data):
                $mois = $data->mois;
                $ca[$mois] = $data->caAffaires;
                $marge[$mois] = $data->margeAffaires;
                $nbChantiers[$mois] = $data->nbAffaires;
            endforeach;
        endif;
        $dataAffairesN = $this->managerAffaires->getAffairesStats(array('affaireEtat' => 3, 'affaireDateCloture >=' => $this->debutAnalyseN, 'affaireDateCloture <=' => $this->finAnalyseN));
        if (!empty($dataAffairesN)):
            foreach ($dataAffairesN as $dataN):
                $mois = $dataN->mois;
                $caN[$mois] = $dataN->caAffaires;
                $margeN[$mois] = $dataN->margeAffaires;
                $nbChantiersN[$mois] = $dataN->nbAffaires;
            endforeach;
        endif;

        $data = array(
            'mois' => $this->labelsMois,
            'ca' => implode(",", $ca),
            'marge' => implode(",", $marge),
            'nbChantiers' => implode(",", $nbChantiers),
            'caN' => implode(",", $caN),
            'margeN' => implode(",", $margeN),
            'nbChantiersN' => implode(",", $nbChantiersN),
            'title' => 'Evolution du CA',
            'description' => 'Chiffre d\'affaires et marges par mois sur l\'année fiscale en cous',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function caCumul() {

        $caCumul = $margeCumul = $caCumulN = $margeCumulN = $this->grilleMensuelle;
        $caTemp = $margeTemp = $caTempN = $margeTempN = 0;

        $dataAffaires = $this->managerAffaires->getAffairesStats(array('affaireEtat' => 3, 'affaireDateCloture >=' => $this->debutAnalyse, 'affaireDateCloture <=' => $this->finAnalyse));
        log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($dataAffaires, true));
        if (!empty($dataAffaires)):

            foreach ($caCumul as $mois => $value):
                foreach ($dataAffaires as $affaire):
                    if ($affaire->mois == $mois):
                        $caTemp += $affaire->caAffaires;
                        $margeTemp += $affaire->margeAffaires;
                        $caCumul[$mois] = $caTemp;
                        $margeCumul[$mois] = $margeTemp;
                        continue;
                    endif;
                endforeach;
            endforeach;

        endif;

        $dataAffairesN = $this->managerAffaires->getAffairesStats(array('affaireEtat' => 3, 'affaireDateCloture >=' => $this->debutAnalyseN, 'affaireDateCloture <=' => $this->finAnalyseN));
        if (!empty($dataAffairesN)):
            foreach ($caCumulN as $mois => $value):
                foreach ($dataAffairesN as $affaireN):
                    if ($affaireN->mois == $mois):
                        $caTempN += $affaireN->caAffaires;
                        $margeTempN += $affaireN->margeAffaires;
                        $caCumulN[$mois] = $caTempN;
                        $margeCumulN[$mois] = $margeTempN;
                        continue;
                    endif;
                endforeach;
            endforeach;
        endif;

        $data = array(
            'mois' => $this->labelsMois,
            'caCumul' => implode(",", $caCumul),
            'margeCumul' => implode(",", $margeCumul),
            'caCumulN' => implode(",", $caCumulN),
            'margeCumulN' => implode(",", $margeCumulN),
            'title' => 'Evolution du CA en cumulé',
            'description' => 'Chiffre d\'affaires et marges cumulés sur l\'année fiscale en cous',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function performanceGlobale() {

        $performances['-100% et plus'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, -99999, -100)) ? count($result) : 0;
        $performances['-50% à -100%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, -100, -50)) ? count($result) : 0;
        $performances['-20% à -50%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, -50, -20)) ? count($result) : 0;
        $performances['-10% à -20%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, -20, -10)) ? count($result) : 0;
        $performances['-5% à -10%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, -10, -5)) ? count($result) : 0;
        $performances['-5% à 0%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, -5, 0)) ? count($result) : 0;
        $performances['0 à 5%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, 0, 5)) ? count($result) : 0;
        $performances['5% à 10%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, 5, 10)) ? count($result) : 0;
        $performances['10% à 20%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, 10, 20)) ? count($result) : 0;
        $performances['20% à 50%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, 20, 50)) ? count($result) : 0;
        $performances['50% à 100%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, 50, 100)) ? count($result) : 0;
        $performances['100% et plus'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, 100, 99999)) ? count($result) : 0;

        $performancesN['-100% et plus'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, -99999, -100)) ? count($result) : 0;
        $performancesN['-50% à -100%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, -100, -50)) ? count($result) : 0;
        $performancesN['-20% à -50%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, -50, -20)) ? count($result) : 0;
        $performancesN['-10% à -20%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, -20, -10)) ? count($result) : 0;
        $performancesN['-5% à -10%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, -10, -5)) ? count($result) : 0;
        $performancesN['-5% à 0%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, -5, 0)) ? count($result) : 0;
        $performancesN['0 à 5%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, 0, 5)) ? count($result) : 0;
        $performancesN['5% à 10%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, 5, 10)) ? count($result) : 0;
        $performancesN['10% à 20%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, 10, 20)) ? count($result) : 0;
        $performancesN['20% à 50%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, 20, 50)) ? count($result) : 0;
        $performancesN['50% à 100%'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, 50, 100)) ? count($result) : 0;
        $performancesN['100% et plus'] = !empty($result = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyseN, $this->finAnalyseN, 100, 99999)) ? count($result) : 0;

        $data = array(
            'performances' => $performances,
            'performancesN' => $performancesN,
            'title' => 'Performances chantiers',
            'description' => 'Chiffre d\'affaires et marges cumulés sur l\'année fiscale en cous',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function performancesGlobalesRangeDetails() {

        switch ($this->input->post('range')):
            case 0:
                $min = -99999;
                $max = -100;
                break;
            case 1:
                $min = -100;
                $max = -50;
                break;
            case 2:
                $min = -50;
                $max = -20;
                break;
            case 3:
                $min = -20;
                $max = -10;
                break;
            case 4:
                $min = -10;
                $max = -5;
                break;
            case 5:
                $min = -5;
                $max = 0;
                break;
            case 6:
                $min = 0;
                $max = 5;
                break;
            case 7:
                $min = 5;
                $max = 10;
                break;
            case 8:
                $min = 10;
                $max = 20;
                break;
            case 9:
                $min = 20;
                $max = 50;
                break;
            case 10:
                $min = 50;
                $max = 100;
                break;
            case 11:
                $min = 100;
                $max = 99999;
                break;
        endswitch;

        $details = array();
        $chantiers = $this->managerChantiers->getPerformancesGlobalesRangeTaux($this->debutAnalyse, $this->finAnalyse, $min, $max);
        if (!empty($chantiers)):
            foreach ($chantiers as $chantier):
                $chantier->hydrateClient();
                $details[] = array(
                    'chantierId' => $chantier->getChantierId(),
                    'affaireId' => $chantier->getChantierAffaire()->getAffaireId(),
                    'client' => $chantier->getChantierClient()->getClientNom() . ' - ' . $chantier->getChantierClient()->getClientVille(),
                    'affaireObjet' => $chantier->getChantierAffaire()->getAffaireObjet(),
                    'chantierObjet' => $chantier->getChantierObjet(),
                    'chantierCategorie' => $chantier->getChantierCategorie(),
                    'chantierDeltaHeures' => $chantier->getChantierDeltaHeures(),
                    'chantierPerformanceHeures' => $chantier->getChantierPerformanceHeures() . '%'
                );
            endforeach;
        endif;
        echo json_encode(array('type' => 'success', 'chantiers' => $details));
    }

}