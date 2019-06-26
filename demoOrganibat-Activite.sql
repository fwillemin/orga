SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
INSERT INTO `affaires` (`affaireId`, `affaireOriginId`, `affaireEtablissementId`, `affairePlaceId`, `affaireCreation`, `affaireCategorieId`, `affaireClientId`, `affaireCommercialId`, `affaireDevis`, `affaireObjet`, `affairePrix`, `affaireDateSignature`, `affaireDateCloture`, `affaireEtat`, `affaireCouleur`, `affaireCouleurSecondaire`, `affaireRemarque`, `affaireHeuresPointees`, `affaireCoutMo`, `affaireAchats`, `affaireFraisGeneraux`) VALUES
(1, NULL, 11, 3, 1559340000, 2, 3, 47, 'D12-3', 'Réparation d\'une toiture', '890.00', 1559340000, 1559752528, 3, '#cae09c', '#526824', '', '24.00', '438.00', '419.00', '106.80'),
(2, NULL, 11, 2, 1559752718, 7, 2, 47, 'D145', 'Renovation de 3 chambres', '7500.00', 1559340000, NULL, 2, '#8cbce8', '#144470', '', '0.00', '0.00', '0.00', '0.00'),
(3, NULL, 11, 1, 1559753043, 2, 1, 47, 'D458', 'Réalisation d\'une nouvelle toiture', '27000.00', 1559340000, NULL, 2, '#d486d1', '#5C0E59', '', '0.00', '0.00', '0.00', '0.00'),
(4, NULL, 11, 5, 1559753539, 2, 4, 47, 'D458', 'Réparation fuite veranda', '890.00', 1558303200, NULL, 2, '#bab420', '#FFFF98', '', '0.00', '0.00', '0.00', '0.00'),
(5, NULL, 11, 6, 1558303200, NULL, 4, 47, 'D258', 'Pose de parquet', '1700.00', 1558303200, 1559753737, 3, '#b82525', '#FF9D9D', '', '7.75', '193.75', '700.00', '204.00'),
(6, NULL, 11, 7, 1559753931, 8, 5, 47, 'D125', 'Installation robinetterie salle de bain', '840.00', 1558303200, 1559753931, 3, '#3ea189', '#B6FFFF', '', '5.50', '137.50', '0.00', '100.80'),
(7, NULL, 11, 6, 1559754078, 9, 4, 47, 'D155', 'Remplacement de la porte d\'entrée et porte de dépendance', '3450.00', NULL, NULL, 2, '#565882', '#CED0FA', '', '0.00', '0.00', '0.00', '0.00'),
(8, NULL, 11, 3, 1559755331, 5, 3, 47, 'D125', 'Sablage de façades', '17000.00', 1554069600, NULL, 2, '#67c476', '#004C00', '', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `chantiers` (`chantierId`, `chantierOriginId`, `chantierAffaireId`, `chantierPlaceId`, `chantierObjet`, `chantierCategorieId`, `chantierPrix`, `chantierCouleur`, `chantierCouleurSecondaire`, `chantierEtat`, `chantierDateCloture`, `chantierHeuresPrevues`, `chantierHeuresPlanifiees`, `chantierHeuresPointees`, `chantierBudgetAchats`, `chantierBudgetPrevu`, `chantierBudgetConsomme`, `chantierFraisGeneraux`, `chantierTauxHoraireMoyen`, `chantierRemarque`, `chantierBudgetPrevisionnel`, `chantierCoutMo`) VALUES
(1, NULL, 1, 3, 'Réparation de la toiture', 2, '890.00', '#cae09c', '#526824', 2, 1559752528, '16.00', '28.00', '24.00', '450.00', '450.00', '419.00', '12.00', '18.00', '', NULL, '438.00'),
(2, NULL, 2, 2, 'Chambre 1', 7, '2500.00', '#8cbce8', '#144470', 1, NULL, '40.00', '58.00', NULL, '350.00', '350.00', '375.00', '12.00', '18.00', '', NULL, NULL),
(3, NULL, 2, 2, 'Chambre 2', 7, '2500.00', '#5eaaf0', '#003278', 1, NULL, '40.00', '58.00', NULL, '350.00', '350.00', '375.00', '12.00', '18.00', '', NULL, NULL),
(4, NULL, 2, 2, 'Chambre 3', 7, '2500.00', '#27659e', '#9FDDFF', 1, NULL, '40.00', '58.00', NULL, '350.00', '350.00', '380.00', '12.00', '18.00', '', NULL, NULL),
(5, NULL, 3, 1, 'Mise en place de l\'échafauddage', NULL, '1200.00', '#d486d1', '#5C0E59', 1, NULL, '16.00', '14.00', NULL, '0.00', NULL, NULL, '12.00', '18.00', '', NULL, NULL),
(6, NULL, 3, 1, 'Démontage de la toiture existante', 2, '7000.00', '#d486d1', '#5C0E59', 1, NULL, '32.00', '16.00', NULL, '400.00', '0.00', '380.00', '12.00', '18.00', '', NULL, NULL),
(7, NULL, 3, 1, 'Toiture', 2, '18800.00', '#d486d1', '#5C0E59', 1, NULL, '80.00', '44.00', NULL, '2600.00', '2600.00', '2540.00', '12.00', '18.00', '', NULL, NULL),
(8, NULL, 4, 5, 'Réparation de fuite', 2, '890.00', '#bab420', '#FFFF98', 1, NULL, '16.00', '14.00', '11.00', '0.00', NULL, NULL, '12.00', '18.00', '', NULL, NULL),
(9, NULL, 5, 6, 'Pose du parquer 35m2', NULL, '1700.00', '#b82525', '#FF9D9D', 2, 1559753737, '8.00', '7.00', '7.75', '680.00', '700.00', '700.00', '12.00', '18.00', '', NULL, '193.75'),
(10, NULL, 6, 7, 'Pose de la robinetterie', 8, '840.00', '#3ea189', '#B6FFFF', 2, 1559753931, '4.00', '7.00', '5.50', '0.00', NULL, NULL, '12.00', '18.00', '', NULL, '137.50'),
(11, NULL, 7, 6, 'Prise de cotes', NULL, '100.00', '#565882', '#CED0FA', 1, NULL, '2.00', '3.00', '0.50', '0.00', NULL, NULL, '12.00', '18.00', '', NULL, NULL),
(12, NULL, 7, 6, 'Pose des portes', 9, '3350.00', '#565882', '#CED0FA', 1, NULL, '12.00', '14.00', NULL, '1700.00', '1750.00', '1570.00', '12.00', '18.00', '', NULL, NULL),
(13, NULL, 8, 3, 'Pose echaffaudafe', NULL, '1200.00', '#67c476', '#004C00', 1, NULL, '24.00', '15.00', '42.00', '0.00', NULL, NULL, '12.00', '18.00', '', NULL, NULL),
(14, NULL, 8, 3, 'Sabalge', 5, '4500.00', '#67c476', '#004C00', 1, NULL, '48.00', '36.00', NULL, '600.00', '650.00', '400.00', '12.00', '18.00', '', NULL, NULL),
(15, NULL, 8, 3, 'Rejointoiement', 5, '11300.00', '#67c476', '#004C00', 1, NULL, '80.00', '58.00', NULL, '1300.00', '900.00', '870.00', '12.00', '18.00', '', NULL, NULL);
INSERT INTO `affectations` (`affectationId`, `affectationOriginId`, `affectationChantierId`, `affectationPersonnelId`, `affectationPlaceId`, `affectationNbDemi`, `affectationDebutDate`, `affectationDebutMoment`, `affectationFinDate`, `affectationFinMoment`, `affectationCases`, `affectationCommentaire`, `affectationType`, `affectationAffichage`, `affectationHeuresPlanifiees`, `affectationHeuresPointees`) VALUES
(1, NULL, 1, 2, 3, 4, 1558908000, 1, 1558994400, 2, 4, '', 1, 1, '14.00', '12.00'),
(2, NULL, 1, 1, 3, 4, 1558908000, 1, 1558994400, 2, 4, '', 1, 1, '14.00', '12.00'),
(3, NULL, 2, 3, 2, 20, 1559512800, 1, 1560463200, 2, 24, '', 1, 1, '58.00', '0.00'),
(4, NULL, 3, 4, 2, 10, 1559512800, 1, 1559858400, 2, 10, '', 1, 1, '29.00', '0.00'),
(5, NULL, 3, 4, 2, 10, 1560117600, 1, 1560463200, 2, 10, '', 1, 1, '29.00', '0.00'),
(6, NULL, 4, 3, 2, 10, 1560722400, 1, 1561068000, 2, 10, '', 1, 1, '29.00', '0.00'),
(7, NULL, 4, 4, 2, 10, 1560722400, 1, 1561068000, 2, 10, '', 1, 1, '29.00', '0.00'),
(8, NULL, 5, 2, 1, 2, 1559599200, 1, 1559599200, 2, 2, '', 1, 1, '7.00', '0.00'),
(9, NULL, 5, 1, 1, 2, 1559599200, 1, 1559599200, 2, 2, '', 1, 1, '7.00', '0.00'),
(10, NULL, 6, 2, 1, 4, 1559685600, 1, 1559772000, 2, 4, '', 1, 1, '8.00', '0.00'),
(11, NULL, 6, 1, 1, 4, 1559685600, 1, 1559772000, 2, 4, '', 1, 1, '8.00', '0.00'),
(12, NULL, 7, 2, 1, 8, 1560117600, 1, 1560376800, 2, 8, '', 1, 1, '22.00', '0.00'),
(13, NULL, 7, 1, 1, 8, 1560117600, 1, 1560376800, 2, 8, '', 1, 1, '22.00', '0.00'),
(14, NULL, 8, 2, 5, 2, 1559080800, 1, 1559080800, 2, 2, '', 1, 1, '7.00', '5.50'),
(15, NULL, 8, 1, 5, 2, 1559080800, 1, 1559080800, 2, 2, '', 1, 1, '7.00', '5.50'),
(16, NULL, 9, 4, 6, 2, 1558908000, 1, 1558908000, 2, 2, '', 1, 1, '7.00', '7.75'),
(17, NULL, 10, 4, 7, 2, 1558994400, 1, 1558994400, 2, 2, '', 1, 2, '7.00', '5.50'),
(18, NULL, 11, 4, 6, 1, 1558994400, 2, 1558994400, 2, 1, '', 1, 3, '3.00', '0.50'),
(19, NULL, 12, 2, 6, 2, 1560463200, 1, 1560463200, 2, 2, '', 1, 1, '7.00', '0.00'),
(20, NULL, 12, 1, 6, 2, 1560463200, 1, 1560463200, 2, 2, '', 1, 1, '7.00', '0.00'),
(21, NULL, 13, 2, 3, 3, 1559167200, 1, 1559253600, 1, 3, '', 1, 1, '5.00', '14.00'),
(22, NULL, 13, 1, 3, 3, 1559167200, 1, 1559253600, 1, 3, '', 1, 1, '5.00', '14.00'),
(23, NULL, 14, 2, 3, 5, 1560722400, 1, 1560895200, 1, 5, '', 1, 1, '18.00', '0.00'),
(24, NULL, 14, 1, 3, 5, 1560722400, 1, 1560895200, 1, 5, '', 1, 1, '18.00', '0.00'),
(25, NULL, 15, 2, 3, 8, 1560981600, 1, 1561413600, 2, 12, '', 1, 1, '22.00', '0.00'),
(26, NULL, 15, 1, 3, 8, 1560981600, 1, 1561413600, 2, 12, '', 1, 1, '22.00', '0.00'),
(27, NULL, 15, 3, 3, 4, 1561327200, 1, 1561413600, 2, 4, '', 1, 1, '14.00', '0.00'),
(28, NULL, 13, 4, 3, 3, 1559167200, 1, 1559253600, 1, 3, '', 1, 1, '5.00', '14.00');
INSERT INTO `achats` (`achatId`, `achatChantierId`, `achatDate`, `achatDescription`, `achatType`, `achatFournisseurId`, `achatLivraisonOriginId`, `achatLivraisonDate`, `achatLivraisonAvancement`, `achatQtePrevisionnel`, `achatPrixPrevisionnel`, `achatQte`, `achatPrix`) VALUES
(1, 1, 1559685600, 'Tuiles', 1, 1, NULL, 1558908000, 1, '1.00', '450.00', '1.00', '419.00'),
(2, 2, 1559685600, 'Enduits et peinture', 1, 1, NULL, NULL, NULL, '1.00', '350.00', '1.00', '375.00'),
(3, 3, 1559685600, 'Enduits et peintures', 1, 1, NULL, NULL, NULL, '1.00', '350.00', '1.00', '375.00'),
(4, 4, 1559685600, 'Enduits et peintures', 1, 1, NULL, NULL, NULL, '1.00', '350.00', '1.00', '380.00'),
(5, 6, 1559599200, 'Location de chariot elevateur', 4, 2, NULL, 1559685600, 2, '0.00', '400.00', '1.00', '380.00'),
(6, 7, 1559772000, 'Tuiles', 1, 1, NULL, 1559858400, 2, '1.00', '2600.00', '1.00', '2540.00'),
(7, 9, 1559685600, 'Parquet stratifié', 1, 1, NULL, 1558562400, 2, '35.00', '20.00', '35.00', '20.00'),
(8, 12, 1559685600, 'Porte d\'entree', 1, 1, NULL, 1560117600, 2, '1.00', '950.00', '1.00', '920.00'),
(9, 12, 1559685600, 'Porte de dépendance', 1, 1, NULL, 1560117600, 2, '1.00', '800.00', '1.00', '650.00'),
(10, 14, 1559685600, 'Sable de facade', 1, 1, NULL, NULL, NULL, '1.00', '650.00', '1.00', '400.00'),
(11, 15, 1559685600, 'Enduit de rejointoiement blanc', 1, 1, NULL, NULL, NULL, '60.00', '15.00', '60.00', '14.50');
INSERT INTO `achats_affectations` (`achatId`, `affectationId`) VALUES
(1, 1),
(1, 2),
(5, 10),
(5, 11),
(6, 12),
(6, 13),
(7, 16),
(8, 19),
(9, 19),
(8, 20),
(9, 20);
INSERT INTO `heures` (`heureId`, `heureOriginId`, `heureAffectationId`, `heureDate`, `heureDuree`, `heureValide`) VALUES
(1, NULL, 1, 1558908000, 420, 1),
(2, NULL, 1, 1558994400, 300, 1),
(3, NULL, 2, 1558908000, 420, 1),
(4, NULL, 2, 1558994400, 300, 1),
(5, NULL, 16, 1558908000, 465, 1),
(6, NULL, 17, 1558994400, 330, 1),
(7, NULL, 18, 1558994400, 30, 1),
(8, NULL, 14, 1559080800, 330, 1),
(9, NULL, 15, 1559080800, 330, 1),
(10, NULL, 21, 1559167200, 420, 1),
(11, NULL, 22, 1559167200, 420, 1),
(12, NULL, 21, 1559253600, 420, 1),
(13, NULL, 22, 1559253600, 420, 1),
(14, NULL, 28, 1559167200, 420, 1),
(15, NULL, 28, 1559253600, 420, 1);
INSERT INTO `indisponibilites` (`indispoId`, `indispoPersonnelId`, `indispoDebutDate`, `indispoDebutMoment`, `indispoFinDate`, `indispoFinMoment`, `indispoNbDemi`, `indispoCases`, `indispoMotifId`, `indispoAffichage`) VALUES
(1, 2, 1559858400, 1, 1559858400, 2, 2, 2, 2, 1),
(2, 1, 1559858400, 1, 1559858400, 2, 2, 2, 2, 1),
(3, 3, 1558908000, 1, 1558994400, 2, 4, 4, 1, 1);
INSERT INTO `performanceChantiersPersonnels` (`performanceChantierId`, `performancePersonnelId`, `performanceHeuresPointees`, `performanceTauxParticipation`, `performanceImpactHeures`, `performanceImpactTaux`) VALUES
(1, 1, '12.00', '50.00', '4.00', '25.00'),
(1, 2, '12.00', '50.00', '4.00', '25.00'),
(9, 4, '7.75', '100.00', '-0.25', '-3.13'),
(10, 4, '5.50', '100.00', '1.50', '37.50');
COMMIT;