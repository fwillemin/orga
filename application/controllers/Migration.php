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
     * Copier la table user de la v1 en users
     *
     */
}
