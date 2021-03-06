<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Personnels extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(25, 26)))) :
            redirect('organibat/board');
        endif;
    }

    public function index() {
        redirect('personnels/liste');
    }

    public function changeAnneeAnalyse() {
        $this->session->set_userdata('analysePersonnelsAnnee', $this->input->post('annee'));
        echo json_encode(array('type' => 'success'));
    }

    /**
     * Calcule la somme des heures hebdomadaire du personnel actif en fonction de l'horaire qui leur est attribué
     */
    private function getBaseHebdomadaire() {
        $personnels = $this->managerPersonnels->getPersonnels(array('personnelActif' => 1));
        $baseHebdomadaire = 0;
        if (!empty($personnels)):
            foreach ($personnels as $personnel):
                $personnel->hydrateHoraire();
                if (!empty($personnel->getPersonnelHoraire())):
                    $baseHebdomadaire += $personnel->getPersonnelHoraire()->getHoraireTotal();
                else:
                    $baseHebdomadaire += 35;
                endif;
            endforeach;
        endif;
        return $baseHebdomadaire;
    }

    public function liste() {

        $personnels = $this->managerPersonnels->getPersonnels();
        if (!empty($personnels)):
            foreach ($personnels as $p):
                $p->hydrateEquipe();
                $p->hydrateHoraire();
            endforeach;
        endif;

        $data = array(
            'equipes' => $this->managerEquipes->getEquipes(),
            'horaires' => $this->managerHoraires->getHoraires(),
            'personnels' => $personnels,
            'title' => 'Personnels',
            'description' => 'Liste du personnel de l\'entreprise',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function fichePersonnel($personnelId = null, $tauxHoraireId = null) {

        if (!$this->ion_auth->in_group(21)):
            redirect('personnels/liste');
        endif;

        if (!$personnelId || !$this->existPersonnel($personnelId)):
            redirect('personnels/liste');
        endif;

        $personnel = $this->managerPersonnels->getPersonnelById($personnelId);
        //$personnel->hydrateTauxHoraires();
        $personnel->hydrateHoraire();

        if ($tauxHoraireId && $this->existTauxHoraire($tauxHoraireId)):
            $tauxHoraire = $this->managerTauxHoraires->getTauxHoraireById($tauxHoraireId);
        else:
            $tauxHoraire = '';
        endif;

        $annee = $this->session->userdata('analysePersonnelsAnnee');

        /* Graph des indisponibilités */
        $motifs = $this->managerMotifs->getMotifs('motifGroupe, motifNom ASC', 'array');
        foreach ($motifs as $motif):
            $motif->nbJours = $this->managerIndisponibilites->getJoursIndisponibilites($personnel, $motif->motifId, $annee) ? $this->managerIndisponibilites->getJoursIndisponibilites($personnel, $motif->motifId, $annee)[0]->nbJours : 0;
        endforeach;

        /* Graph des performances */
        $performances['-100% et plus'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, -99999, -100)) ? count($result) : 0;
        $performances['-50% à -100%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, -100, -50)) ? count($result) : 0;
        $performances['-20% à -50%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, -50, -20)) ? count($result) : 0;
        $performances['-10% à -20%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, -20, -10)) ? count($result) : 0;
        $performances['-5% à -10%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, -10, -5)) ? count($result) : 0;
        $performances['-5% à 0%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, -5, 0)) ? count($result) : 0;
        $performances['0 à 5%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, 0, 5)) ? count($result) : 0;
        $performances['5% à 10%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, 5, 10)) ? count($result) : 0;
        $performances['10% à 20%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, 10, 20)) ? count($result) : 0;
        $performances['20% à 50%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, 20, 50)) ? count($result) : 0;
        $performances['50% à 100%'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, 50, 100)) ? count($result) : 0;
        $performances['100% et plus'] = !empty($result = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnelRangeTaux($personnel, $annee, 100, 99999)) ? count($result) : 0;

        $data = array(
            'personnel' => $personnel,
            'indispos' => $motifs,
            'performances' => $performances,
            'tauxHoraire' => $tauxHoraire,
            'equipes' => $this->managerEquipes->getEquipes(),
            'horaires' => $this->managerHoraires->getHoraires(),
            'title' => 'Personnel ' . $personnel->getPersonnelNom(),
            'description' => 'Fiche personnel',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addPersonnel() {

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

        if (!$this->form_validation->run('addPersonnel')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addPersonnelId')):
                $personnel = $this->managerPersonnels->getPersonnelById($this->input->post('addPersonnelId'));
                $personnel->setPersonnelNom(strtoupper($this->input->post('addPersonnelNom')));
                $personnel->setPersonnelPrenom(ucfirst($this->input->post('addPersonnelPrenom')));
                $personnel->setPersonnelQualif($this->input->post('addPersonnelQualif'));
                $personnel->setPersonnelCode($this->input->post('addPersonnelCode'));
                $personnel->setPersonnelPortable(str_replace(array(' ', '.'), array('', ''), $this->input->post('addPersonnelPortable')));
                $personnel->setPersonnelMessage($this->input->post('addPersonnelMessage'));
                $personnel->setPersonnelEquipeId($this->input->post('addPersonnelEquipeId') ?: null);
                $personnel->setPersonnelHoraireId($this->input->post('addPersonnelHoraireId') ?: null);
                $personnel->setPersonnelPointages($this->input->post('addPersonnelHoraireId') ? $this->input->post('addPersonnelPointages') : 1);
                $personnel->setPersonnelActif($this->input->post('addPersonnelActif') ? 1 : 0);
                $personnel->setPersonnelType($this->input->post('addPersonnelType'));
                $this->managerPersonnels->editer($personnel);

            else:

                $dataPersonnel = array(
                    'personnelEtablissementId' => $this->session->userdata('etablissementId'),
                    'personnelNom' => strtoupper($this->input->post('addPersonnelNom')),
                    'personnelPrenom' => ucfirst($this->input->post('addPersonnelPrenom')),
                    'personnelQualif' => $this->input->post('addPersonnelQualif'),
                    'personnelType' => $this->input->post('addPersonnelType'),
                    'personnelCode' => $this->input->post('addPersonnelCode'),
                    'personnelPortable' => str_replace(array(' ', '.'), array('', ''), $this->input->post('addPersonnelPortable')),
                    'personnelMessage' => $this->input->post('addPersonnelMessage'),
                    'personnelEquipeId' => $this->input->post('addPersonnelEquipeId') ?: null,
                    'personnelHoraireId' => $this->input->post('addPersonnelHoraireId') ?: null,
                    'personnelPointages' => $this->input->post('addPersonnelHoraireId') ? $this->input->post('addPersonnelPointages') : 1,
                    'personnelActif' => $this->input->post('addPersonnelActif') ? 1 : 0
                );
                $personnel = new Personnel($dataPersonnel);
                $this->managerPersonnels->ajouter($personnel);

            endif;

            $etablissementBaseHebdomadaire = $this->getBaseHebdomadaire();
            $etablissement = $this->managerEtablissements->getEtablissementById($this->session->userdata('etablissementId'));
            $etablissement->setEtablissementBaseHebdomadaire($etablissementBaseHebdomadaire);
            $this->managerEtablissements->editer($etablissement);
            $this->session->set_userdata('etablissementBaseHebdomadaire', $etablissementBaseHebdomadaire);

            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function equipes($equipeId = null) {

        $equipes = $this->managerEquipes->getEquipes();

        if ($equipeId):
            if ($this->existEquipe($equipeId)):
                $equipe = $this->managerEquipes->getEquipeById($equipeId);
            else:
                redirect('personnels/equipes');
            endif;
        endif;

        $personnels = $this->managerPersonnels->getPersonnels(array('personnelActif' => 1), '(-personnelEquipeId) DESC, personnelNom ASC');
        if (!empty($personnels)):
            foreach ($personnels as $personnel) {
                $personnel->hydrateEquipe();
            }
        endif;

        $data = array(
            'personnels' => !empty($personnels) ? $personnels : '',
            'equipes' => $equipes,
            'equipe' => $equipeId ? $equipe : '',
            'title' => 'Equipes',
            'description' => 'Liste de vos équipes',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addEquipe() {

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

        if (!$this->form_validation->run('addEquipe')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        if ($this->input->post('addEquipeId')):

            $equipe = $this->managerEquipes->getEquipeById($this->input->post('addEquipeId'));
            $equipe->setEquipeNom(mb_strtoupper($this->input->post('addEquipeNom')));
            $equipe->setEquipeCouleur($this->input->post('addEquipeCouleur'));
            $equipe->setEquipeCouleurSecondaire($this->couleurSecondaire($this->input->post('addEquipeCouleur')));
            $this->managerEquipes->editer($equipe);

        else:

            $arrayEquipe = array(
                'equipeNom' => mb_strtoupper($this->input->post('addEquipeNom')),
                'equipeCouleur' => $this->input->post('addEquipeCouleur'),
                'equipeCouleurSecondaire' => $this->couleurSecondaire($this->input->post('addEquipeCouleur'))
            );
            $equipe = new Equipe($arrayEquipe);
            $this->managerEquipes->ajouter($equipe);

        endif;
        echo json_encode(array('type' => 'success'));
    }

    public function delEquipe() {

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

        if (!$this->form_validation->run('getEquipe')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $equipe = $this->managerEquipes->getEquipeById($this->input->post('equipeId'));
            $this->managerEquipes->delete($equipe);
            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function affectationPersonnelEquipe() {

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

        if ($this->form_validation->run('getEquipe') && $this->form_validation->run('getPersonnel')):

            $personnel = $this->managerPersonnels->getPersonnelById($this->input->post('personnelId'));
            if ($personnel->getPersonnelEquipeId() == $this->input->post('equipeId')):
                $personnel->setPersonnelEquipeId(null);
            else:
                $personnel->setPersonnelEquipeId($this->input->post('equipeId'));
            endif;
            $this->managerPersonnels->editer($personnel);
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
    }

    public function addTauxHoraire() {

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

        if (!$this->form_validation->run('addTauxHoraire')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            if ($this->input->post('addTauxHoraireId')):
                $taux = $this->managerTauxHoraires->getTauxHoraireById($this->input->post('addTauxHoraireId'));
                $taux->setTauxHoraire($this->input->post('addTauxHoraire'));
                $taux->setTauxHoraireDate($this->own->mktimeFromInputDate($this->input->post('addTauxHoraireDate')));
                $this->managerTauxHoraires->editer($taux);
            else:

                $dataTaux = array(
                    'tauxHorairePersonnelId' => $this->input->post('addTauxHorairePersonnelId'),
                    'tauxHoraireDate' => $this->own->mktimeFromInputDate($this->input->post('addTauxHoraireDate')),
                    'tauxHoraire' => $this->input->post('addTauxHoraire')
                );
                $taux = new TauxHoraire($dataTaux);
                $this->managerTauxHoraires->ajouter($taux);
            endif;
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function delTauxHoraire() {

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(26)))) :
            redirect('organibat/board');
        endif;

        if (!$this->form_validation->run('getTauxHoraire')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $taux = $this->managerTauxHoraires->getTauxHoraireById($this->input->post('tauxHoraireId'));
            $this->managerTauxHoraires->delete($taux);
            echo json_encode(array('type' => 'success'));

        endif;
    }

    public function addIndisponibilite() {
        if (!$this->form_validation->run('addIndispo')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            /* Personnels du planning */
            $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($this->session->userdata('planningPersonnelsIds'));
            if (!empty($personnelsPlanning)):
                foreach ($personnelsPlanning as $personnel):
                    $personnel->hydrateEquipe();
                endforeach;
            endif;

            $debutDate = $this->own->mktimeFromInputDate($this->input->post('addIndispoDebutDate'));
            $finDate = $this->own->mktimeFromInputDate($this->input->post('addIndispoFinDate'));
            $nbDemi = $this->cal->nbDemiEntreDates($debutDate, $this->input->post('addIndispoDebutMoment'), $finDate, $this->input->post('addIndispoFinMoment'));
            $cases = $this->cal->nbCasesEntreDates($debutDate, $this->input->post('addIndispoDebutMoment'), $finDate, $this->input->post('addIndispoFinMoment'));

            if ($this->input->post('addIndispoId')):
                $indispo = $this->managerIndisponibilites->getIndisponibiliteById($this->input->post('addIndispoId'));
                $indispo->setIndispoDebutDate($debutDate);
                $indispo->setIndispoFinDate($finDate);
                $indispo->setIndispoDebutMoment($this->input->post('addIndispoDebutMoment'));
                $indispo->setIndispoFinMoment($this->input->post('addIndispoFinMoment'));
                $indispo->setIndispoNbDemi($nbDemi);
                $indispo->setIndispoCases($cases);
                $indispo->setIndispoMotifId($this->input->post('addIndispoMotifId'));

                $this->managerIndisponibilites->editer($indispo);
                $indispo->hydrateMotif();
                $indispo->genereHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                $HTML = $indispo->getIndispoHTML();

            else:
                $HTML = '';
                $arrayIndispo = array(
                    'indispoDebutDate' => $debutDate,
                    'indispoFinDate' => $finDate,
                    'indispoDebutMoment' => $this->input->post('addIndispoDebutMoment'),
                    'indispoFinMoment' => $this->input->post('addIndispoFinMoment'),
                    'indispoNbDemi' => $nbDemi,
                    'indispoCases' => $cases,
                    'indispoMotifId' => $this->input->post('addIndispoMotifId'),
                    'indispoAffichage' => 1
                );

                foreach ($this->input->post('addIndispoPersonnelsIds') as $personnelId):

                    $arrayIndispo['indispoPersonnelId'] = $personnelId;
                    $indispo = new Indisponibilite($arrayIndispo);
                    $this->managerIndisponibilites->ajouter($indispo);
                    $indispo->genereHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                    $HTML .= $indispo->getIndispoHTML();
                    unset($indispo);

                endforeach;

            endif;
            echo json_encode(array('type' => 'success', 'HTML' => $HTML));
        endif;
    }

    public function getIndisponibiliteDetails() {
        if (!$this->form_validation->run('getIndispo')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $indispo = $this->managerIndisponibilites->getIndisponibiliteById($this->input->post('indispoId'), 'array');

            echo json_encode(array('type' => 'success', 'indispo' => $indispo));
        endif;
    }

    /* Passe l'affectation à un affichage FULL, BAS, HAUT */

    public function indisponibiliteToggleAffichage() {
        if (!$this->form_validation->run('getIndispo')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $indispo = $this->managerIndisponibilites->getIndisponibiliteById($this->input->post('indispoId'));
            $indispo->toggleAffichage();
            $indispo->genereHTML($this->session->userdata('premierJourPlanning'), array(), $this->input->post('ligne'), $this->hauteur, $this->largeur);
            $this->managerIndisponibilites->editer($indispo);
            echo json_encode(array('type' => 'success', 'html' => $indispo->getIndispoHTML()));
        endif;
    }

    public function delIndisponibilite() {
        if (!$this->ion_auth->in_group(26)):
            echo json_encode(array('type' => 'error', 'message' => 'Vous ne possédez pas les droits necessaires'));
        elseif (!$this->form_validation->run('getIndispo')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $indispo = $this->managerIndisponibilites->getIndisponibiliteById($this->input->post('indispoId'));
            $this->managerIndisponibilites->delete($indispo);
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function exportPerformancesPersonnel($personnelId = null) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $styleEntete = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'wrapText' => true
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'argb' => 'CCCCCCC',
                ]
            ]
        ];

        $styleAlignRight = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ]
        ];
        $styleAlignCenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];

        $sheet->getStyle('A1:I1')->applyFromArray($styleEntete);

        if ($personnelId && $this->existPersonnel($personnelId)):

            $personnel = $this->managerPersonnels->getPersonnelById($personnelId);

            $performances = $this->managerPerformanceChantiersPersonnels->getPerformancesByPersonnel($personnel, $this->session->userdata('analysePersonnelsAnnee'));
            $sheet->getRowDimension('1')->setRowHeight(31);

            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);

            $sheet->setCellValue('A1', 'Client')
                    ->setCellValue('B1', 'Affaire')
                    ->setCellValue('C1', 'Chantier')
                    ->setCellValue('D1', 'Catégorie')
                    ->setCellValue('E1', 'Date de clôture')
                    ->setCellValue('F1', 'Delta Heures Chantier')
                    ->setCellValue('G1', '% Participation')
                    ->setCellValue('H1', 'Delta Heures ' . $personnel->getPersonnelPrenom())
                    ->setCellValue('I1', '% Gain/Perte ' . $personnel->getPersonnelPrenom());

            $row = 2;
            foreach ($performances as $performance):
                $performance->hydrateAffaire();
                $sheet->getRowDimension($row)->setRowHeight(18);
                $sheet->setCellValue('A' . $row, $performance->getPerformanceClientNom());
                $sheet->setCellValue('B' . $row, $performance->getPerformanceAffaire()->getAffaireObjet());
                $sheet->setCellValue('C' . $row, $performance->getPerformanceChantier()->getChantierObjet());
                $sheet->setCellValue('D' . $row, $performance->getPerformanceChantier()->getChantierCategorie());
                $sheet->setCellValue('E' . $row, $this->cal->dateFrancais($performance->getPerformanceChantier()->getChantierDateCloture(), 'DMa'));
                $sheet->setCellValue('F' . $row, $performance->getPerformanceChantier()->getChantierDeltaHeures());
                $sheet->setCellValue('G' . $row, $performance->getPerformanceTauxParticipation() . '%');
                $sheet->setCellValue('H' . $row, $performance->getPerformanceImpactHeures());
                $sheet->setCellValue('I' . $row, $performance->getPerformanceImpactTaux() . '%');

                $row++;
            endforeach;

            $sheet->getRowDimension($row)->setRowHeight(18);
            $sheet->setCellValue('H' . $row, '=SUM(H1:H' . ($row - 1) . ')');
            $sheet->setCellValue('G' . $row, 'Total');
            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray(
                    array(
                        'font' => array('bold' => true)
                    )
            );
            $sheet->getStyle('G2:G' . $row)->applyFromArray($styleAlignRight);
            $sheet->getStyle('I2:I' . $row)->applyFromArray($styleAlignRight);
            $sheet->getStyle('E2:E' . $row)->applyFromArray($styleAlignCenter);

            $writer = new Xls($spreadsheet);
            $fichier = 'Performances-' . $personnel->getPersonnelNom() . '-' . $personnel->getPersonnelPrenom() . '-' . $this->session->userdata('analysePersonnelsAnnee') . '.xls';
            $writer->save($fichier);
            force_download($fichier, NULL);

        endif;
    }

    public function rttReport($personnelId = null, $annee = null) {
        if (!$personnelId || !$this->existPersonnel($personnelId)):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $personnel = $this->managerPersonnels->getPersonnelById($personnelId);

            $soldeBefore = $this->managerHeuresSupp->getSoldeBefore($personnel->getpersonnelId(), $annee);
            $soldeAfter = $this->managerHeuresSupp->getSoldeBefore($personnel->getpersonnelId(), ($annee + 1));
            $heuresSupp = $this->managerHeuresSupp->getHeuresSupp(array('hsPersonnelId' => $personnel->getPersonnelId(), 'hsAnnee' => $annee), 'hsAnnee, hsSemaine ASC', 'object');

            require_once('application/libraries/tcpdf/tcpdf.php');
            $this->load->library('tcpdf/MYPDF');

            $this->piedPage1 = '';
            $this->piedPage2 = 'Généré par Organibat.com - Le ' . $this->cal->dateFrancais(time(), 'JDMA');

            /* --- Génération du HEADER ---- */
            $etablissement = $this->managerEtablissements->getEtablissementById($this->session->userdata('etablissementId'));
            $header = '<table>
    <tr style="font-size:12px;">
        <td style="text-align: left; width:150px;">
<span style="font-size:11px; font-weight: bold;">'
                    . $etablissement->getEtablissementNom() . '</span>
                        <span style="font-size:9px;">
                            <br>' . $etablissement->getEtablissementAdresse() . ', ' . $etablissement->getEtablissementCp() . ' ' . $etablissement->getEtablissementVille() . '<br>Tel : ' . $etablissement->getEtablissementTelephone() . ' - Email : ' . $etablissement->getEtablissementEmail()
                    . '</span>
        </td>
        <td style="text-align: center; width:240px; font-size:19px;">
            Relevé RTT ' . $annee . '
        </td>
        <td style="text-align: right; width:140px; font-size:12px;">'
                    . $personnel->getPersonnelPrenom() . ' ' . $personnel->getPersonnelNom() . '<br>' . $personnel->getPersonnelQualif() .
                    '</td>
    </tr>
</table>';

            $title = 'Relevé RTT - ' . $annee . ' - ' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom();
            $data = array(
                'heuresSupp' => $heuresSupp,
                'annee' => $annee,
                'soldeBefore' => $soldeBefore,
                'soldeAfter' => $soldeAfter,
                'title' => $title,
                'description' => '',
                'keywords' => '',
                'content' => $this->viewFolder . '/' . __FUNCTION__
            );
            $this->load->view('template/contentDocuments', $data);

            // Extend the TCPDF class to create custom Header and Footer
            $html = $this->output->get_output();

            // create new PDF document
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Organibat');
            $pdf->SetTitle($title);
            $pdf->SetSubject($title);

            $pdf->SetMargins(14, 35, 5);
            // set auto page breaks
            $pdf->SetAutoPageBreak(true, 15);
            $pdf->AddPage('', '', FALSE, FALSE, $header);

            $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
            ob_clean();
            $pdf->Output($title . '.pdf', 'FI');
        endif;
    }

}
