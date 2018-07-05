<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migration extends CI_Controller {

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
    }

}
