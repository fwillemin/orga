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

        /* Définition des variables de planning */
        $this->nbSemainesAvant = $this->session->userdata('parametres')['nbSemainesAvant'];
        $this->nbSemainesApres = $this->session->userdata('parametres')['nbSemainesApres'];
        switch ($this->session->userdata('parametres')['tailleAffectations']):
            case 1:
            case 2:
                $this->hauteur = 40;
                $this->largeur = 30;
                break;
            case 3:
                $this->hauteur = 50;
                $this->largeur = 38;
                break;
        endswitch;
    }

    public function modAffichageTermines() {
        if (!$this->input->post('etat') || $this->input->post('etat') == 0):
            $this->session->unset_userdata('inclureTermines');
        else:
            $this->session->set_userdata('inclureTermines', 1);
        endif;
        echo json_encode(array('type' => 'success'));
    }

    /**
     * Affiche le planning
     * @param String $debut Format YYYY-MM-DD
     */
    public function base($debut = null) {

        /* Recherche du premier jour du planning en excluant le dossier divers */
        if ($debut):
            $this->session->set_userdata('dateFocus', $debut);
        elseif ($this->session->userdata('dateFocus', $debut)):
            $debut = $this->session->userdata('dateFocus', $debut);
        else:
            $debut = date('Y-m-d');
            $this->session->set_userdata('dateFocus', $debut);
        endif;
        $premierJour = $this->own->mktimeFromInputDate($debut);
        $premierJourPlanning = $this->cal->premierJourSemaine($premierJour, $this->nbSemainesAvant);

        /* Le dernier jour du planning est le premier jour auquel on additionne
         * les semaines avant et apres la dateFocus et la derniere semaine en cours
         */
        $dernierJourPlanning = $premierJourPlanning + (1 + $this->nbSemainesAvant + $this->nbSemainesApres) * 604800;


        /* Récuperation des données */
        $personnelsActifs = $this->managerPersonnels->getPersonnels(array('personnelActif' => 1));
        foreach ($personnelsActifs as $persoActif):
            $listePersonnel[] = $persoActif->getPersonnelId();
        endforeach;

        /* Affectations */
        $affectations = $this->managerAffectations->getAffectationsPlanning($premierJourPlanning, $dernierJourPlanning, ($this->session->userdata('inclureTermines') ? 2 : 1), 'a.affectationDebut ASC');
        /* On détermine un nouveau jour de début de planning avec le début le plus ancien des affectations selectionnées.
         * Pas de semaine avant cette date, le retour dans le passé est pris en compte dans la selection des affectations
         */
        $listeAffairesClotureesPlanning = array(); /* Liste des ID des affaires cloturées ayant une affectation dans le panning généré */
        if ($affectations):
            /* Initialisations */
            $dernier = $dernierJourPlanning;

            /* Permier et dernier jours du planning */
            $premierJourPlanning = $this->cal->premierJourSemaine($affectations[0]->getAffectationDebut(), 0);

            foreach ($affectations as $affectation):

                /* Dernier jour du planning */
                if ($affectation->getAffectationFin() > $dernier):
                    $dernier = $affectation->getAffectationFin();
                endif;
                /* Liste du personnel non actif mais présent dans des affectations de la période, ajouté aux personnels actifs. */
                if (!in_array($affectation->getAffectationPersonnelId(), $listePersonnel)):
                    $listePersonnel[] = $affectation->getAffectationPersonnelId();
                endif;

                /* Recupération des affaires cloturées apparaissant sur la planning */
                if ($this->session->userdata('inclureTermines')):
                    $affectation->hydrateOrigines();
                    if ($affectation->getAffectationAffaire()->getAffaireEtat() == 3 && !in_array($affectation->getAffectationAffaire()->getAffaireId(), $listeAffairesClotureesPlanning)):
                        $listeAffairesClotureesPlanning[] = $affectation->getAffectationAffaire()->getAffaireId();
                    endif;
                endif;

            endforeach;
            unset($affectation);

            /* Mise à jour des variables du planning */
            $dernierJourPlanning = $this->cal->dernierJourSemaine($dernier, $this->nbSemainesApres);

        endif;

        /* Affaires du planning (toutes les non cloturées et les cloturées ayant une affectation sur le planning généré) */
        $affairesPlanning = $this->managerAffaires->getAffairesPlanning($listeAffairesClotureesPlanning);
        if (!empty($affairesPlanning)):
            foreach ($affairesPlanning as $affaire):
                $affaire->hydrateChantiers();
                $affaire->hydrateClient();
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
                $affectation->getHTML($premierJourPlanning, $personnelsPlanning);
            endforeach;
            unset($affectation);
        endif;

        /* Passage du premier jour du planning en variable de session */
        $this->session->set_userdata('premierJourPlanning', $premierJourPlanning);
        $this->session->set_userdata('dernierJourPlanning', $dernierJourPlanning);

        /* Recherche du personnel Forcé Inactif */
//        $personnelsForceInactif = $this->managerPersonnels->liste(array('actif' => 0));
//        /* Si on est sur le planning des Terminés ou le Full, on ne retire pas les forcés Inactifs pour pouvoir afficher l'hotorique */
//        if (!empty($personnelsForceInactif) && $this->session->userdata('dossierEtat') == 'Encours'):
//            foreach ($personnelsForceInactif as $pfi):
//                if (in_array($pfi->getId(), $personnelListe)):
//                    /* On retire l'id su personnel dans la liste */
//                    $key = array_search($pfi->getId(), $personnelListe);
//                    unset($personnelListe[$key]);
//                endif;
//            endforeach;
//        endif;

        /* recherche des indisponibilités pour cette periode */
        //$indisponibilites = $this->managerIndisponibilites->liste(array('fin >=' => $premierJourPlanning, 'debut <=' => $derniereAffectation), 'i.fin ASC');

        /* les chantiers */
        $chantiers = $this->managerChantiers->getChantiers(array('chantierEtat' => 1));
        if (!empty($chantiers)):
            foreach ($chantiers as $chantier):
                $chantier->hydrateClient();
            endforeach;
            unset($chantier);
        endif;

        /* les livraisons de ces chantiers */
//        $livraisons = $this->managerLivraisons->listeInArrayChantiers($listeChantiersPlanning);
        /* le planning va s'afficher sur N semaines */
        $n = ceil(($dernierJourPlanning - $premierJourPlanning) / 604800);


        //calcul de stats ----------------------------------------------------------------------------------------------------------------------
        //carnet de commande = uniquement les encours
//        $chantierCarnet = $this->managerChantiers->liste(array('c.etat' => 'Encours'));
//        $carnet = 0; /* Nombre d'heure de travail à venir */
//        $ca = 0; /* Chiffre d'affaire à venir des chantiers en Encours */
//        if (!empty($chantierCarnet)):
//            foreach ($chantierCarnet as $c):
//                $c->hydrateHeures();
//                // on additionne le Ca
//                $ca += $c->getPrix();
//                //on comptabilise les heures déjà saisies sur ce Chantier
//                $temp_deduction = 0;
//                if (!empty($c->getChantierHeures())):
//                    foreach ($c->getChantierHeures() as $h):
//                        if ($h->getHeureChantierId() == $c->getId())
//                            $temp_deduction += $h->getNb_heure();
//                    endforeach;
//                endif;
//                //on deduit les heures réalisées du chantier (0 si plus d'heures que prevues à l'origine)
//                if ($temp_deduction <= $c->getNb_heures_prev()):
//                    $carnet += ($c->getNb_heures_prev() - $temp_deduction);
//                endif;
//            endforeach;
//        endif;
//
//        /* On recherche les chantiers terminés dans l'année fiscale */
//        $realise = 0;
//        $chantiersTermines = $this->managerChantiers->liste(array('c.etat' => 'Termine', 'c.cloture >' => $this->debutFiscale));
//        if (!empty($chantiersTermines)):
//            foreach ($chantiersTermines as $c):
//                $realise += $c->getPrix();
//            endforeach;
//        endif;

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
            //'indisponibilite' => $indisponibilites,
            //'liste_chantier' => $chantiers,
            'listeAffectations' => $affectations,
            //'liste_heure' => $heures,
            //'listeLivraison' => $livraisons,
            //'listeFournisseurs' => $this->managerFournisseurs->liste(),
            'dateFocus' => $debut, /* Date à partir de laquelle tout est calculé */
            'premierJourPlanning' => $premierJourPlanning,
            'dernierJourPlanning' => $dernierJourPlanning,
            //'periodePrecedente' => date('Y-m-d', $premierJourPlanning - 7776000),
            'nbSemainesPlanning' => $n,
            //'carnet' => $carnet,
            //'ca' => $ca,
            //'realise' => $realise,
            //'nbChantiersEncours' => count($chantierCarnet),
            //'nbChantiersTermines' => count($chantiersTermines),
            //'analyseActivite' => $this->analyseActivite(),
            'title' => $this->session->userdata('rs') . '|Planning',
            'description' => 'Planning de votre activité',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

}
