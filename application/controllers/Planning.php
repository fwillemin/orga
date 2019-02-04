<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Planning extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in()) :
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):
                echo json_encode(array('type' => 'sessionTimeout'));
            else:
                redirect('organibat/board');
            endif;
        elseif ($this->ion_auth->in_group(4)):
            redirect('light/baseRestrict');
        endif;

        /* Définition des variables de planning */
        $this->nbSemainesAvant = $this->session->userdata('parametres')['nbSemainesAvant'];
        $this->nbSemainesApres = $this->session->userdata('parametres')['nbSemainesApres'];
    }

    public function index() {
        redirect('planning/base');
    }

    /* Mise à jour des heures d'une affect */

    public function majAffects() {
        //foreach ($this->managerAffectations->getAffectations(array('a.affectationId' => $id)) as $affectation):
        foreach ($this->managerAffectations->getAffectations() as $affectation):
            $affectation->calculHeuresPlanifiees();
            $this->managerAffectations->editer($affectation);
        endforeach;
        echo 'Done';
    }

    // On lance une maj des heures pour activer les triggers de la BDD et incrementer les heures pointees
    public function majHeures($from = 0) {
        foreach ($this->managerHeures->getHeuresMaj(array(), $from) as $heure):
            $this->managerHeures->editer($heure);
        endforeach;
        echo 'Heures mises à jour des ID ' . $from . ' à ' . ($from + 4000);
    }

    public function changeMessageGlobal() {
        $parametre = $this->managerParametres->getParametres();
        $parametre->setMessageEtablissement($this->input->post('message'));
        $this->managerParametres->editer($parametre);
        /* Mise à jour de la session */
        $this->session->unset_userdata('parametres');
        $this->session->set_userdata('parametres', (array) $this->managerParametres->getParametres('array'));
        echo json_encode(array('type' => 'success'));
    }

    /* Permet la selection ou non des chantiers terminés dans le slide gauche */

    public function modAffichageTermines() {
        if (!$this->input->post('etat') || $this->input->post('etat') == 0):
            $this->session->unset_userdata('inclureTermines');
        else:
            $this->session->set_userdata('inclureTermines', 1);
        endif;
        echo json_encode(array('type' => 'success'));
    }

    private function analyseRapideActivite() {

        $affairesEnCours = $this->managerAffaires->getAffaires(array('affaireEtat' => 2, 'affaireId <>' => $this->session->userdata('affaireDiversId')));
        $affairesTermines = $this->managerAffaires->getAffaires(array('affaireEtat' => 3, 'affaireDateCloture > ' => $this->session->userdata('debutFiscale'), 'affaireId <>' => $this->session->userdata('affaireDiversId')));

        $caEncours = $caClos = $nbEncours = $nbClos = $nbHeuresPlannifiees = 0;

        if (!empty($affairesTermines)):
            foreach ($affairesTermines as $affaire):
                $nbClos++;
                $caClos += $affaire->getAffairePrix();
            endforeach;
            unset($affaire);
        endif;

        if (!empty($affairesEnCours)):
            foreach ($affairesEnCours as $affaire):
                $nbEncours++;
                $caEncours += $affaire->getAffairePrix();

                // On compte les heures plannifiées restantes pour le futur
                $affaire->hydrateChantiers();
                foreach ($affaire->getAffaireChantiers() as $chantier):
                    if ($chantier->getChantierEtat() == 1):
                        $nbHeuresPlannifiees += max(array($chantier->getChantierHeuresPlanifiees() - $chantier->getChantierHeuresPointees(), 0));
                    endif;
                endforeach;

            endforeach;
            unset($affaire);
        endif;

        if ($this->session->userdata('etablissementBaseHebdomadaire') > 0):
            $charge = round($nbHeuresPlannifiees / $this->session->userdata('etablissementBaseHebdomadaire'), 1);
        else:
            $charge = 'NC';
        endif;
        return array('nbAffairesEncours' => $nbEncours, 'caEncours' => $caEncours, 'nbAffairesCloses' => $nbClos, 'caClos' => $caClos, 'nbHeuresPlannifiees' => $nbHeuresPlannifiees, 'chargeSemaines' => $charge);
    }

    /**
     * Affiche le planning
     * @param String $debut Format YYYY-MM-DD
     */
    public function base($debut = null) {
//        log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Start : ' . date('H:i:s'));

        /* Recherche du premier jour du planning en excluant le dossier divers */
        if ($debut):
            $this->session->set_userdata('dateFocus', $debut);
        elseif ($this->session->userdata('dateFocus')):
            $debut = $this->session->userdata('dateFocus');
        else:
            $debut = date('Y-m-d');
            $this->session->set_userdata('dateFocus', $debut);
        endif;
        $premierJour = $this->own->mktimeFromInputDate($debut);
        $premierJourPlanning = $this->cal->premierJourSemaine($premierJour, $this->nbSemainesAvant);

        /* Le dernier jour du planning est le premier jour auquel on additionne
         * les semaines avant et apres la dateFocus et la derniere semaine en cours
         */
        if ($debut >= date('Y-m-d')):
            $dernierJourPlanning = null;
        else:
            $dernierJourPlanning = $premierJourPlanning + (1 + $this->nbSemainesAvant + $this->nbSemainesApres) * 604800;
        endif;

        /* Récuperation des données */
        $personnelsActifs = $this->managerPersonnels->getPersonnels(array('personnelActif' => 1), 'personnelEquipeId DESC, personnelNom, personnelPrenom ASC');
        $listePersonnel = array();

        /*
         * On ne prend le personnel actif dans la liste du personnel planning que si la date de fin de planning est moins de 2 mois avant la date du jour
         * Cela permet de ne pas polluer le planning du passé avec les gars de maintenant.
         */
        if (!empty($personnelsActifs)):
            foreach ($personnelsActifs as $persoActif):
                $persoActif->hydrateEquipe();
                $persoActif->hydrateHoraire();
                if (!$dernierJourPlanning || $dernierJourPlanning >= (time() - 5184000)):
                    $listePersonnel[] = $persoActif->getPersonnelId();
                endif;
            endforeach;
        endif;

//        log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Creation des personnels actifs : ' . date('H:i:s'));

        if (!empty($personnelsActifs)):

            /* Affectations */
            $affectations = $this->managerAffectations->getAffectationsPlanning($premierJourPlanning, $dernierJourPlanning, ($this->session->userdata('inclureTermines') ? 2 : 1), 'a.affectationDebutDate ASC');
            /* On détermine un nouveau jour de début de planning avec le début le plus ancien des affectations selectionnées.
             * Pas de semaine avant cette date, le retour dans le passé est pris en compte dans la selection des affectations
             */
            $listeAffairesClotureesPlanning = array(); /* Liste des ID des affaires cloturées ayant une affectation dans le planning généré */
            /* Initialisations */
            //$dernier = $dernierJourPlanning ?: ($premierJourPlanning + (86400 * 7 * ($this->nbSemainesAvant + $this->nbSemainesApres)));
            $dernier = $dernierJourPlanning;

//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Affectations récupérées : ' . date('H:i:s'));

            if (!empty($affectations)):

                foreach ($affectations as $affectation):

                    /* Dernier jour du planning */
                    if ($affectation->getAffectationFinDate() > $dernier):
                        $dernier = $affectation->getAffectationFinDate();
                    endif;
                    /* Liste du personnel non actif mais présent dans des affectations de la période, ajouté aux personnels actifs. */
                    if (!in_array($affectation->getAffectationPersonnelId(), $listePersonnel)):
                        $listePersonnel[] = $affectation->getAffectationPersonnelId();
                    endif;

                    /* Recupération des affaires cloturées apparaissant sur la planning */
                    if ($this->session->userdata('inclureTermines')):
                        if ($affectation->getAffectationChantierEtat() == 2 && !in_array($affectation->getAffectationAffaireId(), $listeAffairesClotureesPlanning)):
                            $listeAffairesClotureesPlanning[] = $affectation->getAffectationAffaireId();
                        endif;
                    endif;

                endforeach;
                unset($affectation);

                /* Mise à jour des variables du planning */
                if ($dernierJourPlanning):
                    /* Plannning contraint par le calendrier (dateFocus < today) */
                    $dernierJourPlanning = $this->cal->dernierJourSemaine($dernier);
                else:
                    /* Planning libre sur le futur (dateFocus >= today) on ajoute 2 semaines libres */
                    $dernierJourPlanning = $this->cal->dernierJourSemaine($dernier) + 86400 * 14;
                endif;
            else:
                $dernierJourPlanning += $premierJourPlanning + (1 + $this->nbSemainesAvant + $this->nbSemainesApres) * 604800;
            endif;

//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Fin du traitement des affectations : ' . date('H:i:s'));

            $this->session->set_userdata('planningPersonnelsIds', $listePersonnel);

            /* Affaires du planning (toutes les non cloturées et les cloturées ayant une affectation sur le planning généré) */
            $affairesPlanning = $this->managerAffaires->getAffairesPlanning($listeAffairesClotureesPlanning);
            if (!empty($affairesPlanning)):
                foreach ($affairesPlanning as $affaire):
                    $affaire->hydrateChantiers();
                    $affaire->hydrateClient();
                endforeach;
            endif;

//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Fin de la récupération des affaires : ' . date('H:i:s'));

            /* recherche des indisponibilités pour cette periode */
            $indisponibilitesPlanning = $this->managerIndisponibilites->getIndisponibilitesPlanning($premierJourPlanning, $dernierJourPlanning);
            if (!empty($indisponibilitesPlanning)):
                foreach ($indisponibilitesPlanning as $indispo):
                    if (!in_array($indispo->getIndispoPersonnelId(), $listePersonnel)):
                        $listePersonnel[] = $indispo->getIndispoPersonnelId();
                    endif;
                endforeach;
            endif;
//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Fin de la récupération des Indispo : ' . date('H:i:s'));

            /* Personnels du planning (Actifs et les inactifs associés à une affectation du planning généré) */
            $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($listePersonnel);
            if (!empty($personnelsPlanning)):
                foreach ($personnelsPlanning as $personnel):
                    $personnel->hydrateEquipe();
                endforeach;
            endif;

//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Fin de la récupération du personnel de planning : ' . date('H:i:s'));

            if ($affectations):
                foreach ($affectations as $affectation):
                    $affectation->getHTML($premierJourPlanning, $personnelsPlanning, null, $this->hauteur, $this->largeur);
                endforeach;
                unset($affectation);
            endif;

//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Génaration des HTML affectations ' . date('H:i:s'));

            /* Passage du premier jour du planning en variable de session */
            $this->session->set_userdata('premierJourPlanning', $premierJourPlanning);
            $this->session->set_userdata('dernierJourPlanning', $dernierJourPlanning);


            if (!empty($indisponibilitesPlanning)):
                foreach ($indisponibilitesPlanning as $indispo):
                    $indispo->genereHTML($premierJourPlanning, $personnelsPlanning, null, $this->hauteur, $this->largeur);
                endforeach;
            endif;

//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Génaration des HTML Indispo ' . date('H:i:s'));

            /* les chantiers */
//            $chantiers = $this->managerChantiers->getChantiers(array('chantierEtat' => 1));
//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => On a les chantiers !! : ' . date('H:i:s'));
//            if (!empty($chantiers)):
//                foreach ($chantiers as $chantier):
//                    $chantier->hydrateClient();
//                endforeach;
//                unset($chantier);
//            endif;
//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Recupération des chantier ' . date('H:i:s'));

            /* le planning va s'afficher sur N semaines */
            $n = ceil(($dernierJourPlanning - $premierJourPlanning) / 604800);

            /* Les livraisons sont des instances d'objet Achat */
            $achats = $this->managerAchats->getAchatsPlanning($premierJourPlanning, $dernierJourPlanning);
            if (!empty($achats)):
                foreach ($achats as $achat):
                    $achat->hydrateChantier();
                    $achat->hydrateFournisseur();
                    $achat->genereHTML();
                endforeach;
            endif;

//            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Génaration desAchats ' . date('H:i:s'));

        else:
            // Personne sur le planning ( pas de personnel créé ou tout le personnel en inactif)
            $affairesPlanning = $indisponibilitesPlanning = $affectations = $achats = $personnelsPlanning = $n = null;

        endif;

//        log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . '-----------------');

        $data = array(
            'section' => 'planning',
            'hauteur' => $this->hauteur,
            'largeur' => $this->largeur,
            'today' => floor((time() - $premierJourPlanning) / 86400) * ($this->largeur * 2),
            'msg' => $this->nbSemainesApres = $this->session->userdata('parametres')['messageEtablissement'],
            /* Affaires & chantiers */
            'affairesPlanning' => $affairesPlanning,
            /* Personnels */
            'personnelsActifs' => $personnelsActifs, /* Pour la sélection dans le formulaire d'ajout d'affectations */
            'personnelsPlanning' => $personnelsPlanning, /* Personnels affichés sur le planning */
            'indisposPlanning' => $indisponibilitesPlanning,
            'motifs' => $this->managerMotifs->getMotifs(),
            'affectationsPlanning' => $affectations,
            'achatsPlanning' => $achats,
            'fournisseurs' => $this->managerFournisseurs->getFournisseurs(),
            'dateFocus' => $debut, /* Date à partir de laquelle tout est calculé */
            'premierJourPlanning' => $premierJourPlanning,
            'dernierJourPlanning' => $dernierJourPlanning,
            'nbSemainesPlanning' => $n,
            'analyseRapide' => $this->analyseRapideActivite(),
            'title' => $this->session->userdata('rs') . '|Planning',
            'description' => 'Planning de votre activité',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    /* Passe l'affectation à un affichage FULL, BAS, HAUT */

    public function affectationToggleAffichage() {
        if (!$this->form_validation->run('getAffectation')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $affectation = $this->managerAffectations->getAffectationPlanningById($this->input->post('affectationId'));
            //$affectation->hydrateOrigines();
            $affectation->toggleAffichage();
            $affectation->getHTML($this->session->userdata('premierJourPlanning'), array(), $this->input->post('ligne'), $this->hauteur, $this->largeur);
            $this->managerAffectations->editer($affectation);
            echo json_encode(array('type' => 'success', 'html' => $affectation->getAffectationHTML()));
        endif;
    }

    public function addAffectation() {
        if (!$this->ion_auth->in_group(60)):
            echo json_encode(array('type' => 'error', 'message' => 'Vous ne possédez pas les droits necessaires'));
        elseif (!$this->form_validation->run('addAffectation')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        elseif (($this->input->post('addAffectationDebutDate') > $this->input->post('addAffectationFinDate')) || ($this->input->post('addAffectationDebutDate') == $this->input->post('addAffectationFinDate') && $this->input->post('addAffectationDebutMoment') > $this->input->post('addAffectationFinMoment'))):
            echo json_encode(array('type' => 'error', 'message' => 'La date de début ne peut pas être supérieure à la date de fin.'));
        else:

            /* Personnels du planning */
            $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($this->session->userdata('planningPersonnelsIds'));
            if (!empty($personnelsPlanning)):
                foreach ($personnelsPlanning as $personnel):
                    $personnel->hydrateEquipe();
                endforeach;
            endif;

            $chantier = $this->managerChantiers->getChantierById($this->input->post('addAffectationChantierId'));
            $html = '';

            if ($this->input->post('addAffectationId')):

                $affectation = $this->managerAffectations->getAffectationById($this->input->post('addAffectationId'));
                $affectation->hydrateChantier();

                if ($affectation->getAffectationChantier()->getChantierEtat() == 2):
                    echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier une affectation dont le chantier est "Cloturé"'));
                    exit;
                endif;

                $affectation->hydrateHeures();

                /* On ne peut changer le personnel d'une affectation si elle a des heures */
                if (empty($affectation->getAffectationHeures())):
                    $affectation->setAffectationPersonnelId($this->input->post('addAffectationPersonnelsIds')[0]);
                endif;

                $affectation->setAffectationType($this->input->post('addAffectationType'));
                $affectation->setAffectationCommentaire($this->input->post('addAffectationCommentaire'));
                $affectation->setAffectationChantierId($this->input->post('addAffectationChantierId'));
                $affectation->setAffectationPlaceId($chantier->getChantierPlaceId());
                $affectation->setAffectationDebutDate($this->own->mktimeFromInputDate($this->input->post('addAffectationDebutDate')));
                $affectation->setAffectationDebutMoment($this->input->post('addAffectationDebutMoment'));
                $affectation->setAffectationFinDate($this->own->mktimeFromInputDate($this->input->post('addAffectationFinDate')));
                $affectation->setAffectationFinMoment($this->input->post('addAffectationFinMoment'));
                $affectation->setAffectationNbDemi($this->input->post('addAffectationNbDemi'));
                $affectation->setAffectationCases($this->own->nbCasesAffectation($affectation));
                $affectation->calculHeuresPlanifiees();
                $this->managerAffectations->editer($affectation);
                unset($affectation);
                $affectation = $this->managerAffectations->getAffectationPlanningById($this->input->post('addAffectationId'));
                $affectation->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                $html .= $affectation->getAffectationHTML();

            else:

                foreach ($this->input->post('addAffectationPersonnelsIds') as $key => $personnelId):

                    $dataAffectation = array(
                        'affectationOriginId' => null,
                        'affectationChantierId' => $this->input->post('addAffectationChantierId'),
                        'affectationPersonnelId' => $personnelId,
                        'affectationPlaceId' => $chantier->getChantierPlaceId(),
                        'affectationNbDemi' => $this->input->post('addAffectationNbDemi'),
                        'affectationDebutDate' => $this->own->mktimeFromInputDate($this->input->post('addAffectationDebutDate')),
                        'affectationDebutMoment' => $this->input->post('addAffectationDebutMoment'),
                        'affectationFinDate' => $this->own->mktimeFromInputDate($this->input->post('addAffectationFinDate')),
                        'affectationFinMoment' => $this->input->post('addAffectationFinMoment'),
                        'affectationCases' => 0,
                        'affectationCommentaire' => $this->input->post('addAffectationCommentaire'),
                        'affectationType' => $this->input->post('addAffectationType'),
                        'affectationAffichage' => 1
                    );

                    $affectation = new Affectation($dataAffectation);
                    $affectation->setAffectationCases($this->own->nbCasesAffectation($affectation));
                    $affectation->calculHeuresPlanifiees();
                    $this->managerAffectations->ajouter($affectation);
                    $affectation->hydratePlanning();
                    $affectation->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                    $html .= $affectation->getAffectationHTML();

                endforeach;

            endif;

            echo json_encode(array('type' => 'success', 'HTML' => $html));

        endif;
    }

    public function getAffectationDetails() {

        if (!$this->form_validation->run('getAffectation')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $affectation = $this->managerAffectations->getAffectationById($this->input->post('affectationId'));
            $affectation->hydrateOrigines();
            $affectation->hydratePersonnel();
            $affectation->getAffectationChantier()->hydratePlace();

            $affaire = array(
                'affaireId' => $affectation->getAffectationAffaire()->getAffaireId(),
                'affaireObjet' => $affectation->getAffectationAffaire()->getAffaireObjet(),
                'affaireRemarque' => $affectation->getAffectationAffaire()->getAffaireRemarque()
            );
            if ($affectation->getAffectationChantier()->getChantierHeuresPrevues() > 0):
                $ratio = floor($affectation->getAffectationChantier()->getChantierHeuresPlanifiees() * 100 / $affectation->getAffectationChantier()->getChantierHeuresPrevues());
            else:
                $ratio = 0;
            endif;
            if ($ratio > 100):
                $bgClass = "progress-bar bg-danger";
                $ratio = 100;
                $barText = 'Dépassement ';
            elseif ($ratio > 75):
                $bgClass = "progress-bar bg-warning";
                $barText = 'Reste ';
            else:
                $bgClass = "progress-bar bg-info";
                $barText = 'Reste ';
            endif;

            $chantier = array(
                'chantierId' => $affectation->getAffectationChantier()->getChantierId(),
                'chantierObjet' => $affectation->getAffectationChantier()->getChantierObjet(),
                'chantierPlace' => !empty($affectation->getAffectationChantier()->getChantierPlace()) ? $affectation->getAffectationChantier()->getChantierPlace()->getPlaceAdresse() : '',
                'chantierRemarque' => $affectation->getAffectationChantier()->getChantierRemarque(),
                'chantierRatio' => $ratio,
                'chantierEtat' => $affectation->getAffectationChantier()->getChantierEtat(),
                'chantierProgressBar' => $bgClass,
                'chantierProgressBarText' => $barText . abs($affectation->getAffectationChantier()->getChantierHeuresPrevues() - $affectation->getAffectationChantier()->getChantierHeuresPlanifiees()) . ' heures'
            );

            $client = array(
                'clientId' => $affectation->getAffectationClient()->getClientId(),
                'clientNom' => $affectation->getAffectationClient()->getClientNom(),
                'clientVille' => $affectation->getAffectationClient()->getClientVille(),
                'clientPortable' => $affectation->getAffectationClient()->getClientPortable()
            );

            if ($affectation->getAffectationDebutDate() == $affectation->getAffectationFinDate() && $affectation->getAffectationDebutMoment() == $affectation->getAffectationFinMoment()):
                $periode = 'le ' . $this->cal->dateFrancais($affectation->getAffectationDebutDate()) . ' ' . $affectation->getAffectationDebutMomentText();
            else:
                $periode = 'du ' . $this->cal->dateFrancais($affectation->getAffectationDebutDate(), 'JDM') . ' ' . $affectation->getAffectationDebutMomentText() . ' au ' . $this->cal->dateFrancais($affectation->getAffectationFinDate()) . ' ' . $affectation->getAffectationFinMomentText();
            endif;

            $affect = array(
                'affectationId' => $affectation->getAffectationId(),
                'affectationDebutDate' => $affectation->getAffectationDebutDate(),
                'affectationDebutMoment' => $affectation->getAffectationDebutMoment(),
                'affectationFinDate' => $affectation->getAffectationFinDate(),
                'affectationFinMoment' => $affectation->getAffectationFinMoment(),
                'affectationPersonnelId' => $affectation->getAffectationPersonnelId(),
                'affectationChantierId' => $affectation->getAffectationChantierId(),
                'affectationNbDemi' => $affectation->getAffectationNbDemi(),
                'affectationTypeText' => $affectation->getAffectationTypeText(),
                'affectationType' => $affectation->getAffectationType(),
                'affectationCommentaire' => nl2br($affectation->getAffectationCommentaire()),
                'affectationPeriode' => $periode,
                'affectationHeuresPlanifiees' => $affectation->getAffectationHeuresPlanifiees()
            );
            $personnel = array(
                'personnelId' => $affectation->getAffectationPersonnel()->getPersonnelId(),
                'personnelNom' => $affectation->getAffectationPersonnel()->getPersonnelNom() . ' ' . $affectation->getAffectationPersonnel()->getPersonnelPrenom()
            );

            $affectation->hydrateHeures();
            if (!empty($affectation->getAffectationHeures())):
                foreach ($affectation->getAffectationHeures() as $heure):
                    $heures[] = array('heureDate' => $this->cal->dateFrancais($heure->getHeureDate(), 'jDM'), 'heureDuree' => floor($heure->getHeureDuree() / 60) . 'h ' . $heure->getHeureDuree() % 60 . '', 'heureValide' => $heure->getHeureValide());
                endforeach;
            else:
                $heures = array();
            endif;

            echo json_encode(array('type' => 'success', 'affectation' => $affect, 'affaire' => $affaire, 'chantier' => $chantier, 'client' => $client, 'personnel' => $personnel, 'heures' => $heures));
        endif;
    }

    public function delAffectation() {
        if (!$this->ion_auth->in_group(60)):
            echo json_encode(array('type' => 'error', 'message' => 'Vous ne possédez pas les droits necessaires'));
        elseif (!$this->form_validation->run('getAffectation')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $affectation = $this->managerAffectations->getAffectationById($this->input->post('affectationId'));
            $affectation->hydrateChantier();

            if ($affectation->getAffectationChantier()->getChantierEtat() == 2):
                echo json_encode(array('type' => 'error', 'message' => 'Impossible de supprimer une affectation dont le chantier est "Cloturé"'));
            else:
                $this->managerAffectations->delete($affectation);
                echo json_encode(array('type' => 'success'));
            endif;
        endif;
    }

    public function resizeAffectation() {
        if (!$this->form_validation->run('getAffectation')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $affectation = $this->managerAffectations->getAffectationPlanningById($this->input->post('affectationId'));
            $affectation->hydrateChantier();
            if ($affectation->getAffectationChantier()->getChantierEtat() == 2):
                echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier une affectation d\'un chantier clôturé.'));
            else:

                /* Personnels du planning */
                $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($this->session->userdata('planningPersonnelsIds'));
                if (!empty($personnelsPlanning)):
                    foreach ($personnelsPlanning as $personnel):
                        $personnel->hydrateEquipe();
                    endforeach;
                endif;

                /* On recherche la dernière heure saisie sur cette affectation et donc la date minimum de fin d'affectation */
                $affectation->hydrateHeures();
                if (empty($affectation->getAffectationHeures())):
                    $limite = $affectation->getAffectationDebutDate();
                else:
                    $limite = $affectation->getAffectationHeures()[sizeof($affectation->getAffectationHeures()) - 1]->getHeureDate();
                endif;

                $newDateFin = $this->cal->calculeDateFinCases($affectation->getAffectationDebutDate(), $affectation->getAffectationDebutMoment(), $this->input->post('nbCases'));

                if ($newDateFin < $limite):
                    $affectation->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                    echo json_encode(array('type' => 'error', 'message' => 'Il y a des heures saisies le <b>' . $this->cal->dateFrancais($limite, 'JDM') . '</b> donc hors des nouvelles limites de cette affectation.<br>Redimensionnement impossible', 'html' => $affectation->getAffectationHTML()));
                else:
                    if ($this->input->post('nbCases') % 2 == 1):
                        $newMomentFin = $affectation->getAffectationDebutMoment();
                    elseif ($affectation->getAffectationDebutMoment() == 1):
                        $newMomentFin = 2;
                    else:
                        $newMomentFin = 1;
                    endif;
                    $newNbDemi = $this->cal->nbDemiEntreDates($affectation->getAffectationDebutDate(), $affectation->getAffectationDebutMoment(), $newDateFin, $newMomentFin);

                    $affectation->setAffectationNbDemi($newNbDemi);
                    $affectation->setAffectationFinDate($newDateFin);
                    $affectation->setAffectationFinMoment($newMomentFin);
                    $affectation->setAffectationCases($this->cal->nbCasesEntreDates($affectation->getAffectationDebutDate(), $affectation->getAffectationDebutMoment(), $affectation->getAffectationFinDate(), $affectation->getAffectationFinMoment()));
                    $affectation->calculHeuresPlanifiees();
                    $this->managerAffectations->editer($affectation);

                    $affectation->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                    echo json_encode(array('type' => 'success', 'html' => $affectation->getAffectationHTML()));

                endif;
            endif;
        endif;
    }

    public function dragAffectation() {
        if (!$this->form_validation->run('getAffectation')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $affectation = $this->managerAffectations->getAffectationPlanningById($this->input->post('affectationId'));
            $affectation->hydrateChantier();
            $affectation->hydrateHeures();
            if ($affectation->getAffectationChantier()->getChantierEtat() == 2 || !empty($affectation->getAffectationHeures())):
                echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier une affectation d\'un chantier clôturé ou ayant des heures de validées.'));
            else:

                /* Personnels du planning */
                $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($this->session->userdata('planningPersonnelsIds'));
                if (!empty($personnelsPlanning)):
                    foreach ($personnelsPlanning as $personnel):
                        $personnel->hydrateEquipe();
                    endforeach;
                endif;
                $affectationOriginaleHTML = $affectation->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);

                if (!$this->ion_auth->in_group(60)):
                    $affectation->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                    echo json_encode(array('type' => 'error', 'message' => 'Vous n\'avez pas les droits pour modifier une affectation', 'html' => $affectationOriginaleHTML));
                else:

                    $decalageLigne = floor($this->input->post('decalageY') / $this->hauteur);
                    $decalageNbDemi = floor($this->input->post('decalageX') / ($this->largeur + 1));

                    $affectation->hydrateHeures();
                    /* On ne peut changer de personnel que si aucune heure n'est saisie sur cette affectation */
                    if (empty($affectation->getAffectationHeures()) && $this->input->post('decalageY') != 0):

                        $nouvelleLigne = $this->input->post('ligne') + $decalageLigne;
                        if ($nouvelleLigne > 0 && $nouvelleLigne <= sizeof($personnelsPlanning)):

                            $affectation->setAffectationPersonnelId($personnelsPlanning[$nouvelleLigne - 1]->getPersonnelId());

                        else:

                            echo json_encode(array('type' => 'error', 'message' => 'Vous devez rester dans le planning', 'html' => $affectationOriginaleHTML));
                        endif;

                    endif;

                    /* On gère les changements de date */
                    $nouvellesDonnees = $this->cal->decalageNbDemi($affectation, $decalageNbDemi);
                    if ($nouvellesDonnees['debutDate'] < $this->session->userdata('premierJourPlanning') || $nouvellesDonnees['debutDate'] > $this->session->userdata('dernierJourPlanning')):
                        echo json_encode(array('type' => 'error', 'message' => 'Vous devez rester dans le planning', 'html' => $affectationOriginaleHTML));
                    else:

                        $affectation->setAffectationDebutDate($nouvellesDonnees['debutDate']);
                        $affectation->setAffectationDebutMoment($nouvellesDonnees['debutMoment']);
                        $affectation->setAffectationFinDate($nouvellesDonnees['finDate']);
                        $affectation->setAffectationFinMoment($nouvellesDonnees['finMoment']);
                        $affectation->setAffectationCases($nouvellesDonnees['cases']);
                        $affectation->calculHeuresPlanifiees();
                        $this->managerAffectations->editer($affectation);

                        $affectation->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                        echo json_encode(array('type' => 'success', 'html' => $affectation->getAffectationHtml()));

                    endif;

                endif;
            endif;
        endif;
    }

    public function returnModalLivraion() {

        if (!$this->form_validation->run('getAchat')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $achat = $this->managerAchats->getAchatById($this->input->post('achatId'));
            $chantier = $this->managerChantiers->getChantierById($achat->getAchatChantierId());
            $chantier->hydrateClient();

            $retour = $this->generationModalAchatPlanning($achat);


            echo json_encode(array('type' => 'success', 'contraintes' => $retour, 'titre' => 'Livraison d\'un achat pour le client ' . $chantier->getChantierClient()->getClientNom()));
        endif;
    }

    public function saveContraintes() {
        if (!$this->form_validation->run('getAchat')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            /* Suppression de toutes les contraintes de cet achat */
            $this->db->where('achatId', $this->input->post('achatId'))
                    ->delete('achats_affectations');

            if (!empty($this->input->post('contraintes'))):
                foreach ($this->input->post('contraintes') as $affectationId):
                    $this->db
                            ->set('achatId', $this->input->post('achatId'))
                            ->set('affectationId', $affectationId)
                            ->insert('achats_affectations');
                endforeach;
            endif;

            $achat = $this->managerAchats->getAchatById($this->input->post('achatId'));
            $achat->genereHTML();

            /* On regénère la modal pour le planning */
            $retourModal = $this->generationModalAchatPlanning($this->managerAchats->getAchatById($this->input->post('achatId')));
            echo json_encode(array('type' => 'success', 'contraintes' => $retourModal, 'achatHTML' => $achat->getAchatHTML()));

        endif;
    }

    private function generationModalAchatPlanning(Achat $achat) {
        $achat->hydrateFournisseur();

        $chantier = $this->managerChantiers->getChantierById($achat->getAchatChantierId());
        $chantier->hydrateAffectations();
        $chantier->hydrateClient();

        $entete = (!empty($achat->getAchatFournisseur()) ? '<small>Fournisseur : </small>' . $achat->getAchatFournisseur()->getFournisseurNom() . '<br>' : '')
                . '<div><small>Avancement : </small>' . str_replace('"', '\'', $achat->getAchatLivraisonAvancementText()) . '</div>'
                . '<small>Achat : </small>' . nl2br($achat->getAchatDescription())
                . '<a href=\'' . site_url('chantiers/ficheChantier/' . $achat->getAchatChantierId() . '/a' . $achat->getAchatId()) . '\' class=\'btn btn-outline-dark btn-sm\' style=\'position:absolute; right:3px; top: 2px;\'><i class=\'fas fa-edit\' style=\'position:relative; top:1px; left:1px;\'></i></a><hr>';


        $contraintes = '';
        if (sizeof($achat->getAchatContraintesIds()) > 0):
            $contraintes = 'Cette livraison est nécessaire pour <b>' . sizeof($achat->getAchatContraintesIds()) . ' affectation(s)</b> :';
            foreach ($achat->getAchatContraintesIds() as $affectationContrainteId):
                foreach ($chantier->getChantierAffectations() as $affectation):
                    if ($affectation->getAffectationId() == $affectationContrainteId):
                        $affectation->hydratePersonnel();
                        $contraintes .= "<div class='row' style='margin: 3px; border: 1px solid " . $chantier->getChantierCouleur() . ";'>"
                                . "<div class='col-1' style='text-align: center; background-color:" . $chantier->getChantierCouleur() . "; color:" . $chantier->getChantierCouleurSecondaire() . "; padding:5px;'>"
                                . "<i class='fas fa-check'></i>"
                                . "</div>"
                                . "<div class='col' style='padding:10px 5px 5px 10px; font-size:14px;'>"
                                . "Le " . $this->cal->dateFrancais($affectation->getAffectationDebutDate(), 'JDMA') . " pour <b>" . $affectation->getAffectationPersonnel()->getPersonnelNom() . "</b>"
                                . "</div>"
                                . "</div>";
                        break;
                    endif;
                endforeach;
                unset($affectation);
            endforeach;
        endif;
        /* On ajoute la liste des chantiers des affectations de ce chantier pour selection des contraites */
        $contraintes .= "<br><select name='selectionContraintes[]' class='selectpicker col-10' id='selectionContraintes' multiple title='Achat nécessaire pour...' data-style='btn-secondary' >";
        if (!empty($chantier->getChantierAffectations())):
            foreach ($chantier->getChantierAffectations() as $affectation):
                $affectation->hydratePersonnel();
                $isSelect = '';
                if (in_array($affectation->getAffectationId(), $achat->getAchatContraintesIds())):
                    $isSelect = 'selected';
                endif;
                $contraintes .= "<option " . $isSelect . " value='" . $affectation->getAffectationId() . "' data-content='Le " . $this->cal->dateFrancais($affectation->getAffectationDebutDate(), 'JDma') . " avec " . $affectation->getAffectationPersonnel()->getPersonnelNom() . " pour " . $affectation->getAffectationNbDemi() . " demi-journée(s)'>" . $affectation->getAffectationId() . "</option>";
            endforeach;
        endif;
        $contraintes .= "</select><button class='btn btn-dark' id='btnSaveContraintes' data-achatid='" . $achat->getAchatId() . "'><i class='fas fa-save'></i></button>";

        return $entete . $contraintes;
    }

    public function addDateLivraison() {

        if (!$this->existChantier($this->input->post('addLivraisonChantierId')) || !$this->existAchat($this->input->post('addLivraisonAchatId'))):
            echo json_encode(array('type' => 'error', 'message' => 'Cet achat et/ou le chantier liés ne sont pas valables'));
        else:
            $achat = $this->managerAchats->getAchatById($this->input->post('addLivraisonAchatId'));
            $achat->setAchatLivraisonDate($this->own->mktimeFromInputDate($this->input->post('addLivraisonDate')));
            $achat->setAchatLivraisonAvancement($this->input->post('addLivraisonAvancement'));

            $this->managerAchats->editer($achat);
            $achat->genereHTML();

            echo json_encode(array('type' => 'success', 'achatHTML' => $achat->getAchatHTML()));
        endif;
    }

    public function dragLivraison() {
        if (!$this->form_validation->run('getAchat')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $achat = $this->managerAchats->getAchatById($this->input->post('achatId'));
            if ($this->input->post('decalageX') > 0):
                $newDate = $achat->getAchatLivraisonDate() + floor($this->input->post('decalageX') / (2 * $this->largeur)) * 86400;
            else:
                $newDate = $achat->getAchatLivraisonDate() + ceil($this->input->post('decalageX') / (2 * $this->largeur)) * 86400;
            endif;
            $achat->setAchatLivraisonDate($newDate);
            $this->managerAchats->editer($achat);
            echo json_encode(array('type' => 'success'));
        endif;
    }

    public function couperAffectation() {
        if (!$this->form_validation->run('getAffectation')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            $affectation = $this->managerAffectations->getAffectationPlanningById($this->input->post('affectationId'));
            $affectation->hydrateChantier();
            if ($affectation->getAffectationChantier()->getChantierEtat() == 2):
                echo json_encode(array('type' => 'error', 'message' => 'Impossible de modifier une affectation d\'un chantier clôturé.'));
            else:

                $affectation->hydrateHeures();
                $dateCoupure = $this->own->mktimeFromInputDate($this->input->post('couperDate'));
                if ($dateCoupure < $affectation->getAffectationDebutDate() || $dateCoupure > $affectation->getAffectationFinDate() || ($dateCoupure == $affectation->getAffectationDebutDate() && $this->input->post('couperMoment') < $affectation->getAffectationDebutMoment()) || ($dateCoupure == $affectation->getAffectationFinDate() && $this->input->post('couperMoment') >= $affectation->getAffectationFinMoment())):
                    echo json_encode(array('type' => 'error', 'message' => 'Vous devez selectionner une date de coupure incluse dans la durée de l\'affectation !'));
                else:

                    $newAffectation = clone $affectation;

                    $this->db->trans_start();

                    /* Modification de l'affectation coupée */
                    $affectation->setAffectationFinDate($dateCoupure);
                    $affectation->setAffectationFinMoment($this->input->post('couperMoment'));
                    $affectation->setAffectationNbDemi($this->cal->nbDemiEntreDates($affectation->getAffectationDebutDate(), $affectation->getAffectationDebutMoment(), $dateCoupure, $this->input->post('couperMoment')));
                    $affectation->setAffectationCases($this->cal->nbCasesEntreDates($affectation->getAffectationDebutDate(), $affectation->getAffectationDebutMoment(), $dateCoupure, $this->input->post('couperMoment')));
                    $affectation->calculHeuresPlanifiees();
                    $this->managerAffectations->editer($affectation);

                    /* création de la nouvelle affectation */
                    $newAffectation->setAffectationId('');
                    //log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($newAffectation, true));
                    if ($this->input->post('couperMoment') == 1):
                        $newDebutDate = $dateCoupure;
                        $newDebutMoment = 2;
                    else:
                        $newDebutDate = $dateCoupure + 86400;
                        $newDebutMoment = 1;
                    endif;


                    $newAffectation->setAffectationDebutDate($newDebutDate);
                    $newAffectation->setAffectationDebutMoment($newDebutMoment);
                    $newAffectation->setAffectationNbDemi($this->cal->nbDemiEntreDates($newDebutDate, $newDebutMoment, $newAffectation->getAffectationFinDate(), $newAffectation->getAffectationFinMoment()));
                    $newAffectation->setAffectationCases($this->cal->nbCasesEntreDates($newDebutDate, $newDebutMoment, $newAffectation->getAffectationFinDate(), $newAffectation->getAffectationFinMoment()));
                    $affectation->calculHeuresPlanifiees();
                    $this->managerAffectations->ajouter($newAffectation);

                    /* Migration des heures vers la nouvelle affectation */
                    if (!empty($affectation->getAffectationHeures())):
                        foreach ($affectation->getAffectationHeures() as $heure):
                            if ($heure->getHeureDate() > $affectation->getAffectationFinDate()):
                                /* On passe les heures sur la nouvelle affectation */
                                $heure->setHeureAffectationId($newAffectation->getAffectationId());
                                $this->managerHeures->editer($heure);
                            endif;
                        endforeach;
                    endif;

                    $this->db->trans_complete();

                    /* Personnels du planning */
                    $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($this->session->userdata('planningPersonnelsIds'));
                    if (!empty($personnelsPlanning)):
                        foreach ($personnelsPlanning as $personnel):
                            $personnel->hydrateEquipe();
                        endforeach;
                    endif;

                    $affectation->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                    $newAffectation->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);

                    echo json_encode(array('type' => 'success', 'HTML' => $affectation->getAffectationHTML() . $newAffectation->getAffectationHTML()));

                endif;
            endif;

        endif;
    }

    public function deplaceAffectation() {
        if (!$this->form_validation->run('getAffectation')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:

            /* Personnels du planning */
            $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($this->session->userdata('planningPersonnelsIds'));
            if (!empty($personnelsPlanning)):
                foreach ($personnelsPlanning as $personnel):
                    $personnel->hydrateEquipe();
                endforeach;
            endif;

            $affectation = $this->managerAffectations->getAffectationById($this->input->post('affectationId'));
            if ($this->input->post('cible') == 1):
                $affectations = $this->managerAffectations->getAffectations(array('affectationId' => $affectation->getAffectationId()));
            else:
                $affectations = $this->managerAffectations->getAffectations(array('affectationPersonnelId' => $affectation->getAffectationPersonnelId(), 'affectationDebutDate >=' => $affectation->getAffectationDebutDate()));
            endif;

            $eraseIds = array();
            $HTML = '';
            $repercutionDebutDate = null;
            $nbDemiDecalage = $this->input->post('decalage') * 2;

            $this->db->trans_start();

            foreach ($affectations as $affect):
                $affect->hydrateHeures();
                $affect->hydrateChantier();
                if (!empty($affect->getAffectationHeures()) || $affect->getAffectationChantier()->getChantierEtat() == 2):
                    continue;
                else:
                    $eraseIds[] = $affect->getAffectationId();
                    $infosDecalage = $this->cal->decalageNbDemi($affect, $nbDemiDecalage);
                    $affect->setAffectationDebutDate($infosDecalage['debutDate']);
                    $affect->setAffectationFinDate($infosDecalage['finDate']);
                    $affect->setAffectationCases($infosDecalage['cases']);
                    $affectation->calculHeuresPlanifiees();
                    $this->managerAffectations->editer($affect);
                    $affect->hydratePlanning();
                    $affect->getHTML($this->session->userdata('premierJourPlanning'), $personnelsPlanning, null, $this->hauteur, $this->largeur);
                    $HTML .= $affect->getAffectationHTML();

                    $repercutionDebutDate = $affect->getAffectationFinDate();

                endif;
            endforeach;

            $this->db->trans_complete();
            echo json_encode(array('type' => 'success', 'HTML' => $HTML, 'eraseIds' => $eraseIds));
        endif;
    }

    /**
     * Retourne les données d'une affectation en fonction d'un horaire, d'un debut et d'un nombre d'heures à planifier
     * @param Horaire $horaire ID Horaire
     * @param int $debutDate Timestamp
     * @param [1,2] $debutMoment Matin ou Aprem
     * @return Array
     */
    private function calculAffectationFinFromHeures(Horaire $horaire = null, $debutDate, $debutMoment, $nbHeures) {

        $decompte = 0;
        $dateTraitee = $debutDate;

        // Décompte du premier jour
        $jour = trim($this->cal->dateFrancais($dateTraitee, 'j'));
        if ($debutMoment == 1):
            /* On decompte les heures complètes du jour dans l'horaire */
            $decompte += ($horaire ? $horaire->{'getHoraire' . $jour}() : 3.5);
        else:
            /* On decompte les heures de l'aprem dans l'horaire */
            $decompte += ($horaire ? $horaire->{'getHoraire' . $jour . 'PM'}() : 3.5);
        endif;


        while ($decompte < $nbHeures):
            $dateTraitee += 86400;
            $jour = trim($this->cal->dateFrancais($dateTraitee, 'j'));
            $decompte += ($horaire ? $horaire->{'getHoraire' . $jour}() : 7);

            if ($decompte > $nbHeures):
                $depassement = $decompte - $nbHeures;
                // recherche des heures d'aprem du jour traite
                $heuresAprem = ($horaire ? $horaire->{'getHoraire' . $jour . 'PM'}() : 3.5);
                if (($depassement - $heuresAprem) < 0):
                    $finMoment = 2;
                else:
                    $finMoment = 1;
                endif;
            endif;
        endwhile;
        $finDate = $dateTraitee;

        return [$finDate, $finMoment];
    }

    /* Retourne au planning le nombre d'heures pour la saisie en cours dans le fichier formAffectation.php */

    public function getNbHeuresFormAffectation() {
        if ($this->form_validation->run('getPersonnel')):
            $personnel = $this->managerPersonnels->getPersonnelById($this->input->post('personnelId'));
            $personnel->hydrateHoraire();

            /* On créé un objet Affectation pour avoir son nombre d'heures */
            $testAffect = new Affectation(array(
                'affectationPersonnelId' => $this->input->post('personnelId'),
                'affectationDebutDate' => $this->own->mktimeFromInputDate($this->input->post('debutDate')),
                'affectationDebutMoment' => $this->input->post('debutMoment'),
                'affectationFinDate' => $this->own->mktimeFromInputDate($this->input->post('finDate')),
                'affectationFinMoment' => $this->input->post('finMoment')
            ));
            $testAffect->hydratePersonnel();
            $testAffect->getAffectationPersonnel()->hydrateHoraire();

            $nbheures = $this->own->nbHeuresAffectation($testAffect);
            echo json_encode(array('type' => 'success', 'nbHeures' => $nbheures));

        else:
            echo json_encode(array('type' => 'error', 'message' => 'Personnel inexistant'));
        endif;
    }

    public function getFinAffectationWithNbHeures() {
        if ($this->form_validation->run('getPersonnel')):
            $personnel = $this->managerPersonnels->getPersonnelById($this->input->post('personnelId'));
            $personnel->hydrateHoraire();

            $infos = $this->own->finAffectationWithNbHeures(($personnel->getPersonnelHoraire() ?: null), $this->input->post('debutDate'), $this->input->post('debutMoment'), $this->input->post('nbHeures'));
            $nbDemi = $this->cal->nbDemiEntreDates($this->input->post('debutDate'), $this->input->post('debutMoment'), $infos['finDate'], $infos['finMoment']);
            echo json_encode(array('type' => 'success', 'nbDemi' => $nbDemi, 'finDate' => date('Y-m-d', $infos['finDate']), 'finMoment' => $infos['finMoment']));

        else:
            echo json_encode(array('type' => 'error', 'message' => 'Personnel inexistant'));
        endif;
    }

//      Transféré dans le controlleur LIGHT
//    public function baseRestrict() {
//        $this->hauteur = 30;
//        $this->largeur = 20;
//
//        $debut = date('Y-m-d');
//        $this->session->set_userdata('dateFocus', $debut);
//
//        $premierJour = $this->own->mktimeFromInputDate($debut);
//        $premierJourPlanning = $this->cal->premierJourSemaine($premierJour, 0);
//        $dernierJourPlanning = $premierJourPlanning + (13 * 86400);
//
//        /* Récuperation des données */
//        $personnelsActifs = $this->managerPersonnels->getPersonnels(array('personnelActif' => 1), 'personnelEquipeId DESC, personnelNom, personnelPrenom ASC');
//        foreach ($personnelsActifs as $persoActif):
//            $persoActif->hydrateEquipe();
//            $persoActif->hydrateHoraire();
//            $listePersonnel[] = $persoActif->getPersonnelId();
//        endforeach;
//
//        /* Affectations */
//        $affectations = $this->managerAffectations->getAffectationsPlanning($premierJourPlanning, $dernierJourPlanning, 2, 'a.affectationDebutDate ASC');
//        /* On détermine un nouveau jour de début de planning avec le début le plus ancien des affectations selectionnées.
//         * Pas de semaine avant cette date, le retour dans le passé est pris en compte dans la selection des affectations
//         */
//        $listeAffairesClotureesPlanning = array(); /* Liste des ID des affaires cloturées ayant une affectation dans le planning généré */
//
//        if ($affectations):
//            foreach ($affectations as $affectation):
//                $affectation->hydrateOrigines();
//                if ($affectation->getAffectationDebutDate() < $premierJourPlanning):
//                    $affectation->setAffectationDebutDate($premierJourPlanning);
//                    $affectation->setAffectationDebutMoment(1);
//                endif;
//                if ($affectation->getAffectationFinDate() > $dernierJourPlanning):
//                    $affectation->setAffectationFinDate($dernierJourPlanning);
//                    $affectation->setAffectationFinMoment(2);
//                endif;
//                $affectation->setAffectationCases($this->own->nbCasesAffectation($affectation));
//
//                /* Liste du personnel non actif mais présent dans des affectations de la période, ajouté aux personnels actifs. */
//                if (!in_array($affectation->getAffectationPersonnelId(), $listePersonnel)):
//                    $listePersonnel[] = $affectation->getAffectationPersonnelId();
//                endif;
//
//                /* Recupération des affaires cloturées apparaissant sur la planning */
//                if ($this->session->userdata('inclureTermines')):
//                    if ($affectation->getAffectationAffaire()->getAffaireEtat() == 3 && !in_array($affectation->getAffectationAffaire()->getAffaireId(), $listeAffairesClotureesPlanning)):
//                        $listeAffairesClotureesPlanning[] = $affectation->getAffectationAffaire()->getAffaireId();
//                    endif;
//                endif;
//
//            endforeach;
//            unset($affectation);
//        endif;
//        $this->session->set_userdata('planningPersonnelsIds', $listePersonnel);
//
//        /* Affaires du planning (toutes les non cloturées et les cloturées ayant une affectation sur le planning généré) */
//        $affairesPlanning = $this->managerAffaires->getAffairesPlanning($listeAffairesClotureesPlanning);
//        if (!empty($affairesPlanning)):
//            foreach ($affairesPlanning as $affaire):
//                $affaire->hydrateChantiers();
//                $affaire->hydrateClient();
//            endforeach;
//        endif;
//
//        /* recherche des indisponibilités pour cette periode */
//        $indisponibilitesPlanning = $this->managerIndisponibilites->getIndisponibilitesPlanning($premierJourPlanning, $dernierJourPlanning);
//        if (!empty($indisponibilitesPlanning)):
//            foreach ($indisponibilitesPlanning as $indispo):
//                if (!in_array($indispo->getIndispoPersonnelId(), $listePersonnel)):
//                    $listePersonnel[] = $indispo->getIndispoPersonnelId();
//                endif;
//            endforeach;
//        endif;
//
//        /* Personnels du planning (Actifs et les inactifs associés à une affectation du planning généré) */
//        $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($listePersonnel);
//        if (!empty($personnelsPlanning)):
//            foreach ($personnelsPlanning as $personnel):
//                $personnel->hydrateEquipe();
//            endforeach;
//        endif;
//
//        if ($affectations):
//            foreach ($affectations as $affectation):
//                $affectation->getHTML($premierJourPlanning, $personnelsPlanning, null, $this->hauteur, $this->largeur);
//            endforeach;
//            unset($affectation);
//        endif;
//
//        /* Passage du premier jour du planning en variable de session */
//        $this->session->set_userdata('premierJourPlanning', $premierJourPlanning);
//        $this->session->set_userdata('dernierJourPlanning', $dernierJourPlanning);
//
//
//        if (!empty($indisponibilitesPlanning)):
//            foreach ($indisponibilitesPlanning as $indispo):
//                $indispo->genereHTML($premierJourPlanning, $personnelsPlanning, null, $this->hauteur, $this->largeur);
//            endforeach;
//        endif;
//
//        /* les chantiers */
//        $chantiers = $this->managerChantiers->getChantiers(array('chantierEtat' => 1));
//        if (!empty($chantiers)):
//            foreach ($chantiers as $chantier):
//                $chantier->hydrateClient();
//            endforeach;
//            unset($chantier);
//        endif;
//
//        /* le planning va s'afficher sur N semaines */
//        $n = 2;
//
//        $data = array(
//            'section' => 'planning',
//            'hauteur' => $this->hauteur,
//            'largeur' => $this->largeur,
//            'today' => floor((time() - $premierJourPlanning) / 86400) * ($this->largeur * 2),
//            'msg' => $this->nbSemainesApres = $this->session->userdata('parametres')['messageEtablissement'],
//            /* Affaires & chantiers */
//            'affairesPlanning' => $affairesPlanning,
//            /* Personnels */
//            'personnelsActifs' => $personnelsActifs, /* Pour la sélection dans le formulaire d'ajout d'affectations */
//            'personnelsPlanning' => $personnelsPlanning, /* Personnels affichés sur le planning */
//            'indisposPlanning' => $indisponibilitesPlanning,
//            'motifs' => $this->managerMotifs->getMotifs(),
//            'affectationsPlanning' => $affectations,
//            'fournisseurs' => $this->managerFournisseurs->getFournisseurs(),
//            'dateFocus' => $debut, /* Date à partir de laquelle tout est calculé */
//            'premierJourPlanning' => $premierJourPlanning,
//            'dernierJourPlanning' => $dernierJourPlanning,
//            'nbSemainesPlanning' => $n,
//            'title' => $this->session->userdata('rs') . '|Planning',
//            'description' => 'Planning de votre activité',
//            'content' => $this->viewFolder . __FUNCTION__
//        );
//        $this->load->view('template/content', $data);
//    }

    public function relierAffectation() {
        if (!$this->form_validation->run('relierAffectation')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $affectation = $this->managerAffectations->getAffectationById($this->input->post('lierAffectationId'));
            $affectation->setAffectationChantierId($this->input->post('lierChantierId'));
            $this->managerAffectations->editer($affectation);
            echo json_encode(array('type' => 'success'));
        endif;
    }

}
