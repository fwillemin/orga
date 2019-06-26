<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Light extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in()) :
            redirect('organibat/board');
        endif;

        $this->hauteur = 30;
        $this->largeur = 20;
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
                $ratio = round($affectation->getAffectationChantier()->getChantierHeuresPlanifiees() * 100 / $affectation->getAffectationChantier()->getChantierHeuresPrevues());
            else:
                $ratio = 0;
            endif;
            if ($ratio > 100):
                $bgClass = "progress-bar bg-danger";
                $ratio = 100;
            elseif ($ratio > 75):
                $bgClass = "progress-bar bg-warning";
            else:
                $bgClass = "progress-bar bg-info";
            endif;

            $chantier = array(
                'chantierId' => $affectation->getAffectationChantier()->getChantierId(),
                'chantierObjet' => $affectation->getAffectationChantier()->getChantierObjet(),
                'chantierPlace' => !empty($affectation->getAffectationChantier()->getChantierPlace()) ? $affectation->getAffectationChantier()->getChantierPlace()->getPlaceAdresse() : '',
                'chantierRemarque' => $affectation->getAffectationChantier()->getChantierRemarque(),
                'chantierRatio' => $ratio,
                'chantierEtat' => $affectation->getAffectationChantier()->getChantierEtat(),
                'chantierProgressBar' => $bgClass
            );

            $client = array(
                'clientId' => $affectation->getAffectationClient()->getClientId(),
                'clientNom' => $affectation->getAffectationClient()->getClientNom(),
                'clientVille' => $affectation->getAffectationClient()->getClientVille(),
                'clientPortable' => $affectation->getAffectationClient()->getClientPortable() ?: '-',
                'clientFixe' => $affectation->getAffectationClient()->getClientFixe() ?: '-'
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

    public function baseRestrict() {
        $this->session->unset_userdata('ouvrierConnecte');
        $debut = date('Y-m-d');
        $this->session->set_userdata('dateFocus', $debut);

        $premierJour = $this->own->mktimeFromInputDate($debut);
        $premierJourPlanning = $this->cal->premierJourSemaine($premierJour, 0);
        $dernierJourPlanning = $premierJourPlanning + (27 * 86400);

        /* Récuperation des données */
        $personnelsActifs = $this->managerPersonnels->getPersonnels(array('personnelActif' => 1), 'personnelEquipeId DESC, personnelNom, personnelPrenom ASC');
        foreach ($personnelsActifs as $persoActif):
            $persoActif->hydrateEquipe();
            $persoActif->hydrateHoraire();
            $listePersonnel[] = $persoActif->getPersonnelId();
        endforeach;

        /* Affectations */
        $affectations = $this->managerAffectations->getAffectationsPlanning($premierJourPlanning, $dernierJourPlanning, 2, 'a.affectationDebutDate ASC');
        /* On détermine un nouveau jour de début de planning avec le début le plus ancien des affectations selectionnées.
         * Pas de semaine avant cette date, le retour dans le passé est pris en compte dans la selection des affectations
         */
        $listeAffairesClotureesPlanning = array(); /* Liste des ID des affaires cloturées ayant une affectation dans le planning généré */

        if ($affectations):
            foreach ($affectations as $affectation):
                $affectation->hydrateOrigines();
                if ($affectation->getAffectationDebutDate() < $premierJourPlanning):
                    $affectation->setAffectationDebutDate($premierJourPlanning);
                    $affectation->setAffectationDebutMoment(1);
                endif;
                if ($affectation->getAffectationFinDate() > $dernierJourPlanning):
                    $affectation->setAffectationFinDate($dernierJourPlanning);
                    $affectation->setAffectationFinMoment(2);
                endif;
                $affectation->setAffectationCases($this->own->nbCasesAffectation($affectation));

                /* Liste du personnel non actif mais présent dans des affectations de la période, ajouté aux personnels actifs. */
                if (!in_array($affectation->getAffectationPersonnelId(), $listePersonnel)):
                    $listePersonnel[] = $affectation->getAffectationPersonnelId();
                endif;

                /* Recupération des affaires cloturées apparaissant sur la planning */
                if ($this->session->userdata('inclureTermines')):
                    if ($affectation->getAffectationAffaire()->getAffaireEtat() == 3 && !in_array($affectation->getAffectationAffaire()->getAffaireId(), $listeAffairesClotureesPlanning)):
                        $listeAffairesClotureesPlanning[] = $affectation->getAffectationAffaire()->getAffaireId();
                    endif;
                endif;

            endforeach;
            unset($affectation);
        endif;
        $this->session->set_userdata('planningPersonnelsIds', $listePersonnel);

        /* Affaires du planning (toutes les non cloturées et les cloturées ayant une affectation sur le planning généré) */
        $affairesPlanning = $this->managerAffaires->getAffairesPlanning($listeAffairesClotureesPlanning);
        if (!empty($affairesPlanning)):
            foreach ($affairesPlanning as $affaire):
                $affaire->hydrateChantiers();
                $affaire->hydrateClient();
            endforeach;
        endif;

        /* recherche des indisponibilités pour cette periode */
        $indisponibilitesPlanning = $this->managerIndisponibilites->getIndisponibilitesPlanning($premierJourPlanning, $dernierJourPlanning);
        if (!empty($indisponibilitesPlanning)):
            foreach ($indisponibilitesPlanning as $indispo):
                if (!in_array($indispo->getIndispoPersonnelId(), $listePersonnel)):
                    $listePersonnel[] = $indispo->getIndispoPersonnelId();
                endif;
            endforeach;
        endif;

        /* Personnels du planning (Actifs et les inactifs associés à une affectation du planning généré) */
        $personnelsPlanning = $this->managerPersonnels->getPersonnelsPlanning($listePersonnel);
        if (!empty($personnelsPlanning)):
            foreach ($personnelsPlanning as $personnel):
                $personnel->hydrateEquipe();
            endforeach;
        endif;

        if ($affectations):
            foreach ($affectations as $affectation):
                $affectation->getHTML($premierJourPlanning, $personnelsPlanning, null, $this->hauteur, $this->largeur);
            endforeach;
            unset($affectation);
        endif;

        /* Passage du premier jour du planning en variable de session */
        $this->session->set_userdata('premierJourPlanning', $premierJourPlanning);
        $this->session->set_userdata('dernierJourPlanning', $dernierJourPlanning);


        if (!empty($indisponibilitesPlanning)):
            foreach ($indisponibilitesPlanning as $indispo):
                $indispo->genereHTML($premierJourPlanning, $personnelsPlanning, null, $this->hauteur, $this->largeur);
            endforeach;
        endif;

        /* les chantiers */
        $chantiers = $this->managerChantiers->getChantiers(array('chantierEtat' => 1));
        if (!empty($chantiers)):
            foreach ($chantiers as $chantier):
                $chantier->hydrateClient();
            endforeach;
            unset($chantier);
        endif;

        /* le planning va s'afficher sur N semaines */
        $n = 4;

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
            'affectationsPlanning' => $affectations,
            'fournisseurs' => $this->managerFournisseurs->getFournisseurs(),
            'dateFocus' => $debut, /* Date à partir de laquelle tout est calculé */
            'premierJourPlanning' => $premierJourPlanning,
            'dernierJourPlanning' => $dernierJourPlanning,
            'nbSemainesPlanning' => $n,
            'title' => $this->session->userdata('rs') . '|Planning',
            'description' => 'Planning de votre activité',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function tryConnectOuvrier() {
        if (!$this->form_validation->run('getPersonnel')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $personnel = $this->managerPersonnels->getPersonnelById($this->input->post('personnelId'));
            if ($this->input->post('code') == $personnel->getpersonnelCode()):
                $this->session->set_userdata('ouvrierConnecte', $personnel->getPersonnelId());
                echo json_encode(array('type' => 'success'));
            else:
                echo json_encode(array('type' => 'error', 'message' => 'Code invalide'));
            endif;
        endif;
    }

    public function saisie() {
        if (!$this->session->userdata('ouvrierConnecte') || !$this->existPersonnel($this->session->userdata('ouvrierConnecte'))):
            $this->session->unset_userdata('ouvrierConnecte');
            redirect('light/baseRestrict');
        else:
            $personnel = $this->managerPersonnels->getPersonnelById($this->session->userdata('ouvrierConnecte'));

            $fin = date('Y-m-d');
            $dernierJourSaisie = $this->own->mktimeFromInputDate($fin);
            $premierJourSaisie = $dernierJourSaisie - 7 * 86400;

            /* Affectations */
            $affectations = $this->managerAffectations->getAffectationsSaisie($personnel->getPersonnelId(), $premierJourSaisie, $dernierJourSaisie, 2, 'a.affectationDebutDate ASC');
            if (!empty($affectations)):
                foreach ($affectations as $affectation):
                    $affectation->hydrateHeures();
                    $affectation->hydrateOrigines();
                    $affectation->getAffectationChantier()->hydrateClient();
                endforeach;
            endif;
            unset($affectation);

            $data = array(
                'personnel' => $personnel,
                'premierJourSaisie' => $premierJourSaisie,
                'dernierJourSaisie' => $dernierJourSaisie,
                'affectations' => $affectations,
                'title' => $this->session->userdata('rs') . '|Saisie heures ouvriers',
                'description' => 'Saisie des heures',
                'content' => $this->viewFolder . __FUNCTION__
            );
            $this->load->view('template/content', $data);

        endif;
    }

}
