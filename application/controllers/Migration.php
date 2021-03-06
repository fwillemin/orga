<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * Projets de développement
 * - Passer les heures prévues d'un chantier en comptant les heures de chaque 1/2 journée en fonction de l'horaire du personnel de l'affectation (pas de travail l'aprem, 4 heures le matin et 3 heures l'aprem, ...)
 */

class Migration extends My_Controller {

    const tauxTVA = 20;

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';
    }

    public function correctif() {
        $chantiers = $this->managerChantiers->getChantiers();
        foreach ($chantiers as $chantier):
            $oldChantier = $this->db->select('*')->from('V1_chantier')->where('id', $chantier->getChantierOriginId())->get()->result();
            if (!empty($oldChantier)):
                $chantier->setChantierHeuresPrevues($oldChantier[0]->nb_heures_prev);
            else:
                $chantier->setChantierHeuresPrevues(1);
            endif;
            $this->managerChantiers->editer($chantier);
        endforeach;
        echo 'Done';
    }

    /**
     * renseigne la date de cloture des affaires cloturées à la date de cloture du dernier chantier
     * Opération à réaliser déconnecté de tout compte client
     */
    public function correctifDateClotureAffaire() {

        foreach ($this->db->select('*')->from('affaires')->where(array('affaireEtat' => 3, 'affaireDateCloture' => null))->get()->result() as $affaire):
            $chantiers = $this->db->select('*')->from('chantiers')->where('chantierAffaireId', $affaire->affaireId)->get()->result();
            if (!empty($chantiers)):
                $dateCloture = null;
                foreach ($chantiers as $chantier):
                    if ($chantier->chantierDateCloture > $dateCloture):
                        $dateCloture = $chantier->chantierDateCloture;
                    endif;
                endforeach;
                $this->db->set('affaireDateCloture', $dateCloture)->where('affaireId', $affaire->affaireId)->update('affaires');
            else:
                echo 'Affaire ' . $affaire->getAffaireId() . ' semble ne pas avoir de chantier mais est indiquée comme cloturée !';
            endif;
        endforeach;

        echo 'Opération terminée avec succès. ';
    }

    public function correctifDateCreationAffaire() {

        foreach ($this->db->select('*')->from('affaires')->where(array('affaireCreation' => null, 'affaireDateSignature>' => 0))->get()->result() as $affaire):
            $this->db->set('affaireCreation', $affaire->affaireDateSignature)->where('affaireId', $affaire->affaireId)->update('affaires');
        endforeach;
        $affaires = $this->managerAffaires->getAffaires(array('affaireCreation' => 0));
        if (!empty($affaires)):
            foreach ($affaires as $affaire):
                $dateCreation = '';
                $affaire->hydrateChantiers();
                if (!empty($affaire->getAffaireChantiers())):
                    foreach ($affaire->getAffaireChantiers() as $chantier):
                        $chantier->hydrateAffectations();
                        if (!empty($chantier->getChantierAffectations())):
                            foreach ($chantier->getChantierAffectations() as $affectation):
                                if (!$dateCreation || $dateCreation > $affectation->getAffectationDebutDate()):
                                    $dateCreation = $affectation->getAffectationDebutDate();
                                endif;
                            endforeach;
                        endif;
                    endforeach;
                endif;
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . $dateCreation);
                if ($dateCreation):
                    $affaire->setAffaireCreation($dateCreation);
                    $this->managerAffaires->editer($affaire);
                endif;

            endforeach;
        endif;
        $affaires = $this->managerAffaires->getAffairesCreationNull();
        if (!empty($affaires)):
            foreach ($affaires as $affaire):
                unset($dateCreation);
                $affaire->hydrateChantiers();
                if (!empty($affaire->getAffaireChantiers())):
                    foreach ($affaire->getAffaireChantiers() as $chantier):
                        $chantier->hydrateAffectations();
                        if (!empty($chantier->getChantierAffectations())):
                            foreach ($chantier->getChantierAffectations() as $affectation):
                                if (!$dateCreation || $dateCreation > $affectation->getAffectationDebutDate()):
                                    $dateCreation = $affectation->getAffectationDebutDate();
                                endif;
                            endforeach;
                        endif;
                    endforeach;
                endif;
                if ($dateCreation):
                    $affaire->setAffaireCreation($dateCreation);
                    $this->managerAffaires->editer($affaire);
                endif;
            endforeach;
        endif;

        echo 'Opération terminée avec succès. ';
    }

    public function correctifDateClotureChantier() {

        foreach ($this->db->select('*')->from('chantiers')->where(array('chantierEtat' => 2, 'chantierDateCloture' => 0))->get()->result() as $chantier):
            $derniereHeure = $this->db->select('MAX(h.heureDate) AS max')->from('heures h')->join('affectations a', 'a.affectationId = h.heureAffectationId')->where('a.affectationChantierId', $chantier->chantierId)->get()->result();
            if ($derniereHeure[0]->max > 0):
                $this->db->set('chantierDateCloture', $derniereHeure[0]->max)->where('chantierId', $chantier->chantierId)->update('chantiers');
            endif;
        endforeach;

        echo 'Opération terminée avec succès. ';
    }

    /* Avant de lancer le script, il est imperatif de copier toutes les tables de la V1 (hors licences et transactions) avec le préfixe V1 dans la BDD de la V2
     * - Etre déconnecté !!!!!!!
     * - Vérifier que l'etablissement à un email valide avec un domaine qui lui est propre
     *
     */

    public function resetDB($rsId = null) {
        if (!$rsId):
            echo 'Vous devez renseigner un ID RS valide;';
        else:
            $this->db->query("DELETE FROM `raisonsSociales`");
            $this->db->query("ALTER TABLE `raisonsSociales` auto_increment = 1;");
            $this->db->query("ALTER TABLE `etablissements` auto_increment = 1;");
            $this->db->query("ALTER TABLE `users` auto_increment = 1;");
            $this->db->query("ALTER TABLE `users_groups` auto_increment = 1;");
            $this->db->query("ALTER TABLE `horaires` auto_increment = 1;");
            $this->db->query("ALTER TABLE `personnels` auto_increment = 1;");
            $this->db->query("ALTER TABLE `tauxHoraires` auto_increment = 1;");
            $this->db->query("ALTER TABLE `categories` auto_increment = 1;");
            $this->db->query("ALTER TABLE `clients` auto_increment = 1;");
            $this->db->query("ALTER TABLE `places` auto_increment = 1;");
            $this->db->query("ALTER TABLE `affaires` auto_increment = 1;");
            $this->db->query("ALTER TABLE `chantiers` auto_increment = 1;");
            $this->db->query("ALTER TABLE `achats` auto_increment = 1;");
            $this->db->query("ALTER TABLE `affectations` auto_increment = 1;");
            $this->db->query("ALTER TABLE `heures` auto_increment = 1;");
            $this->db->query("ALTER TABLE `fournisseurs` auto_increment = 1;");
            $this->db->query("ALTER TABLE `indisponibilites` auto_increment = 1;");
            $this->db->query("ALTER TABLE `pointages` auto_increment = 1;");
        endif;
    }

    public function migrerRS($rsId = null, $domaine = null, $migrationPlace = 0) {

        // ON NE RESEST PLUS LA BASE DE DONNEES A CHAQUE INTEGRATION !!
        //$this->resetDB($rsId);

        if (!$rsId):
            echo 'Vous devez renseigner un ID RS valide';
        elseif (!$domaine):
            echo 'Vous devez renseigner un domaine valide';
        else:

            $rs = $this->importRS($rsId);
            $etablissement = $this->importEtablissement($rs);

            /* Utilisateurs administratifs */
            $identifiants = $this->importUsers($etablissement, $domaine);
            $this->importCategories($rs);
            $this->importContacts($etablissement);
            $this->importFournisseurs($etablissement);
            $this->importHoraires($etablissement);
            $this->importPersonnels($etablissement);
            $this->importTauxHoraire($etablissement);
            $this->importCategories($rs);
            $this->importDossiers($etablissement, $migrationPlace);

            /* Mise à jour du dossier DIVERS */
            $divers = $this->db->select('*')->from('affaires')->where('affaireOriginId', $etablissement->getEtablissementAffaireDiversId())->get()->result();
            $etablissement->setEtablissementAffaireDiversId($divers[0]->affaireId);
            $this->managerEtablissements->editer($etablissement);

            echo 'Import terminé avec succès !<br>Connectez-vous avec les identifiants : ' . $identifiants;

        endif;
    }

    private function importRS($rsId) {
        $rsOLD = $this->db->select('*')->from('V1_raison_sociale')->where('id', $rsId)->get()->result()[0];
        if (empty($rsOLD)):
            echo 'Echec : Cette raison sociale est introuvable dans la V1';
            exit;
        else:
            $arrayRs = array(
                'rsOriginId' => $rsOLD->id,
                'rsNom' => mb_strtoupper($rsOLD->nom),
                'rsInscription' => $rsOLD->rs_inscription,
                'rsMoisFiscal' => $rsOLD->rs_mois_fiscal,
                'rsCategorieNC' => $rsOLD->rs_categorieNC
            );
            $rs = new RaisonSociale($arrayRs);
            $this->managerRaisonsSociales->ajouter($rs);
            return $rs;
        endif;
    }

    private function importEtablissement(RaisonSociale $rs) {
        $etaOLD = $this->db->select('*')->from('V1_etablissement')->where('id_rs', $rs->getRsOriginId())->get()->result()[0];
        if (empty($etaOLD)):
            echo 'Echec : Cette raison sociale n\'a aucun établissement';
            exit;
        else:
            $arrayEta = array(
                'etablissementOriginId' => $etaOLD->id,
                'etablissementRsId' => $rs->getRsId(),
                'etablissementNom' => strtoupper($etaOLD->nom),
                'etablissementAdresse' => $etaOLD->adresse,
                'etablissementCp' => $etaOLD->cp,
                'etablissementVille' => $etaOLD->ville,
                'etablissementContact' => $etaOLD->contact,
                'etablissementTelephone' => $etaOLD->tel,
                'etablissementEmail' => $etaOLD->email,
                'etablissementGps' => $etaOLD->gps,
                'etablissementStatut' => $etaOLD->statut,
                'etablissementAffaireDiversId' => $etaOLD->id_chantier_divers,
                'etablissementMessage' => $etaOLD->msg,
                'etablissementTauxFraisGeneraux' => $etaOLD->fraisGeneraux,
                'etablissementTauxHoraireMoyen' => $etaOLD->txHoraireMoyen,
                'etablissementBaseHebdomadaire' => 0,
                'etablissementExpiration' => 0,
                'etablissementLimiteActifs' => 5
            );
            $etablissement = new Etablissement($arrayEta);
            $this->managerEtablissements->ajouter($etablissement);

            /* Creation des paramètres de l'établissement */
            $param = new Parametre(
                    array(
                'parametreEtablissementId' => $etablissement->getEtablissementId(),
                'nbSemainesAvant' => 2,
                'nbSemainesApres' => 2,
                'tranchePointage' => 30,
                'tailleAffectations' => 2,
                'messageEtablissement' => '',
                'genererPaniers' => 1
                    )
            );
            $this->managerParametres->ajouter($param);
            return $etablissement;
        endif;
    }

    private function getPassword($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    /* GLOBAL
     *
     * Copier la table organibat_raison_sociale de la v1 en raisonsSociales puis
     * ALTER TABLE `raisonsSociales` CHANGE `id` `rsId` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `nom` `rsNom` VARCHAR(2558) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `rs_inscription` `rsInscription` INT(11) NOT NULL, CHANGE `rs_mois_fiscal` `rsMoisFiscal` TINYINT(4) NOT NULL, CHANGE `rs_categorieNC` `rsCategorieNC` INT(11) NULL COMMENT 'id de la categorie Non classé associée à la rs';

     * Copier la table organibat_etablissement de la v1 en etablissements puis
     * ALTER TABLE `etablissements` DROP `periodicite_hs`, DROP `limit_hs`, DROP `nb_rtt_annuel`, DROP `majoration_hs`;
     * ALTER TABLE `etablissements` CHANGE `id` `etablissementId` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `id_rs` `etablissementRsId` INT(11) NOT NULL, CHANGE `nom` `etablissementNom` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `adresse` `etablissementAdresse` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `cp` `etablissementCp` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `ville` `etablissementVille` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `contact` `etablissementContact` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `tel` `etablissementTelephone` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `email` `etablissementEmail` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `gps` `etablissementGps` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `statut` `etablissementStatut` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1=principal 2=secondaire', CHANGE `id_chantier_divers` `etablissementChantierDiversId` INT(11) NULL, CHANGE `msg` `etablissementMessage` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'message diffusé sur la page de saisie des heures par les salariés', CHANGE `fraisGeneraux` `etablissementTauxFraisGeneraux` DECIMAL(5,2) NOT NULL DEFAULT '0', CHANGE `txHoraireMoyen` `etablissementTauxHoraireMoyen` DECIMAL(5,2) NOT NULL DEFAULT '0';
     * ALTER TABLE `etablissements` ADD FOREIGN KEY (`etablissementRsId`) REFERENCES `raisonsSociales`(`rsId`) ON DELETE CASCADE ON UPDATE RESTRICT;
     */

    /*
     * Copier la table user de la v1 en usersV1
     * ALTER TABLE `users` DROP `first_name`, DROP `last_name`, DROP `company`, DROP `phone`;
     * ALTER TABLE `users` ADD `userNom` VARCHAR(120) NOT NULL AFTER `password`, ADD `userPrenom` VARCHAR(120) NOT NULL AFTER `userNom`, ADD `userEtablissementId` INT NOT NULL AFTER `userPrenom`, ADD `userClairMdp` VARCHAR(120) NOT NULL AFTER `userEtablissementId`, ADD INDEX (`userEtablissementId`);
     * ALTER TABLE `users` ADD UNIQUE(`email`);
     * ALTER TABLE `users` ADD FOREIGN KEY (`userEtablissementId`) REFERENCES `etablissements`(`etablissementId`) ON DELETE CASCADE ON UPDATE RESTRICT;

     */

    private function importUsers(Etablissement $etablissement, $domaine) {

        //$domaine .= '.com';
        $i = 1;
        foreach ($this->db->select('*')->from('V1_user')->where('id_etablissement', $etablissement->getEtablissementOriginId())->get()->result() as $user):

            if ($user->niveau != 5):
                $email = str_replace(array(' ', 'é', 'è'), array('', 'e', 'e'), strtolower($user->nom) . '.' . strtolower($user->prenom)) . '@' . $domaine;
                //$mdp = $this->getPassword();
                $mdp = 'Organibat2019';

                $additional_data = array(
                    'userNom' => strtoupper($user->nom),
                    'userPrenom' => ucfirst($user->prenom),
                    'userEtablissementId' => $etablissement->getEtablissementId(),
                    'userOriginId' => $user->id,
                    'userClairMdp' => $mdp,
                    'userCode' => 0000
                );
                unset($group);
                $groups = $this->db->select('id')->from('groups')->where('id > ', 4)->get()->result();
                foreach ($groups as $g):
                    $group[] = $g->id;
                endforeach;
                /* Admin */
                if ($i == 1):
                    $group[] = 1;
                else:
                    $group[] = 2;
                endif;

                $this->ion_auth->register($email, $mdp, $email, $additional_data, $group);
                $i++;
                if ($user->niveau == 1):
                    $identifiants = $email . '@' . $mdp;
                endif;
            endif;

        endforeach;
        return $identifiants;
    }

    private function importFournisseurs(Etablissement $etablissement) {

        foreach ($this->db->select('*')->from('V1_fournisseurs')->where('fournisseurEtablissementId', $etablissement->getEtablissementOriginId())->get()->result() as $fst):

            $dataFst = array(
                'fournisseurOriginId' => $fst->fournisseurId,
                'fournisseurEtablissementId' => $etablissement->getEtablissementId(),
                'fournisseurNom' => strtoupper($fst->fournisseurNom),
                'fournisseurAdresse' => $fst->fournisseurAdresse,
                'fournisseurCp' => $fst->fournisseurCp,
                'fournisseurVille' => $fst->fournisseurVille,
                'fournisseurTelephone' => $fst->fournisseurTelephone,
                'fournisseurEmail' => $fst->fournisseurEmail
            );
            $fournisseur = new Fournisseur($dataFst);
            $this->managerFournisseurs->ajouter($fournisseur);
            unset($fournisseur);

        endforeach;
    }

    private function importContacts(Etablissement $etablissement) {

        foreach ($this->db->select('*')->from('V1_contact')->where('contactEtablissementId', $etablissement->getEtablissementOriginId())->get()->result() as $contact):

            $etat = 1;
            if ($contact->contactEtat > 1):
                $contact->contactEtat += 1;
            endif;

            $categorie = null;
            if ($contact->contactCategorieId != 0):
                $categorieOrigin = $this->managerCategories->getCategorieByOriginId($contact->contactCategorieId);
                if ($categorieOrigin):
                    $categorie = $categorieOrigin->getCategorieId();
                endif;
            endif;

            $commercial = null;
            if ($contact->contactCommercialId != 0):
                $commercialOrigin = $this->managerUtilisateurs->getUtilisateurByOriginId($contact->contactCommercialId);
                if ($commercialOrigin):
                    $commercial = $commercialOrigin->getId();
                endif;
            endif;

            $dataContact = array(
                'contactEtablissementId' => $etablissement->getEtablissementId(),
                'contactDate' => $contact->contactDate,
                'contactMode' => $contact->contactOrigine,
                'contactNom' => $contact->contactNom . ' ' . $contact->contactPrenom,
                'contactAdresse' => $contact->contactAdresse,
                'contactCp' => $contact->contactCp,
                'contactVille' => $contact->contactVille,
                'contactTelephone' => $contact->contactTelephone,
                'contactEmail' => $contact->contactEmail,
                'contactObjet' => $contact->contactObjet,
                'contactCategorieId' => $categorie,
                'contactSource' => $contact->contactSource,
                'contactCommercialId' => $commercial,
                'contactEtat' => $contact->contactEtat
            );
            $contact = new Contact($dataContact);
            $this->managerContacts->ajouter($contact);
            unset($contact);

        endforeach;
    }

    /* Migration des horaires
     *
     * CREATE TABLE `horaires` (
      `horaireId` int(11) NOT NULL,
      `horaireEtablissementId` int(11) NOT NULL,
      `horaireNom` varchar(255) COLLATE utf8_bin NOT NULL,
      `horaireLun1` time NOT NULL,
      `horaireLun2` time NOT NULL,
      `horaireLun3` time NOT NULL,
      `horaireLun4` time NOT NULL,
      `horaireLunAM` decimal(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireLun2`,`horaireLun1`)) / 3600),2)) STORED,
      `horaireLunPM` float(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireLun4`,`horaireLun3`)) / 3600),2)) STORED,
      `horaireLun` float(4,2) GENERATED ALWAYS AS ((`horaireLunAM` + `horaireLunPM`)) STORED,
      `horaireMar1` time NOT NULL,
      `horaireMar2` time NOT NULL,
      `horaireMar3` time NOT NULL,
      `horaireMar4` time NOT NULL,
      `horaireMarAM` decimal(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireMar2`,`horaireMar1`)) / 3600),2)) STORED,
      `horaireMarPM` float(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireMar4`,`horaireMar3`)) / 3600),2)) STORED,
      `horaireMar` float(4,2) GENERATED ALWAYS AS ((`horaireMarAM` + `horaireMarPM`)) STORED,
      `horaireMer1` time NOT NULL,
      `horaireMer2` time NOT NULL,
      `horaireMer3` time NOT NULL,
      `horaireMer4` time NOT NULL,
      `horaireMerAM` decimal(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireMer2`,`horaireMer1`)) / 3600),2)) STORED,
      `horaireMerPM` float(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireMer4`,`horaireMer3`)) / 3600),2)) STORED,
      `horaireMer` float(4,2) GENERATED ALWAYS AS ((`horaireMerAM` + `horaireMerPM`)) STORED,
      `horaireJeu1` time NOT NULL,
      `horaireJeu2` time NOT NULL,
      `horaireJeu3` time NOT NULL,
      `horaireJeu4` time NOT NULL,
      `horaireJeuAM` decimal(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireJeu2`,`horaireJeu1`)) / 3600),2)) STORED,
      `horaireJeuPM` float(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireJeu4`,`horaireJeu3`)) / 3600),2)) STORED,
      `horaireJeu` float(4,2) GENERATED ALWAYS AS ((`horaireJeuAM` + `horaireJeuPM`)) STORED,
      `horaireVen1` time NOT NULL,
      `horaireVen2` time NOT NULL,
      `horaireVen3` time NOT NULL,
      `horaireVen4` time NOT NULL,
      `horaireVenAM` decimal(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireVen2`,`horaireVen1`)) / 3600),2)) STORED,
      `horaireVenPM` float(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireVen4`,`horaireVen3`)) / 3600),2)) STORED,
      `horaireVen` float(4,2) GENERATED ALWAYS AS ((`horaireVenAM` + `horaireVenPM`)) STORED,
      `horaireSam1` time NOT NULL,
      `horaireSam2` time NOT NULL,
      `horaireSam3` time NOT NULL,
      `horaireSam4` time NOT NULL,
      `horaireSamAM` decimal(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireSam2`,`horaireSam1`)) / 3600),2)) STORED,
      `horaireSamPM` float(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireSam4`,`horaireSam3`)) / 3600),2)) STORED,
      `horaireSam` float(4,2) GENERATED ALWAYS AS ((`horaireSamAM` + `horaireSamPM`)) STORED,
      `horaireDim1` time NOT NULL,
      `horaireDim2` time NOT NULL,
      `horaireDim3` time NOT NULL,
      `horaireDim4` time NOT NULL,
      `horaireDimAM` decimal(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireDim2`,`horaireDim1`)) / 3600),2)) STORED,
      `horaireDimPM` float(4,2) GENERATED ALWAYS AS (round((time_to_sec(timediff(`horaireDim4`,`horaireDim3`)) / 3600),2)) STORED,
      `horaireDim` float(4,2) GENERATED ALWAYS AS ((`horaireDimAM` + `horaireDimPM`)) STORED,
      `horaireTotal` float(4,2) GENERATED ALWAYS AS ((`horaireLun` + `horaireMar` + `horaireMer` + `horaireJeu` + `horaireVen` + `horaireSam` + `horaireDim`)) STORED
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
     * ALTER TABLE `horaires` ADD PRIMARY KEY(`horaireId`);
     * ALTER TABLE `horaires` CHANGE `horaireId` `horaireId` INT(11) NOT NULL AUTO_INCREMENT;
     * ALTER TABLE `horaires` ADD INDEX(`horaireEtablissementId`);

     *
     * Importer uniquement les données de la table Horaires de la V1 dans cette table
     */

    private function importHoraires(Etablissement $etablissement) {

        foreach ($this->db->select('*')->from('V1_horaires')->where('horaireEtablissementId', $etablissement->getEtablissementOriginId())->get()->result() as $horaire):

            $arrayHoraire = array(
                'horaireOriginId' => $horaire->horaireId,
                'horaireEtablissementId' => $etablissement->getEtablissementId(),
                'horaireNom' => $horaire->horaireNom,
                'horaireLun1' => $horaire->horaireLun1,
                'horaireLun2' => $horaire->horaireLun2,
                'horaireLun3' => $horaire->horaireLun3,
                'horaireLun4' => $horaire->horaireLun4,
                'horaireMar1' => $horaire->horaireMar1,
                'horaireMar2' => $horaire->horaireMar2,
                'horaireMar3' => $horaire->horaireMar3,
                'horaireMar4' => $horaire->horaireMar4,
                'horaireMer1' => $horaire->horaireMer1,
                'horaireMer2' => $horaire->horaireMer2,
                'horaireMer3' => $horaire->horaireMer3,
                'horaireMer4' => $horaire->horaireMer4,
                'horaireJeu1' => $horaire->horaireJeu1,
                'horaireJeu2' => $horaire->horaireJeu2,
                'horaireJeu3' => $horaire->horaireJeu3,
                'horaireJeu4' => $horaire->horaireJeu4,
                'horaireVen1' => $horaire->horaireVen1,
                'horaireVen2' => $horaire->horaireVen2,
                'horaireVen3' => $horaire->horaireVen3,
                'horaireVen4' => $horaire->horaireVen4,
                'horaireSam1' => $horaire->horaireSam1,
                'horaireSam2' => $horaire->horaireSam2,
                'horaireSam3' => $horaire->horaireSam3,
                'horaireSam4' => $horaire->horaireSam4,
                'horaireDim1' => $horaire->horaireDim1,
                'horaireDim2' => $horaire->horaireDim2,
                'horaireDim3' => $horaire->horaireDim3,
                'horaireDim4' => $horaire->horaireDim4
            );
            $horaireNew = new Horaire($arrayHoraire);
            $this->managerHoraires->ajouter($horaireNew);

        endforeach;
    }

    /* Migration du personnel */
    /*
     * Copier la table de la V1 puis :
     * ALTER TABLE `personnels` CHANGE `id` `personnelId` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `id_etablissement` `personnelEtablissementId` INT(11) NOT NULL, CHANGE `nom` `personnelNom` VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `prenom` `personnelPrenom` VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `qualif` `personnelQualif` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `actif` `personnelActif` TINYINT(2) NOT NULL DEFAULT '1' COMMENT '1=actif, 0=inactif 2 = auto', CHANGE `code` `personnelCode` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `message` `personnelMessage` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
     * ALTER TABLE `personnels` DROP `personnelType`;
     * ALTER TABLE `personnels` ADD `personnelEquipeId` INT NULL AFTER `personnelHoraireId` DEFAULT NULL, ADD INDEX (`personnelEquipeId`);
     * ALTER TABLE `personnels` CHANGE `personnelHoraireId` `personnelHoraireId` INT(11) NULL;
     * UPDATE `personnels` SET `personnelHoraireId` = null WHERE `personnelHoraireId` = 0
     * ALTER TABLE `personnels` ADD FOREIGN KEY (`personnelHoraireId`) REFERENCES `horaires`(`horaireId`) ON DELETE SET NULL ON UPDATE CASCADE;
     * ALTER TABLE `personnels` ADD FOREIGN KEY (`personnelEquipeId`) REFERENCES `equipes`(`equipeId`) ON DELETE SET NULL ON UPDATE CASCADE;
     */

    private function importPersonnels(Etablissement $etablissement) {
        foreach ($this->db->select('*')->from('V1_personnel')->where('id_etablissement', $etablissement->getEtablissementOriginId())->get()->result() as $perso):

            $horaire = $this->managerHoraires->getHoraireByIdMigration($perso->personnelHoraireId);

            $arrayPersonnel = array(
                'personnelOriginId' => $perso->id,
                'personnelId' => $perso->id,
                'personnelEtablissementId' => $etablissement->getEtablissementId(),
                'personnelNom' => $perso->nom,
                'personnelPrenom' => $perso->prenom,
                'personnelQualif' => $perso->qualif,
                'personnelActif' => $perso->actif == 1 ? 1 : 0,
                'personnelMessage' => $perso->message,
                'personnelCode' => $perso->code,
                'personnelHoraireId' => $horaire ? $horaire->getHoraireId() : null,
                'personnelPointages' => $horaire ? 2 : 1,
                'personnelEquipeId' => null,
                'personnelType' => 1
            );
            $personnel = new Personnel($arrayPersonnel);
            $this->managerPersonnels->ajouter($personnel);

            $this->importIndisponibilites($personnel);
            $this->importPointages($personnel);
        endforeach;
    }

    /* Migration des taux horaire
     * Copier la table de la V1 en ajoutant un "s" puis :
     *
     * ALTER TABLE `tauxHoraires` ADD FOREIGN KEY (`tauxPersonnelId`) REFERENCES `personnels`(`personnelId`) ON DELETE CASCADE ON UPDATE RESTRICT;
     * ALTER TABLE `tauxHoraires` CHANGE `tauxHoraire` `tauxHoraire` DECIMAL(5,2) NOT NULL;
     * ALTER TABLE `tauxHoraires` CHANGE `tauxId` `tauxHoraireId` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `tauxPersonnelId` `tauxHorairePersonnelId` INT(11) NOT NULL, CHANGE `tauxDate` `tauxHoraireDate` INT(11) NOT NULL;
     */

    private function importTauxHoraire(Etablissement $etablissement) {

        $personnels = $this->managerPersonnels->getPersonnelsMigration(array('personnelEtablissementId' => $etablissement->getEtablissementId()));

        foreach ($personnels as $personnel):
            foreach ($this->db->select('*')->from('V1_tauxHoraire')->where('tauxPersonnelId', $personnel->getPersonnelOriginId())->get()->result() as $to):

                $arrayTaux = array(
                    'tauxHorairePersonnelId' => $personnel->getPersonnelId(),
                    'tauxHoraire' => $to->tauxHoraire,
                    'tauxHoraireDate' => $to->tauxDate
                );
                $taux = new TauxHoraire($arrayTaux);
                $this->managerTauxHoraires->ajouter($taux);

            endforeach;
        endforeach;
    }

    /* Clients
     * Nouvelle fonction, créer la table avec :
     * CREATE TABLE `clients` (
      `clientId` int(11) NOT NULL,
      `clientEtablissementId` int(11) NOT NULL,
      `clientNom` varchar(120) NOT NULL,
      `clientAdresse` varchar(255) NOT NULL,
      `clientCp` varchar(10) NOT NULL,
      `clientVille` varchar(120) NOT NULL,
      `clientPays` varchar(40) NOT NULL,
      `clientFixe` varchar(40) NOT NULL,
      `clientPortable` varchar(40) NOT NULL,
      `clientEmail` varchar(120) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      ALTER TABLE `clients`
      ADD PRIMARY KEY (`clientId`),
      ADD KEY `clientEtablissementId` (`clientEtablissementId`);

      ALTER TABLE `clients`
      MODIFY `clientId` int(11) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `clients`
      ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`clientEtablissementId`) REFERENCES `etablissements` (`etablissementId`) ON DELETE CASCADE;
     */

    /* Places
     * Nouvelle fonction, créer la table avec :
     *
      CREATE TABLE `places` (
      `placeId` int(11) NOT NULL,
      `placeClientId` int(11) NOT NULL,
      `placeEtablissementId` int(11) NOT NULL,
      `placeAdresse` varchar(255) NOT NULL,
      `placeLat` float NOT NULL,
      `placeLon` float NOT NULL,
      `placeDistance` int(11) NOT NULL COMMENT 'en metres',
      `placeDuree` int(11) NOT NULL COMMENT 'durée du trajet en secondes',
      `placeVolOiseau` int(11) NOT NULL COMMENT 'en metres',
      `placeZone` tinyint(2) NOT NULL COMMENT 'Zone déplacement',
      `placeGoogleId` varchar(120) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      ALTER TABLE `places`
      ADD PRIMARY KEY (`placeId`),
      ADD KEY `placeClientId` (`placeClientId`),
      ADD KEY `placeEtablissementId` (`placeEtablissementId`);

      ALTER TABLE `places`
      MODIFY `placeId` int(11) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `places`
      ADD CONSTRAINT `places_ibfk_1` FOREIGN KEY (`placeClientId`) REFERENCES `clients` (`clientId`) ON DELETE CASCADE;
      ALTER TABLE `places` ADD FOREIGN KEY (`placeEtablissementId`) REFERENCES `etablissements`(`etablissementId`) ON DELETE RESTRICT ON UPDATE RESTRICT;


     */

    /* Categories de chantiers
      Copier la table de la V1, puis,
      ALTER TABLE `categories` CHANGE `id_categorie` `categorieId` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `id_rs` `categorieRsId` INT(11) NOT NULL, CHANGE `nom` `categorieNom` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
      ALTER TABLE `categories` ADD FOREIGN KEY (`categorieRsId`) REFERENCES `raisonsSociales`(`rsId`) ON DELETE CASCADE ON UPDATE RESTRICT;
     */

    private function importCategories(RaisonSociale $rs) {

        foreach ($this->db->select('*')->from('V1_categorie')->where('id_rs', $rs->getRsOriginId())->get()->result() as $cat):

            $arrayCategorie = array(
                'categorieOriginId' => $cat->id_categorie,
                'categorieRsId' => $rs->getRsId(),
                'categorieNom' => mb_strtoupper($cat->nom)
            );
            $categorie = new Categorie($arrayCategorie);
            $this->managerCategories->ajouter($categorie);

        endforeach;
    }

    /* Intégration des affaires
     * Copier la table dossiers de la V1 en "dossiersV1"
     * créer la table affaires avec :

      CREATE TABLE `affaires` (
      `affaireId` int(11) NOT NULL,
      `affaireOriginId` int(11) NOT NULL,
      `affaireEtablissementId` int(11) NOT NULL,
      `affaireCommercialId` int(11) DEFAULT NULL COMMENT 'Id du user commercial lié',
      `affaireClientId` int(11) NOT NULL,
      `affaireDevis` varchar(40) NOT NULL,
      `affaireObjet` varchar(255) NOT NULL,
      `affairePrix` decimal(12,2) NOT NULL,
      `affaireDateSignature` int(11) DEFAULT NULL,
      `affaireDateCloture` int(11) DEFAULT NULL,
      `affaireEtat` tinyint(1) NOT NULL,
      `affaireCouleur` varchar(7) NOT NULL,
      `affaireCategorieId` int(11) DEFAULT NULL,
      `affaireRemarque` text NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      ALTER TABLE `affaires`
      ADD PRIMARY KEY (`affaireId`),
      ADD KEY `affaireEtablissementId` (`affaireEtablissementId`),
      ADD KEY `affaireCommercialId` (`affaireCommercialId`),
      ADD KEY `affaireClientId` (`affaireClientId`),
      ADD KEY `affaireCategorieId` (`affaireCategorieId`);

      ALTER TABLE `affaires`
      MODIFY `affaireId` int(11) NOT NULL AUTO_INCREMENT;
      ALTER TABLE `affaires` ADD `affaireCouleurSecondaire` VARCHAR(7) NOT NULL AFTER `affaireCouleur`;

      ALTER TABLE `affaires`
      ADD CONSTRAINT `affaires_ibfk_1` FOREIGN KEY (`affaireEtablissementId`) REFERENCES `etablissements` (`etablissementId`) ON DELETE CASCADE,
      ADD CONSTRAINT `affaires_ibfk_2` FOREIGN KEY (`affaireCategorieId`) REFERENCES `categories` (`categorieId`) ON DELETE SET NULL;
      ALTER TABLE `affaires` ADD `affairePlaceId` INT NULL DEFAULT NULL AFTER `affaireEtablissementId`, ADD INDEX (`affairePlaceId`);

     */

    public function importDossiers(Etablissement $etablissement, $migrationPlace) {

        $creation = 0;

        foreach ($this->db->select('*')->from('V1_dossier')
                ->where(array('id_etablissement' => $etablissement->getEtablissementOriginId()))
                ->order_by('dossierId DESC')
                //->limit(5, 0)
                ->get()->result() as $dossier):

            /**
             * Si on a une date de signature, on la prend comme date de création également
             * Si on n'a pas de date de signature, on prend la date de création du précedent dossier comme date de création
             */
            if ($dossier->date_signature > 0):
                $creation = $dossier->date_signature;
            endif;

            $this->db->trans_start();

            if ($migrationPlace == 1):

                /* Création du client */
                /* Recherche d'une place identique et donc d'un client déjà validé */

                $result = $this->maps->geocode(urlencode($dossier->adresse . ' ' . $dossier->cp . ' ' . $dossier->ville . ' FRANCE'), $etablissement);
                if ($result):
                    $placeExistante = $this->managerPlaces->getPlaceByGoogle($result['placeGoogleId'], $etablissement->getEtablissementId());
                    if ($placeExistante):
                        $client = $this->managerClients->getClientByIdMigration($placeExistante->getPlaceClientId());
                        $place = $placeExistante;
                    else:

                        $dataClient = array(
                            'clientEtablissementId' => $etablissement->getEtablissementId(),
                            'clientNom' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? 'DIVERS - ' . $etablissement->getEtablissementNom() : mb_strtoupper($dossier->client),
                            'clientAdresse' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementAdresse() : $dossier->adresse,
                            'clientCp' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementCp() : $dossier->cp,
                            'clientVille' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementVille() : $dossier->ville,
                            'clientPays' => 'FRANCE',
                            'clientFixe' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementTelephone() : ( strlen($dossier->tel) > 9 ? $dossier->tel : ''),
                            'clientPortable' => '',
                            'clientEmail' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementEmail() : $dossier->email
                        );
                        $client = new Client($dataClient);
                        $this->managerClients->ajouter($client);

                        $volOiseau = $this->maps->distanceVolOiseau(explode(',', $etablissement->getEtablissementGps())[0], explode(',', $etablissement->getEtablissementGps())[1], $result['latitude'], $result['longitude']);
                        $zone = floor($volOiseau / 10000) + 1;
                        if ($zone > 6):
                            $zone = 6;
                        endif;

                        $arrayPlace = array(
                            'placeClientId' => $client->getClientId(),
                            'placeEtablissementId' => $etablissement->getEtablissementId(),
                            'placeLat' => $result['latitude'],
                            'placeLon' => $result['longitude'],
                            'placeAdresse' => $result['adresse'],
                            'placeVille' => $result['ville'],
                            'placeGoogleId' => $result['placeGoogleId'],
                            'placeDistance' => $result['distance'],
                            'placeDuree' => $result['duree'],
                            'placeZone' => $zone,
                            'placeVolOiseau' => $volOiseau
                        );

                        $place = new Place($arrayPlace);
                        $this->managerPlaces->ajouter($place);
                    endif;
                else:
                    log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Erreur de geoCodage');
                endif;
            else:
                $dataClient = array(
                    'clientEtablissementId' => $etablissement->getEtablissementId(),
                    'clientNom' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? 'DIVERS - ' . $etablissement->getEtablissementNom() : mb_strtoupper($dossier->client),
                    'clientAdresse' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementAdresse() : $dossier->adresse,
                    'clientCp' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementCp() : $dossier->cp,
                    'clientVille' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementVille() : $dossier->ville,
                    'clientPays' => 'FRANCE',
                    'clientFixe' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementTelephone() : ( strlen($dossier->tel) > 9 ? $dossier->tel : ''),
                    'clientPortable' => '',
                    'clientEmail' => ($dossier->dossierId == $etablissement->getEtablissementAffaireDiversId()) ? $etablissement->getEtablissementEmail() : $dossier->email
                );
                $client = new Client($dataClient);
                $this->managerClients->ajouter($client);
            endif;

            /* Evite les soucis de catégories fantômes */
            $categorie = null;
            if ($dossier->dossierCategorieId != 0):
                $categorieOrigin = $this->managerCategories->getCategorieByOriginId($dossier->dossierCategorieId);
                if ($categorieOrigin):
                    $categorie = $categorieOrigin->getCategorieId();
                endif;
            endif;

            /* Etat des dossiers */
            switch ($dossier->dossierEtat):
                case 'Termine':
                    $etat = 3;
                    break;
                case 'Encours':
                    $etat = 2;
                    break;
                case 'Devis':
                    $etat = 1;
                    break;
            endswitch;

            /* Creation d'une affaire */
            $arrayAffaire = array(
                'affaireOriginId' => $dossier->dossierId,
                'affaireEtablissementId' => $etablissement->getEtablissementId(),
                'affaireCreation' => $creation,
                'affaireClientId' => $client->getClientId(),
                'affaireCategorieId' => $categorie,
                'affaireCommercialId' => $this->managerUtilisateurs->getUtilisateurByOriginId($dossier->id_commercial)->getId(),
                'affairePlaceId' => !empty($place) ? $place->getPlaceId() : null,
                'affaireDevis' => $dossier->devis,
                'affairePrix' => $dossier->dossierPrix,
                'affaireObjet' => $dossier->dossierObjet,
                'affaireDateSignature' => $dossier->date_signature ?: null,
                'affaireDateCloture' => $dossier->date_solde ?: null,
                'affaireEtat' => $etat,
                'affaireCouleur' => $dossier->dossierCouleur,
                'affaireCouleurSecondaire' => $this->couleurSecondaire($dossier->dossierCouleur),
                'affaireRemarque' => $dossier->dossierRemarque
            );

            $affaire = new Affaire($arrayAffaire);
            $this->managerAffaires->ajouter($affaire);

            $this->db->trans_complete();

            $this->importChantiers($affaire, $migrationPlace);

        endforeach;
    }

    /**
     * Copier la table organibat_chantier de la V1 puis
     *
     *
      CREATE TABLE `chantiers` (
      `chantierId` int(11) NOT NULL,
      `chantierOriginId` int(11) NOT NULL COMMENT 'Id du chantier d''origine',
      `chantierAffaireId` int(11) NOT NULL,
      `chantierObjet` varchar(255) NOT NULL,
      `chantierCategorieId` int(11) DEFAULT NULL,
      `chantierPrix` decimal(10,2) NOT NULL,
      `chantierCouleur` varchar(7) NOT NULL,
      `chantierCouleurSecondaire` varchar(7) NOT NULL,
      `chantierEtat` tinyint(1) NOT NULL,
      `chantierDateCloture` int(11) DEFAULT NULL,
      `chantierHeuresPrevues` decimal(6,2) NOT NULL,
      `chantierBudgetAchats` decimal(10,2) NOT NULL,
      `chantierFraisGeneraux` decimal(4,2) NOT NULL,
      `chantierTauxHoraireMoyen` decimal(5,2) NOT NULL,
      `chantierRemarque` text NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      ALTER TABLE `chantiers`
      ADD PRIMARY KEY (`chantierId`),
      ADD KEY `chantierAffaireId` (`chantierAffaireId`),
      ADD KEY `chantierCategorieId` (`chantierCategorieId`);

      ALTER TABLE `chantiers`
      MODIFY `chantierId` int(11) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `chantiers`
      ADD CONSTRAINT `chantiers_ibfk_1` FOREIGN KEY (`chantierAffaireId`) REFERENCES `affaires` (`affaireId`) ON DELETE CASCADE,
      ADD CONSTRAINT `chantiers_ibfk_2` FOREIGN KEY (`chantierCategorieId`) REFERENCES `categories` (`categorieId`) ON DELETE SET NULL;
      ALTER TABLE `chantiers` ADD `chantierPlaceId` INT NULL DEFAULT NULL AFTER `chantierAffaireId`, ADD INDEX (`chantierPlaceId`);
      ALTER TABLE `chantiers` ADD FOREIGN KEY (`affairePlaceId`) REFERENCES `places`(`placeId`) ON DELETE SET NULL ON UPDATE RESTRICT;

     */
    public function importChantiers(Affaire $affaire, $migrationPlace) {

        foreach ($this->db->select('*')->from('V1_chantier')->where('dossier_id', $affaire->getAffaireOriginId())->get()->result() as $chant):

            /* Evite les soucis de catégories fantômes */
            $categorie = null;
            if ($chant->id_categorie != 0):
                $categorieOrigin = $this->managerCategories->getCategorieByOriginId($chant->id_categorie);
                if ($categorieOrigin):
                    $categorie = $categorieOrigin->getCategorieId();
                endif;
            endif;

            $dataChantier = array(
                'chantierAffaireId' => $affaire->getAffaireId(),
                'chantierPlaceId' => $affaire->getAffairePlaceId(),
                'chantierOriginId' => $chant->id,
                'chantierCategorieId' => $categorie,
                'chantierObjet' => $chant->objet,
                'chantierPrix' => $chant->prix,
                'chantierEtat' => $chant->etat == 'Termine' ? 2 : 1,
                'chantierDateCloture' => $chant->cloture,
                'chantierHeuresPrevues' => ($chant->nb_heures_prev > 0) ? $chant->nb_heures_prev : 1,
                'chantierBudgetAchats' => $chant->budgetAchat,
                'chantierFraisGeneraux' => $chant->chantierFraisGeneraux,
                'chantierTauxHoraireMoyen' => $chant->chantierTxHoraireMoyen,
                'chantierRemarque' => $chant->remarque,
                'chantierCouleur' => $chant->couleur,
                'chantierCouleurSecondaire' => $this->couleurSecondaire($chant->couleur)
            );
            $chantier = new Chantier($dataChantier);
            $this->managerChantiers->ajouter($chantier);

            $this->importCouts($chantier);
            $this->importLivraisons($chantier);

            $affaire->hydratePlace();
            $this->importAffectations($chantier, $affaire, $migrationPlace);

            $chantier->hydrateAchats();
            if (!empty($chantier->getChantierAchats())):
                foreach ($chantier->getChantierAchats() as $achat):
                    /* on recherche les liens incluant cette livraison dans la table V1 */
                    foreach ($this->db->select('*')->from('V1_livraison_contrainte')->where('lcLivraisonId', $achat->getAchatLivraisonOriginId())->get()->result() as $contrainte):
                        $affectation = $this->managerAffectations->getAffectationByOriginId($contrainte->lcAffectationId);
                        if ($affectation):
                            $nbExist = $this->db->select('COUNT(*) as nb')->from('achats_affectations')->where(array('affectationId' => $affectation->getAffectationId(), 'achatId' => $achat->getAchatId()))->get()->result()[0]->nb;
                            if ($nbExist == 0):
                                $this->db
                                        ->set('achatId', $achat->getAchatId())
                                        ->set('affectationId', $affectation->getAffectationId())
                                        ->insert('achats_affectations');
                            endif;
                        endif;
                    endforeach;
                endforeach;
            endif;
        endforeach;
    }

    /**
     * Vérifier les couleurs de chantier qui ne seraient pas au format #FFFFFF (2 dans les tables de test)
     *
     *
     * Passer les affaires et les chantiers de la categorie NC de la RS à Null et supprimer le champs raisonSociale->rsCategorieNC
     *
     *
     * Migration des couts
     * Copier la table Couts de la V1 puis lancer le script
     */
    public function importCouts(Chantier $chantier) {
        foreach ($this->db->select('*')->from('V1_cout')->where('coutChantierId', $chantier->getChantierOriginId())->get()->result() as $cout):

            switch ($cout->coutType):
                case 0:
                    $type = 1;
                    break;
                case 1:
                case 2:
                    $type = 2;
                    break;
                case 3:
                    $type = 3;
                    break;
                default:
                    $type = 1;
            endswitch;

            $dataAchat = array(
                'achatChantierId' => $chantier->getChantierId(),
                'achatDate' => $cout->coutDate,
                'achatDescription' => $cout->coutDescription,
                'achatType' => $type,
                'achatQte' => $cout->coutPrevisionnel ? 0 : $cout->coutQuantite,
                'achatQtePrevisionnel' => $cout->coutQuantite,
                'achatprix' => round($cout->coutPrix / $cout->coutQuantite, 2),
                'achatPrixPrevisionnel' => round($cout->coutPrix / $cout->coutQuantite, 2),
                'achatFournisseurId' => null,
                'achatLivraisonDate' => null,
                'achatLivraisonOriginId' => null,
                'achatLivraisonAvancement' => null
            );
            $achat = new Achat($dataAchat);
            $this->managerAchats->ajouter($achat);
            unset($achat);

        endforeach;
    }

    public function importLivraisons(Chantier $chantier) {

        /**
         * Pour chaque livraison de la V1, on créé un achat sans valeur dans le chantier correspondant afin de créer les contraintes sur les affectations
         */
        foreach ($this->db->select('*')->from('V1_livraison')->where('livraisonChantierId', $chantier->getChantierOriginId())->get()->result() as $liv):

            $fournisseur = $this->managerFournisseurs->getFournisseurByOriginId($liv->livraisonFournisseurId);

            $dataAchat = array(
                'achatChantierId' => $chantier->getChantierId(),
                'achatDate' => $liv->livraisonDate,
                'achatDescription' => $liv->livraisonRemarque,
                'achatType' => 2,
                'achatQte' => 1,
                'achatQtePrevisionnel' => 1,
                'achatprix' => 0,
                'achatPrixPrevisionnel' => 0,
                'achatFournisseurId' => $fournisseur ? $fournisseur->getFournisseurId() : null,
                'achatLivraisonDate' => $liv->livraisonDate,
                'achatLivraisonOriginId' => $liv->livraisonId,
                'achatLivraisonAvancement' => ($liv->livraisonEtat + 1)
            );
            $achat = new Achat($dataAchat);
            $this->managerAchats->ajouter($achat);
            unset($achat);

        endforeach;
    }

    public function importAffectations(Chantier $chantier, Affaire $affaire, $migrationPlace = 0) {
        foreach ($this->db->select('*')->from('V1_affectation')->where('id_chantier', $chantier->getChantierOriginId())->get()->result() as $affect):

            if ($affect->etat == 'Termine'):
                $etat = 2;
            else:
                $etat = 1;
            endif;

//            if ($migrationPlace == 1):
//
//                similar_text(strtoupper($affect->affectationAdresse), strtoupper(explode(',', $affaire->getAffairePlace()->getPlaceAdresse())[0]), $percent);
//                if ($percent < 60 && $affect->affectationAdresse != ''):
//                    /* On créé une nouvelle place pour ce client */
//                    log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ------------ Affect ' . $affect->id . ' -- ' . $percent . '% ----');
//                    log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Nouvelle place pour le client : ' . $affaire->getAffaireClientId());
//                    $etablissement = $this->managerEtablissements->getEtablissementById($affaire->getAffaireEtablissementId());
//                    $result = $this->maps->geocode(urlencode($affect->affectationAdresse . ', ' . $affect->affectationCp . ' ' . $affect->affectationVille . ', FRANCE'), $etablissement);
//                    if ($result):
//                        $selectedPlace = false;
//                        /* On recherche la même place qui existerai pour ce client */
//                        $affaire->hydrateClient();
//                        $affaire->getAffaireClient()->hydratePlaces();
//                        foreach ($affaire->getAffaireClient()->getClientPlaces() as $place):
//                            if ($place->getPlaceGoogleId() == $result['placeGoogleId']):
//                                $selectedPlace = $place;
//                                continue;
//                            endif;
//                        endforeach;
//
//                        if (!$selectedPlace):
//
//                            $volOiseau = $this->maps->distanceVolOiseau(explode(',', $etablissement->getEtablissementGps())[0], explode(',', $etablissement->getEtablissementGps())[1], $result['latitude'], $result['longitude']);
//                            $zone = floor($volOiseau / 10000) + 1;
//                            if ($zone > 6):
//                                $zone = 6;
//                            endif;
//
//                            $arrayPlace = array(
//                                'placeClientId' => $affaire->getAffaireClientId(),
//                                'placeEtablissementId' => $etablissement->getEtablissementId(),
//                                'placeLat' => $result['latitude'],
//                                'placeLon' => $result['longitude'],
//                                'placeAdresse' => $result['adresse'],
//                                'placeVille' => $result['ville'],
//                                'placeGoogleId' => $result['placeGoogleId'],
//                                'placeDistance' => $result['distance'],
//                                'placeDuree' => $result['duree'],
//                                'placeZone' => $zone,
//                                'placeVolOiseau' => $volOiseau
//                            );
//
//                            $selectedPlace = new Place($arrayPlace);
//                            $this->managerPlaces->ajouter($selectedPlace);
//                        endif;
//                    else:
//                        log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Erreur de geoCodage');
//                    endif;
//                else:
//                    $selectedPlace = $affaire->getAffairePlace();
//                endif;
//            else:
//                $selectedPlace = $affaire->getAffairePlace();
//            endif;

            $personnel = $this->managerPersonnels->getPersonnelByIdMigration($affect->id_personnel);
            if ($personnel):

                $dataAffectation = array(
                    'affectationOriginId' => $affect->id,
                    'affectationChantierId' => $chantier->getChantierId(),
                    'affectationPersonnelId' => $personnel->getPersonnelId(),
                    'affectationPlaceId' => $chantier->getChantierPlaceId(),
                    'affectationNbDemi' => $affect->nb_demi,
                    'affectationDebutDate' => $this->own->mktimeFromInputDate(date('Y-m-d', $affect->debut)),
                    'affectationDebutMoment' => $affect->debut_journee + 1,
                    'affectationFinDate' => $this->own->mktimeFromInputDate(date('Y-m-d', $affect->fin)),
                    'affectationFinMoment' => $affect->fin_journee + 1,
                    'affectationCases' => $affect->longueur,
                    'affectationEtat' => $etat,
                    'affectationCommentaire' => $affect->commentaire,
                    'affectationType' => $affect->type + 1,
                    'affectationAffichage' => $affect->affichage + 1
                );
                $affectation = new Affectation($dataAffectation);
                $affectation->setAffectationCases($this->own->nbCasesAffectation($affectation));
                $this->managerAffectations->ajouter($affectation);
//                $affectation->calculHeuresPlanifiees();
//                $this->managerAffectations->editer($affectation);
                $this->importHeures($affectation);

            else:
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => Cette affectation n\'a pas été migrée (Origin) ' . $affect->id . '// Personnel invalide (Origin):' . $affect->id_personnel);
            endif;

        endforeach;
    }

    public function importHeures(Affectation $affectation) {
        foreach ($this->db->select('*')->from('V1_heure')->where('id_affectation', $affectation->getAffectationOriginId())->get()->result() as $heure):

            $dataHeure = array(
                'heureOriginId' => $heure->id,
                'heureAffectationId' => $affectation->getAffectationId(),
                'heureDate' => $heure->date,
                'heureDuree' => $heure->nb_heure * 60,
                'heureValide' => $heure->valide
            );
            $newHeure = new Heure($dataHeure);
            $this->managerHeures->ajouter($newHeure);

        endforeach;
    }

    public function importIndisponibilites(Personnel $personnel) {
        foreach ($this->db->select('*')->from('V1_indisponible')->where(array('id_personnel' => $personnel->getPersonnelOriginId()))->get()->result() as $indispo):

            $dataIndispo = array(
                'indispoPersonnelId' => $personnel->getPersonnelId(),
                'indispoDebutDate' => $indispo->debut,
                'indispoDebutMoment' => $indispo->debut_journee + 1,
                'indispoFinDate' => $indispo->fin,
                'indispoFinMoment' => $indispo->demi_fin + 1,
                'indispoMotifId' => ($indispo->type > 0) ? $indispo->type : 14,
                'indispoAffichage' => $indispo->affichage + 1,
                'indispoNbDemi' => $indispo->nb_demi,
                'indispoCases' => $this->cal->nbCasesEntreDates($indispo->debut, ($indispo->debut_journee + 1), $indispo->fin, ($indispo->demi_fin + 1))
            );
            $newIndispo = new Indisponibilite($dataIndispo);
            $this->managerIndisponibilites->ajouter($newIndispo);

        endforeach;
    }

    public function importPointages(Personnel $personnel) {
        foreach ($this->db->select('*')->from('V1_pointage')->where(array('pointagePersonnelId' => $personnel->getPersonnelOriginId()))->get()->result() as $pointage):

            $dataPointage = array(
                'pointagePersonnelId' => $personnel->getPersonnelId(),
                'pointageMois' => $pointage->pointageMois,
                'pointageAnnee' => $pointage->pointageAnnee,
                'pointageHTML' => $pointage->pointageHTML
            );
            $pointage = new Pointage($dataPointage);
            $this->managerPointages->ajouter($pointage);
            unset($pointage);

        endforeach;
    }

}
