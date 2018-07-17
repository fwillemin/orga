<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migration extends My_Controller {

    const tauxTVA = 20;

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';
    }

    /* GLOBAL
     *
     * Copier la table organibat_raison_sociale de la v1 en raisonsSociales puis
     * ALTER TABLE `raisonsSociales` CHANGE `id` `rsId` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `nom` `rsNom` VARCHAR(2558) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `rs_inscription` `rsInscription` INT(11) NOT NULL, CHANGE `rs_mois_fiscal` `rsMoisFiscal` TINYINT(4) NOT NULL, CHANGE `rs_categorieNC` `rsCategorieNC` INT(11) NULL COMMENT 'id de la categorie Non classé associée à la rs';

     * Copier la table organibat_etablissement de la v1 en etablissements puis
     * ALTER TABLE `etablissements` DROP `periodicite_hs`, DROP `limit_hs`, DROP `nb_rtt_annuel`, DROP `majoration_hs`;
     * ALTER TABLE `etablissements` CHANGE `id` `etablissementId` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `id_rs` `etablissementRsId` INT(11) NOT NULL, CHANGE `nom` `etablissementNom` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `adresse` `etablissementAdresse` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `cp` `etablissementCp` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `ville` `etablissementVille` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `contact` `etablissementContact` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `tel` `etablissementTelephone` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `email` `etablissementEmail` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `gps` `etablissementGps` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `statut` `etablissementStatut` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1=principal 2=secondaire', CHANGE `id_chantier_divers` `etablissementChantierDiversId` INT(11) NULL, CHANGE `msg` `etablissementMessage` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'message diffusé sur la page de saisie des heures par les salariés', CHANGE `fraisGeneraux` `etablissementTauxFraisGeneraux` DECIMAL(5,2) NOT NULL DEFAULT '0', CHANGE `txHoraireMoyen` `etablissementTauxHoraireMoyen` DECIMAL(5,2) NOT NULL DEFAULT '0';
     * ALTER TABLE `etablissements` ADD FOREIGN KEY (`etablissementRsId`) REFERENCES `raisonsSociales`(`rsId`) ON DELETE CASCADE ON UPDATE RESTRICT;

     *
     * Copier la table user de la v1 en usersV1
     * ALTER TABLE `users` DROP `first_name`, DROP `last_name`, DROP `company`, DROP `phone`;
     * ALTER TABLE `users` ADD `userNom` VARCHAR(120) NOT NULL AFTER `password`, ADD `userPrenom` VARCHAR(120) NOT NULL AFTER `userNom`, ADD `userEtablissementId` INT NOT NULL AFTER `userPrenom`, ADD `userClairMdp` VARCHAR(120) NOT NULL AFTER `userEtablissementId`, ADD INDEX (`userEtablissementId`);
     * ALTER TABLE `users` ADD UNIQUE(`email`);
     * ALTER TABLE `users` ADD FOREIGN KEY (`userEtablissementId`) REFERENCES `etablissements`(`etablissementId`) ON DELETE CASCADE ON UPDATE RESTRICT;

     */

    private function getPassword($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    public function migrationUsers() {

        $idEta = 0;
        foreach ($this->db->select('*')->from('usersV1')->order_by('id_etablissement')->get()->result() as $user):

            if ($user->niveau != 5):

                $etablissement = $this->db->select('*')->from('etablissements')->where('etablissementId', $user->id_etablissement)->get()->result()[0];
                $domaine = strtolower(explode('@', $etablissement->etablissementEmail)[1]);

                $email = str_replace(array(' ', 'é', 'è'), array('', 'e', 'e'), strtolower($user->nom) . '.' . strtolower($user->prenom)) . '@' . $domaine;
                $identity = str_replace(array(' ', 'é', 'è'), array('', 'e', 'e'), strtolower($user->nom) . '.' . strtolower($user->prenom)) . '@' . $domaine;
                $mdp = $this->getPassword();
                $password = $mdp;

                $additional_data = array(
                    'userNom' => $user->nom,
                    'userPrenom' => $user->prenom,
                    'userEtablissementId' => $etablissement->etablissementId,
                    'userClairMdp' => $mdp,
                    'userCode' => 0000
                );
                /* Admin */
                if ($idEta != $etablissement->etablissementId):
                    $group = array('1, 10, 11');
                    $idEta = $etablissement->etablissementId;
                else:
                    $group = array('2');
                endif;

                $this->ion_auth->register($identity, $password, $email, $additional_data, $group);
            endif;

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

    /* Migration des taux horaire
     * Copier la table de la V1 en ajoutant un "s" puis :
     *
     * ALTER TABLE `tauxHoraires` ADD FOREIGN KEY (`tauxPersonnelId`) REFERENCES `personnels`(`personnelId`) ON DELETE CASCADE ON UPDATE RESTRICT;
     * ALTER TABLE `tauxHoraires` CHANGE `tauxHoraire` `tauxHoraire` DECIMAL(5,2) NOT NULL;
     * ALTER TABLE `tauxHoraires` CHANGE `tauxId` `tauxHoraireId` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `tauxPersonnelId` `tauxHorairePersonnelId` INT(11) NOT NULL, CHANGE `tauxDate` `tauxHoraireDate` INT(11) NOT NULL;
     */

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
     * ALTER TABLE `places` ADD FOREIGN KEY (`placeEtablissementId`) REFERENCES `etablissements`(`etablissementId`) ON DELETE RESTRICT ON UPDATE RESTRICT;

     */

    /* Categories de chantiers
      Copier la table de la V1, puis,
      ALTER TABLE `categories` CHANGE `id_categorie` `categorieId` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `id_rs` `categorieRsId` INT(11) NOT NULL, CHANGE `nom` `categorieNom` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
      ALTER TABLE `categories` ADD FOREIGN KEY (`categorieRsId`) REFERENCES `raisonsSociales`(`rsId`) ON DELETE CASCADE ON UPDATE RESTRICT;
     */


    /* Intégration des affaires
     * Copier la table dossiers de la V1 en "dossiersV1"
     * créer la table affaires avec :

      CREATE TABLE `affaires` (
      `affaireId` int(11) NOT NULL,
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

      ALTER TABLE `affaires`
      ADD CONSTRAINT `affaires_ibfk_1` FOREIGN KEY (`affaireEtablissementId`) REFERENCES `etablissements` (`etablissementId`) ON DELETE CASCADE,
      ADD CONSTRAINT `affaires_ibfk_2` FOREIGN KEY (`affaireCategorieId`) REFERENCES `categories` (`categorieId`) ON DELETE SET NULL;
     *
     */

    public function migrationDossiers() {

        foreach ($this->db->select('*')->from('dossiersV1')->get()->result() as $dossier):

            /* On garde l'établissement du dossier pour créer le client, les places et les affaires */
            $etablissement = $dossier->id_etablissement;
            /**
             * Si on a une date de signature, on la prend comme date de création également
             * Si on n'a pas de date de signature, on prend la date de création du précedent dossier comme date de création
             */
            if ($dossier->date_signature > 0):
                $creation = $dossier->date_signature;
            endif;

            $this->db->trans_start();
            /* Création du client */
            $dataClient = array(
                'clientEtablissementId' => $etablissement,
                'clientNom' => mb_strtoupper($dossier->client),
                'clientAdresse' => $dossier->adresse,
                'clientCp' => $dossier->cp,
                'clientVille' => $dossier->ville,
                'clientPays' => 'FRANCE',
                'clientFixe' => ( strlen($dossier->tel) > 9 ? $dossier->tel : ''),
                'clientPortable' => '',
                'clientEmail' => $dossier->email
            );
            $client = new Client($dataClient);
            $this->managerClients->ajouter($client);

            /* Creation d'une place */
            $result = $this->maps->geocode(urlencode($client->getClientAdresse() . ' ' . $client->getClientCp() . ' ' . $client->getClientVille() . ' FRANCE'));
            if ($result):

                $volOiseau = $this->maps->distanceVolOiseau(explode(',', $this->session->userdata('etablissementGPS'))[0], explode(',', $this->session->userdata('etablissementGPS'))[1], $result['latitude'], $result['longitude']);
                $zone = floor($volOiseau / 10000) + 1;
                if ($zone > 6):
                    $zone = 6;
                endif;

                $arrayPlace = array(
                    'placeClientId' => $client->getClientId(),
                    'placeEtablissementId' => $etablissement,
                    'placeLat' => $result['latitude'],
                    'placeLon' => $result['longitude'],
                    'placeAdresse' => $result['adresse'],
                    'placeGoogleId' => $result['placeGoogleId'],
                    'placeDistance' => $result['distance'],
                    'placeDuree' => $result['duree'],
                    'placeZone' => $zone,
                    'placeVolOiseau' => $volOiseau
                );

                $place = new Place($arrayPlace);
                $this->managerPlaces->ajouter($place);

            else:
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Erreur de geoCodage');
            endif;

            /* Evite les soucis de catégories fantômes */
            if ($dossier->dossierCategorieId != 0 && $this->existCategorie($dossier->dossierCategorieId)):
                $categorie = $dossier->dossierCategorieId;
            else:
                $categorie = null;
            endif;

            /* Creation d'une affaire */
            $arrayAffaire = array(
                'affaireEtablissementId' => $etablissement,
                'affaireCreation' => $creation,
                'affaireClientId' => $client->getClientId(),
                'affaireCategorieId' => $categorie,
                'affaireCommercialId' => $dossier->id_commercial,
                'affaireDevis' => $dossier->devis,
                'affairePrix' => $dossier->dossierPrix,
                'affaireObjet' => $dossier->dossierObjet,
                'affaireDateSignature' => $dossier->date_signature ?: null,
                'affaireDateCloture' => $dossier->date_solde ?: null,
                'affaireEtat' => $dossier->dossierEtat,
                'affaireCouleur' => $dossier->dossierCouleur,
                'affaireRemarque' => $dossier->dossierRemarque
            );

            $affaire = new Affaire($arrayAffaire);
            $this->managerAffaires->ajouter($affaire);

            $this->db->trans_complete();

        endforeach;
    }

    /**
     * Passer les affaires et les chantiers de la categorie NC de la RS à Null et supprimer le champs raisonSociale->rsCategorieNC
     */
    /**
     *
     * A DEVELOPPER
     *
     * Fusionner des clients
     */
}
