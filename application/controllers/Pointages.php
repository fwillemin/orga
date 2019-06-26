<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pointages extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(80, 81, 82, 83, 4)))) :
            redirect('organibat/board');
        endif;
    }

    public function index() {
        redirect('pointages/heures');
    }

    public function heures($semaine = null, $annee = null) {
        if (!$this->ion_auth->in_group(array(80, 81, 82))) :
            redirect('organibat/board');
        endif;

        if (!$annee):
            $annee = date('Y', time());
        endif;
        if (!$semaine):
            $semaine = date('W', time());
        endif;

        $premierJourSemaine = $this->cal->premierJourFromNumSemaine($semaine, $annee);
        $results = array();
        $affectations = $this->managerAffectations->getAffectations(array('affectationDebutDate <' => ($premierJourSemaine + 7 * 86400), 'affectationFinDate >=' => $premierJourSemaine));

        /* liste du personnel */
        $listePersonnelId = array();
        if (!empty($affectations)):
            foreach ($affectations AS $affectation):
                if (!in_array($affectation->getAffectationPersonnelId(), $listePersonnelId)):
                    $listePersonnelId[] = $affectation->getAffectationPersonnelId();
                endif;
            endforeach;
        endif;
        if (!empty($listePersonnelId)):
            $personnels = $this->managerPersonnels->getPersonnelsFromListeIds($listePersonnelId);
            foreach ($personnels as $personnel):
                $results[$personnel->getPersonnelId()]['affectations'] = $this->managerAffectations->getAffectations(array('affectationDebutDate <' => ($premierJourSemaine + 7 * 86400), 'affectationFinDate >=' => $premierJourSemaine, 'affectationPersonnelId' => $personnel->getPersonnelId()));
                if (!empty($results[$personnel->getPersonnelId()]['affectations'])):
                    foreach ($results[$personnel->getPersonnelId()]['affectations'] as $affectation):
                        $affectation->hydrateHeures();
                        $affectation->hydrateChantier();
                        $affectation->getAffectationChantier()->hydrateClient();
                    endforeach;
                endif;
                $results[$personnel->getPersonnelId()]['indisponibilites'] = $this->managerIndisponibilites->getIndisponibilites(array('indispoDebutDate <' => ($premierJourSemaine + 7 * 86400), 'indispoFinDate >=' => $premierJourSemaine, 'indispoPersonnelId' => $personnel->getPersonnelId()));
            endforeach;

        else:
            $personnels = array();
        endif;

        $data = array(
            'premierJourSemaine' => $premierJourSemaine,
            'results' => $results,
            'semaine' => $semaine,
            'annee' => $annee,
            'personnels' => $personnels,
            'title' => 'Pointages',
            'description' => 'Gestion des pointages',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function heuresSupp($semaine = null, $annee = null) {
        if (!$this->ion_auth->in_group(array(80, 81, 82))) :
            redirect('organibat/board');
        endif;

        if (!$annee):
            $annee = date('Y', time());
        endif;
        if (!$semaine):
            $semaine = date('W', time());
        endif;

        $premierJourSemaine = $this->cal->premierJourFromNumSemaine($semaine, $annee);
        $results = array();
        $affectations = $this->managerAffectations->getAffectations(array('affectationDebutDate <' => ($premierJourSemaine + 7 * 86400), 'affectationFinDate >=' => $premierJourSemaine));

        /* liste du personnel */
        $listePersonnelId = array();
        if (!empty($affectations)):
            foreach ($affectations AS $affectation):
                if (!in_array($affectation->getAffectationPersonnelId(), $listePersonnelId)):
                    $listePersonnelId[] = $affectation->getAffectationPersonnelId();
                endif;
            endforeach;
        endif;
        if (!empty($listePersonnelId)):
            $personnels = $this->managerPersonnels->getPersonnelsFromListeIds($listePersonnelId);
            foreach ($personnels as $personnel):
                $nbJoursRTT = 0;
                $personnel->hydrateHoraire();
                $rtts = $this->managerIndisponibilites->getRTTPeriodeByPersonnelId($personnel->getPersonnelId(), $premierJourSemaine, ($premierJourSemaine + 7 * 86400));
                if (!empty($rtts)):
                    foreach ($rtts as $rtt):
                        $nbJoursRTT += $this->own->nbHeuresIndispo($rtt);
                    endforeach;
                endif;
                $results[$personnel->getPersonnelId()]['rtt'] = $nbJoursRTT;

                $heuresPointeesJours = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0); /* 7 = somme de la semaine */
                $heuresPointees = $this->managerHeures->getHeuresPeriodeByPersonnelId($personnel->getPersonnelId(), $premierJourSemaine, ($premierJourSemaine + 7 * 86400));
                if (!empty($heuresPointees)):
                    foreach ($heuresPointees as $pointage):
                        $heuresPointeesJours[date('w', $pointage->heureDate)] = $pointage->nbHeuresPointees;
                        $heuresPointeesJours['7'] += $pointage->nbHeuresPointees;
                    endforeach;
                endif;

                $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'] = $heuresPointeesJours;

                /* Heures supp exitantes */
                $exist = $this->managerHeuresSupp->getHeuresSupp(array('hsPersonnelId' => $personnel->getPersonnelId(), 'hsAnnee' => $annee, 'hsSemaine' => $semaine))[0];
                $results[$personnel->getPersonnelId()]['heuresSuppSaved'] = $exist;
            endforeach;

        else:
            $personnels = array();
        endif;

        $data = array(
            'premierJourSemaine' => $premierJourSemaine,
            'results' => $results,
            'semaine' => $semaine,
            'annee' => $annee,
            'personnels' => $personnels,
            'title' => 'Heures supplémentaires',
            'description' => 'Gestion des heures du personnel, validation, réaffectation client, préparation salaires',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function getSemaineAnnee() {
        $jour = $this->own->mktimeFromInputDate($this->input->post('dateFormattee'));
        echo json_encode(array('type' => 'success', 'semaine' => date('W', $jour), 'annee' => date('Y', $jour)));
    }

    /**
     * Valide une heure directement depuis son Id
     */
    public function quickValide() {
        if (!$this->ion_auth->in_group(array(80, 81, 82))) :
            redirect('organibat/board');
        endif;

        if (!$this->form_validation->run('getHeure')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $heure = $this->managerHeures->getHeureById(intval($this->input->post('heureId')));
            $heure->valide();
            $this->managerHeures->editer($heure);
            echo json_encode(array('type' => 'success'));
        endif;
    }

    /**
     * Ajoute des heures à une affectation
     */
    public function addHeure() {
        if ($this->ion_auth->in_group(array(50, 51, 52))) :
            $heureValide = 1;
        else:
            $heureValide = 0;
        endif;

        if (!$this->form_validation->run('addHeure')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            if ($this->input->post('heureId')):
                $heure = $this->managerHeures->getHeureById($this->input->post('heureId'));
                if ($this->input->post('duree') == 0):
                    $this->managerHeures->delete($heure);
                    unset($heure);
                    $retourClass = 'empty';
                else:
                    $heure->setHeureDuree($this->input->post('duree'));
                    $this->managerHeures->editer($heure);
                    if ($this->ion_auth->in_group(array(4))) :
                        $retourClass = 'unchecked';
                    else:
                        $retourClass = 'valide';
                    endif;
                endif;
            else:
                $arrayHeure = array(
                    'heureAffectationId' => $this->input->post('affectationId'),
                    'heureDate' => $this->input->post('jour'),
                    'heureDuree' => $this->input->post('duree'),
                    'heureValide' => $heureValide
                );
                $heure = new Heure($arrayHeure);
                $this->managerHeures->ajouter($heure);
                if ($this->ion_auth->in_group(array(4))) :
                    $retourClass = 'unchecked';
                else:
                    $retourClass = 'valide';
                endif;
            endif;
            echo json_encode(array('type' => 'success', 'retourClass' => $retourClass, 'heureId' => (!empty($heure) ? $heure->getHeureId() : '')));

        endif;
    }

    /**
     * Edite une feuille de pointage mensuelle pour le personnel et pour une periode donnée
     * @param integer $personnel Id du personnel pour lequel retourner la feuille de pointage
     * @param string $periode période de la feuille de pointage sous le format
     */
    public function feuilles($personnelId = null, $periode = null) {
        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group(array(80, 81, 82)))) :
            redirect('organibat/board');
        endif;

        if (!$this->existPersonnel($personnelId) || !$periode):
            $personnel = $sauvegarde = '';
            $heures = $indisponibilites = array();
            $mois = date('m');
            $annee = date('Y');
        else:

            $personnel = $this->managerPersonnels->getPersonnelById($personnelId);
            $personnel->hydrateHoraire();
            $personnel->hydrateEquipe();

            $mois = explode('-', $periode)[0];
            $annee = explode('-', $periode)[1];

            /* Recherche d'un pointage enregistré */
            $pointage = $this->managerPointages->getPointage($personnel->getPersonnelId(), $mois, $annee);
            if (!empty($pointage)):
                $pointage->setPointageHTML(gzuncompress($pointage->getPointageHTML()));
                $indisponibilites = $heures = array();
            else:
                $premierJour = mktime(0, 0, 0, $mois, 1, $annee);
                $dernierJour = mktime(23, 59, 59, $mois, date('t', mktime(0, 0, 0, $mois, 1, $annee)), $annee);

                $heures = $this->managerHeures->getHeures(array('h.heureDate >=' => $premierJour, 'h.heureDate <=' => $dernierJour, 'a.affectationPersonnelId' => $personnel->getPersonnelId()));
                if (!empty($heures)):
                    foreach ($heures as $heure):
                        $heure->hydrateAffectation();
                        $heure->getHeureAffectation()->hydratePlace();
                    endforeach;
                endif;
                $indisponibilites = $this->managerIndisponibilites->getIndisponibilites(array('i.indispoFinDate >=' => $premierJour, 'i.indispoDebutDate <=' => $dernierJour, 'i.indispoPersonnelId' => $personnel->getPersonnelId()));
            endif;
        endif;

        $data = array(
            'mois' => $mois,
            'annee' => $annee,
            'heures' => $heures,
            'indisponibilites' => $indisponibilites,
            'personnels' => $this->managerPersonnels->getPersonnels(),
            'personnel' => $personnel,
            'etablissement' => $this->managerEtablissements->getEtablissementById($this->session->userdata('etablissementId')),
            'sauvegarde' => !empty($pointage) ? $pointage : null,
            'title' => 'Feuilles de pointages',
            'description' => 'Liste des heures effectuées dans la période',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function reportHeuresSemaineEmployes($semaine = null, $annee = null) {

        $start = time();

        // Include the main TCPDF library (search for installation path).
        require_once('application/libraries/tcpdf/tcpdf.php');

        if (!$annee):
            $annee = date('Y', time());
        endif;
        if (!$semaine):
            $semaine = date('W', time());
        endif;

        $premierJourSemaineW = (mktime(0, 0, 0, 1, 1, $annee) - (date('N', mktime(0, 0, 0, 1, 1, $annee)) - 1) * 86400) + ($semaine - 1) * 604800;
        /* cas des années à 53 semaines (le 01/01 est un jeudi ou plus tard dans la semaine) */
        if (date('w', mktime(0, 0, 0, 1, 1, $annee)) > 4 || date('w', mktime(0, 0, 0, 1, 1, $annee)) == 0):
            $premierJourSemaineW += 604800;
        endif;
        if (date('I', $premierJourSemaineW) == 1):
            $premierJourSemaineW -= 3600;
        endif;

        /* recherches des affectations de la semaine */
        $affectations = $this->managerAffectations->liste(array('debut <' => intval($premierJourSemaineW + 7 * 86400), 'fin >=' => $premierJourSemaineW));

        /* recherche des indisponibilités */
        $indispo = $this->managerIndisponibilites->liste(array('debut <' => intval($premierJourSemaineW + 7 * 86400), 'fin >=' => $premierJourSemaineW));

        /* liste du personnel */
        $personnelListe = array();
        if (!empty($affectations)):
            foreach ($affectations AS $a):
                if (!in_array($a->getId_personnel(), $personnelListe)):
                    $personnelListe[] = $a->getId_personnel();
                endif;
            endforeach;
        endif;

        if (!empty($indispo)):
            foreach ($indispo AS $i):
                if (!in_array($i->getId_personnel(), $personnelListe)):
                    $personnelListe[] = $i->getId_personnel();
                endif;
            endforeach;
        endif;

        if (!empty($personnelListe)):
            $personnels = $this->managerPersonnels->listingInArray($personnelListe, 'p.nom', 'array');
        else:
            $personnels = array();
        endif;

        foreach ($personnels as $p):
            $p['heures'] = $this->managerHeures->getHeuresHebdo(array('p.id' => $p['id'], 'WEEK(FROM_UNIXTIME(h.date,"%Y-%m-%d"),1)' => $semaine, 'YEAR(FROM_UNIXTIME(h.date,"%Y-%m-%d"))' => $annee));
            $p['indispo'] = $this->managerIndisponibilites->liste(array('p.id' => $p['id'], 'debut <' => intval($premierJourSemaineW + 7 * 86400), 'fin >=' => $premierJourSemaineW));
            $personnelsEnrichi[] = $p;
        endforeach;

        /* Recherche des heures saisies dans le systeme */
        //$heures = $this->managerHeures->getHeuresHebdo(array('WEEK(FROM_UNIXTIME(h.date,"%Y-%m-%d"),1)' => $semaine, 'YEAR(FROM_UNIXTIME(h.date,"%Y-%m-%d"))' => $annee));

        $data = array(
            'premierJourSemaineW' => $premierJourSemaineW,
            //'affectations' => $affectations,
            //'heures' => $heures,
            'indispo' => $indispo,
            'semaine' => $semaine,
            'annee' => $annee,
            'personnel' => $personnelsEnrichi,
            'description' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

        // Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, '', '');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Orgnibat.com');
        $pdf->SetTitle('releve');
        $pdf->SetSubject('releve');

        $pdf->SetMargins(5, 5, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('RelevéDetaillé.pdf', 'FI');
    }

    public function reportHeuresSemaineDossiers($semaine = null, $annee = null) {

        // Include the main TCPDF library (search for installation path).
        require_once('application/libraries/tcpdf/tcpdf.php');

        if (!$annee):
            $annee = date('Y', time());
        endif;
        if (!$semaine):
            $semaine = date('W', time());
        endif;

        $premierJourSemaineW = (mktime(0, 0, 0, 1, 1, $annee) - (date('N', mktime(0, 0, 0, 1, 1, $annee)) - 1) * 86400) + ($semaine - 1) * 604800;
        /* cas des années à 53 semaines (le 01/01 est un jeudi ou plus tard dans la semaine) */
        if (date('w', mktime(0, 0, 0, 1, 1, $annee)) > 4 || date('w', mktime(0, 0, 0, 1, 1, $annee)) == 0):
            $premierJourSemaineW += 604800;
        endif;
        if (date('I', $premierJourSemaineW) == 1):
            $premierJourSemaineW -= 3600;
        endif;

        /* recherches des affectations de la semaine */
        $affectations = $this->managerAffectations->liste(array('debut <' => intval($premierJourSemaineW + 7 * 86400), 'fin >=' => $premierJourSemaineW));

        $dossiersConcernes = array();
        if (!empty($affectations)):
            foreach ($affectations AS $a):
                $a->hydrateChantier();
                $a->getAffectationChantier()->hydrateHeures($premierJourSemaineW, intval($premierJourSemaineW + 7 * 86400));
                if (!empty($a->getAffectationChantier()->getChantierHeures())):
                    foreach ($a->getAffectationChantier()->getChantierHeures() as $h):
                        $h->hydrateDetails();
                    endforeach;
                endif;

                if (!in_array($a->getAffectationChantier()->getDossier_id(), $dossiersConcernes)):
                    $dossiersConcernes[$a->getAffectationChantier()->getDossier_id()] = $this->managerDossiers->getDossierById($a->getAffectationChantier()->getDossier_id());
                endif;

                $dossiersConcernes[$a->getAffectationChantier()->getDossier_id()]->addChantier($a->getAffectationChantier());
            endforeach;
        endif;

        $data = array(
            'premierJourSemaineW' => $premierJourSemaineW,
            //'affectations' => $affectations,
            //'heures' => $heures,
            'dossiers' => $dossiersConcernes,
            'semaine' => $semaine,
            'annee' => $annee,
            'description' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

        // Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, '', '');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Orgnibat.com');
        $pdf->SetTitle('releve');
        $pdf->SetSubject('releve');

        $pdf->SetMargins(5, 5, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('RelevéDetaillé.pdf', 'FI');
    }

    public function releve_pdf($personnel = null, $periode = null) {
        $salarie = $this->m_personnel->get_one(array('id' => $personnel));
        if (!$periode || !$personnel || $salarie->id_etablissement != $this->session->userdata('etablissement')): redirect('heures/');
            exit;
        endif;

        $decomp = explode('-', $periode);
        $dernier_jour = date('t', mktime(0, 0, 0, $decomp[0], 1, $decomp[1]));
        $heures = $this->m_heure->liste(array('h.date >=' => mktime(0, 0, 0, $decomp[0], 1, $decomp[1]), 'h.date <=' => mktime(23, 59, 59, $decomp[0], $dernier_jour, $decomp[1]), 'p.id' => $personnel));

        $data = array(
            'heures' => $heures,
            'personnel' => $salarie,
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view($this->view_folder . __FUNCTION__, $data);
        $html = $this->output->get_output();

        $this->load->library('mpdf');
        $mpdf = new Pdf();
        $mpdf->SetHTMLFooter('<img src="' . base_url('assets/img/logo.png') . '" height="30" />');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function releve_pdf_global($periode = null) {
        if (!$periode): redirect('heures/');
            exit;
        endif;

        $this->load->library('mpdf');
        $mpdf = new Pdf();
        $mpdf->SetHTMLFooter('<img src="' . base_url('assets/img/logo.png') . '" height="30" />');
        $mpdf->WriteHTML('<!DOCTYPE html><html lang="fr"><head><link href="' . base_url('assets/bootstrap/css/bootstrap.css') . '" rel="stylesheet"></head><body>');

        $salarie = $this->m_personnel->liste(array('id_etablissement' => $this->session->userdata('etablissement')));
        $decomp = explode('-', $periode);

        foreach ($salarie as $p):
            $heures = $this->m_heure->liste(array('h.date >=' => mktime(0, 0, 0, $decomp[0], 1, $decomp[1]), 'h.date <=' => mktime(23, 59, 59, $decomp[0], date('t', mktime(0, 0, 0, $decomp[0], 1, $decomp[1])), $decomp[1]), 'c.id_etablissement' => $this->session->userdata('etablissement'), 'id_personnel' => $p->id));

            $html = '<div class="row">
                        <div class="col-lg-12" align="center">
                            <h2>Relevé d\'heures</h2>
                            <p>
                            Salarié : <strong>' . $p->nom . ' ' . $p->prenom . '</strong><br/>
                            Période : <strong>' . $this->organibat->get_mois($decomp[0]) . ' ' . $decomp[1] . '</strong>
                            </p>
                        </div>
                    </div>
                    <div class="row" style="margin-top:15px;">
                        <div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3 col-xs-12" style="background-color: #FFF; border-radius:15px; padding:15px; border:1px solid #000; width:90%; margin-left:15px;">

                            <table class="table table-condensed">';

            $semaine = null;
            $total_hebdo = 0;
            $total_mois = 0;

            foreach ($heures as $h):

                if ($semaine != date('W', $h->date)):
                    if ($semaine):
                        $html .= '<TR><TD align="right"><strong>Total </strong></TD><TD style="text-align: right; font-weight: bold;">' . $total_hebdo . '</TD><TD style="text-align: left; font-weight: bold;">Heures</TD></TR>
                                <TR height="15"><TD colspan="3">-</TD></TR>';
                    endif;
                    $total_hebdo = 0;
                    $semaine = date('W', $h->date);
                    $html .= '<TR bgcolor="#5bc0de"><TD colspan="3"><font color="#FFF"><strong>Semaine ' . $semaine . '</strong></font></TD></TR>';

                endif;

                $total_mois += $h->nb_heure;
                $total_hebdo += $h->nb_heure;
                $html .= '<TR>
                            <TD>' . $this->organibat->get_jour(date('l', $h->date)) . ' ' . date('d', $h->date) . ' ' . $this->organibat->get_mois(date('F', $h->date)) . ' ' . date('Y', $h->date) . '</TD>
                            <TD style="text-align: right;">' . $h->nb_heure . '</TD>
                            <TD style="text-align: right;">
                                <a href="' . site_url('chantier/worksite/' . $h->chantier) . '">' . $h->client . '</a>
                            </TD>
                        </TR>';

            endforeach;
            if ($total_hebdo != 0):
                $html .= '<TR><TD align="right"><strong>Total </strong></TD><TD style="text-align: right; font-weight: bold;">' . $total_hebdo . '</TD><TD style="text-align: left; font-weight: bold;">Heures</TD></TR>';
            endif;
            $html .= '<TR style="background-color: #5cb85c; color:#FFF; font-weight: bold;">
                            <TD align="right"><font color="#FFF">Total mensuel </font></TD>
                            <TD style="text-align: right; font-weight: bold;"><font color="#FFF">' . $total_mois . '</font></TD>
                            <TD style="text-align: left; font-weight: bold;">
                                <font color="#FFF">Heures</font>
                            </TD>
                        </TR>
                        </table>
                    </div></div>';

            $mpdf->WriteHTML($html);
            $mpdf->AddPage();
        endforeach;

        $mpdf->Output();
    }

    public function addPointage() {

        if ($this->form_validation->run('addPointage')):

            $periode = explode('-', $this->input->post('periode'));
            /* recherche d'un pointage existant pour ces mêmes critères */
            $pointage = $this->managerPointages->getPointage($this->input->post('personnelId'), $periode[0], $periode[1]);
            if (!empty($pointage)):
                $pointage->setPointageHTML(gzcompress($this->input->post('pointageHTML'), 5));
                $this->managerPointages->editer($pointage);
            else:

                $dataPointage = array(
                    'pointagePersonnelId' => $this->input->post('personnelId'),
                    'pointageMois' => $periode[0],
                    'pointageAnnee' => $periode[1],
                    'pointageHTML' => gzcompress($this->input->post('pointageHTML'))
                );
                $pointage = new Pointage($dataPointage);
                $this->managerPointages->ajouter($pointage);
            endif;

            echo json_encode(array('type' => 'success'));

        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
    }

    public function delPointage() {

        if ($this->form_validation->run('getPointage')):
            $pointage = $this->managerPointages->getPointageById($this->input->post('pointageId'));
            $this->managerPointages->delete($pointage);
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
    }

    public function valideHS() {

        if (!$this->form_validation->run('valideHS')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            /* on recherche une heure supplementaire deja saisie */
            $exist = $this->managerHeuresSupp->getHeuresSupp(array('hsSemaine' => $this->input->post('semaine'), 'hsAnnee' => $this->input->post('annee'), 'hsPersonnelId' => $this->input->post('personnelId')));
            if (empty($exist)):
                if ($this->input->post('heuresSupp') == 0):
                    echo json_encode(array('type' => 'error', 'message' => 'On ne peut pas enregistrer des heures supplémentaires à 0'));
                else:
                    $newData = array(
                        'hsSemaine' => $this->input->post('semaine'),
                        'hsAnnee' => $this->input->post('annee'),
                        'hsPersonnelId' => $this->input->post('personnelId'),
                        'hsNbHeuresSupp' => $this->input->post('heuresSupp')
                    );
                    $new = new HeureSupp($newData);
                    $this->managerHeuresSupp->ajouter($new);
                    echo json_encode(array('type' => 'success'));
                endif;
            else:
                if ($this->input->post('heuresSupp') == 0):
                    $this->managerHeuresSupp->delete($exist[0]);
                else:
                    $exist[0]->setHsNbHeuresSupp($this->input->post('heuresSupp'));
                    $this->managerHeuresSupp->editer($exist[0]);
                endif;
                echo json_encode(array('type' => 'success'));
            endif;

        endif;
    }

    public function deleteHS() {

        if (!$this->form_validation->run('deleteHS')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $exist = $this->managerHeuresSupp->getHeuresSupp(array('hsSemaine' => $this->input->post('semaine'), 'hsAnnee' => $this->input->post('annee'), 'hsPersonnelId' => $this->input->post('personnelId')))[0];
            if (empty($exist)):
                echo json_encode(array('type' => 'error', 'message' => 'Heures supplémentaires invalides - Suppression impossible'));
            else:
                $this->managerHeuresSupp->delete($exist);
            endif;
            echo json_encode(array('type' => 'success'));
        endif;
    }

}
