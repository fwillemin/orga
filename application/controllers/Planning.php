<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Planning extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in()) :
            redirect('organibat/board');
        endif;
    }

    public function index($debut = null) {

        /* Recherche du premier jour du planning en excluant le dossier divers */
        if ($debut):
            $premierJour = $this->organibat->mktimeFromInputDate($debut);
        else:
            $premierJour = time() - (15 * 86400);
        endif;
        $premierJourPlanning = ($premierJour - (mdate('%N', $premierJour) - 1) * 86400) - (28 * 86400); // 1 mois avant le premier jour de la semaine en cours

        $where_affect_start = array();
        $where_affect = array();

        /* parametrage des requetes en fonction de la selection de l'état DU DOSSIER */
        switch ($this->session->userdata('dossierEtat')):
            case 'Encours':
                $whereAffect = array('d.dossierEtat' => 'Encours');
                $whereChantier = array('d.dossierEtat' => 'Encours');
                $whereDossier = array('d.dossierEtat' => 'Encours');
                break;
            case 'Termine':
                $whereAffect = array('d.dossierEtat' => 'Termine');
                $whereChantier = array('d.dossierEtat' => 'Termine');
                $whereDossier = array('d.dossierEtat' => 'Termine');
                break;
            default:
                $whereAffect = array('d.dossierEtat <>' => 'Devis');
                $whereChantier = array('d.dossierEtat <>' => 'Devis');
                $whereDossier = array('d.dossierEtat <>' => 'Devis');
                break;
        endswitch;

        $whereAffect['a.fin >='] = $premierJourPlanning;

        /* Récuperation des données */
        /* Affectations */
        $affectations = $this->managerAffectations->liste($whereAffect, 'a.debut ASC');
        /* on liste le personnel qui est utilisé dans ces affectations */
        $personnelListe = array();
        $affectationListe = array();
        $heures = array();

        /* On parcours les affectations pour créer les listes de personnels  et modifier le debut
         * du planning si une affectation commence avant.
         */
        $newPremierJour = $premierJourPlanning;
        if (!empty($affectations)):
            /* Creation des listes */
            foreach ($affectations AS $a):

                if ($a->getDebut() < $newPremierJour):
                    $newPremierJour = $a->getDebut();
                endif;

                $affectationListe[] = $a->getId(); /* la liste des id d'affectation va servir à rechercher uniquement les heures necessaires */
                if ($a->getAffectationDossierId() != $this->session->userdata('divers') && !in_array($a->getId_personnel(), $personnelListe)):
                    $personnelListe[] = $a->getId_personnel();
                endif;
            endforeach;

            /* Nouvelle date de début de planning ajustée aux affectations */
            $premierJourPlanning = ($newPremierJour - (mdate('%N', $newPremierJour) - 1) * 86400); // 1 mois avant le premier jour de la semaine en cours
            /* Recherche des heures */
            $heures = $this->managerHeures->listeInArrayAffectation($affectationListe);
        endif;

        /* Passage du premier jour du planning en variable de session */
        $this->session->set_userdata('planningPremierJour', $premierJourPlanning);

        /* Recherche du personnel Forcé Actif */
        $personnelsForceActif = $this->managerPersonnels->liste(array('actif' => 1));
        if (!empty($personnelsForceActif)):
            foreach ($personnelsForceActif as $pfa):
                if (!in_array($pfa->getId(), $personnelListe)):
                    $personnelListe[] = $pfa->getId();
                endif;
            endforeach;
        endif;

        /* Recherche du personnel Forcé Inactif */
        $personnelsForceInactif = $this->managerPersonnels->liste(array('actif' => 0));
        /* Si on est sur le planning des Terminés ou le Full, on ne retire pas les forcés Inactifs pour pouvoir afficher l'hotorique */
        if (!empty($personnelsForceInactif) && $this->session->userdata('dossierEtat') == 'Encours'):
            foreach ($personnelsForceInactif as $pfi):
                if (in_array($pfi->getId(), $personnelListe)):
                    /* On retire l'id su personnel dans la liste */
                    $key = array_search($pfi->getId(), $personnelListe);
                    unset($personnelListe[$key]);
                endif;
            endforeach;
        endif;

        if (!empty($personnelListe)):
            /* passage de la liste du personnel qui sera affiché sur le planning en session
             * pour pouvoir la réutiliser lors de l'ajout/modification des affectations, drag et resize
             */
            $this->session->set_userdata('listePersonnelPlanning', $personnelListe);
            $personnels = $this->managerPersonnels->listingInArray($personnelListe);
        else:
            $personnels = array();
        endif;

        /* recherche du dernier jour du planning */
        if (!empty($affectations)):
            $derniereAffectation = end($affectations)->getFin() + 15 * 86400;
        else:
            $derniereAffectation = time() + 15 * 86400;
        endif;

        /* recherche des indisponibilités pour cette periode */
        $indisponibilites = $this->managerIndisponibilites->liste(array('fin >=' => $premierJourPlanning, 'debut <=' => $derniereAffectation), 'i.fin ASC');

        /* les chantiers */
        $chantiers = $this->managerChantiers->liste($whereChantier);
        $listeChantiersPlanning = array();
        if (!empty($chantiers)):
            foreach ($chantiers as $c):
                $listeChantiersPlanning[] = $c->getId();
            endforeach;
        endif;

        /* les dossiers */
        $dossiers = $this->managerDossiers->liste($whereDossier, 'd.client ASC');
        if (!empty($dossiers)):
            foreach ($dossiers as $d):
                $d->hydrateChantiers();
            endforeach;
        endif;



        /* les livraisons de ces chantiers */
        $livraisons = $this->managerLivraisons->listeInArrayChantiers($listeChantiersPlanning);
        /* le planning va s'afficher sur N semaines */
        $n = ceil(($derniereAffectation - $premierJourPlanning) / 604800);


        //calcul de stats ----------------------------------------------------------------------------------------------------------------------
        //carnet de commande = uniquement les encours
        $chantierCarnet = $this->managerChantiers->liste(array('c.etat' => 'Encours'));
        $carnet = 0; /* Nombre d'heure de travail à venir */
        $ca = 0; /* Chiffre d'affaire à venir des chantiers en Encours */
        if (!empty($chantierCarnet)):
            foreach ($chantierCarnet as $c):
                $c->hydrateHeures();
                // on additionne le Ca
                $ca += $c->getPrix();
                //on comptabilise les heures déjà saisies sur ce Chantier
                $temp_deduction = 0;
                if (!empty($c->getChantierHeures())):
                    foreach ($c->getChantierHeures() as $h):
                        if ($h->getHeureChantierId() == $c->getId())
                            $temp_deduction += $h->getNb_heure();
                    endforeach;
                endif;
                //on deduit les heures réalisées du chantier (0 si plus d'heures que prevues à l'origine)
                if ($temp_deduction <= $c->getNb_heures_prev()):
                    $carnet += ($c->getNb_heures_prev() - $temp_deduction);
                endif;
            endforeach;
        endif;

        /* On recherche les chantiers terminés dans l'année fiscale */
        $realise = 0;
        $chantiersTermines = $this->managerChantiers->liste(array('c.etat' => 'Termine', 'c.cloture >' => $this->debutFiscale));
        if (!empty($chantiersTermines)):
            foreach ($chantiersTermines as $c):
                $realise += $c->getPrix();
            endforeach;
        endif;

        $data = array(
            'map' => '',
            'section' => 'planning',
            'hauteur' => self::HAUTEUR,
            'largeur' => self::LARGEUR,
            'today' => floor((time() - $premierJourPlanning) / 86400) * (self::LARGEUR * 2),
            'msg' => $this->m_etablissement->get_one($this->session->userdata('etablissement'))->msg,
            'liste_personnel' => $personnels,
            'liste_chantier' => $chantiers,
            'liste_dossier' => $dossiers, /* liste des dossiers utilisée pour le slide gauche */
            'liste_affectation' => $affectations,
            'liste_heure' => $heures,
            'listeLivraison' => $livraisons,
            'listeFournisseurs' => $this->managerFournisseurs->liste(),
            'indisponibilite' => $indisponibilites,
            'premier_jour' => $premierJourPlanning,
            'dernier_jour' => $derniereAffectation,
            'periodePrecedente' => date('Y-m-d', $premierJourPlanning - 7776000),
            'nb_semaine_planning' => $n,
            'carnet' => $carnet,
            'ca' => $ca,
            'realise' => $realise,
            'nbChantiersEncours' => count($chantierCarnet),
            'nbChantiersTermines' => count($chantiersTermines),
            'analyseActivite' => $this->analyseActivite(),
            'title' => $this->session->userdata('rs') . '|Planning',
            'description' => 'Planning de votre activité',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

}
