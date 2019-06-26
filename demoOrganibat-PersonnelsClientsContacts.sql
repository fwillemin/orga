SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
INSERT INTO `clients` (`clientId`, `clientEtablissementId`, `clientNom`, `clientAdresse`, `clientCp`, `clientVille`, `clientPays`, `clientFixe`, `clientPortable`, `clientEmail`) VALUES
(1, 11, 'WILLEMIN FRANCOIS', '13 RUE BASSE', '59530', 'LOUVIGNIES QUESNOY', '', '', '0651731808', ''),
(2, 11, 'SARL MERCURE', 'Place du Canada', '59300', 'VALENCINNES', '', '', '0651731808', ''),
(3, 11, 'DECOLE REMI', 'RUE DE LILLE', '59870', 'MARCHIENNES', '', '', '', ''),
(4, 11, 'SCI WILLEMIN', '2638 RUE GEORGES OZANEAUX', '59530', 'VILLERS POL', '', '', '0651731808', ''),
(5, 11, 'DECO2000', '12 rue nationale', '59000', 'Lille', '', '', '', '');
INSERT INTO `contacts` (`contactId`, `contactEtablissementId`, `contactDate`, `contactMode`, `contactNom`, `contactAdresse`, `contactCp`, `contactVille`, `contactTelephone`, `contactEmail`, `contactObjet`, `contactCategorieId`, `contactSource`, `contactCommercialId`, `contactEtat`) VALUES
(1, 11, 1556661600, 1, 'WILLEMIN', 'Rue Ozaneaux', 59530, 'VILLERS POL', '0651731808', '', 'Renovation de toiture', 2, 4, 0, 4),
(2, 11, 1557439200, 2, 'DEPROS', '', 59300, 'Valenciennes', '0327000001', '', 'Sablage de façades', 5, 1, 47, 1),
(3, 11, 1556402400, 3, 'MERX', 'place du cabada', 59300, 'Valenciennes', '032701020304', '', 'Besoin de contruire une maison à orsinval', 6, 6, 47, 2);
INSERT INTO `personnels` (`personnelId`, `personnelOriginId`, `personnelEtablissementId`, `personnelNom`, `personnelPrenom`, `personnelQualif`, `personnelType`, `personnelActif`, `personnelCode`, `personnelPortable`, `personnelMessage`, `personnelHoraireId`, `personnelPointages`, `personnelEquipeId`, `personnelSoldeRTT`) VALUES
(1, 0, 11, 'WILLEMIN', 'François', 'Couvreur N2P2', 1, 1, '0000', '0651731808', '', 1, 1, 1, NULL),
(2, 0, 11, 'SAGLON', 'Vincent', 'Couvreur N3P3', 1, 1, '0000', '', '', 1, 1, 1, NULL),
(3, 0, 11, 'CIARGO', 'Dave', 'Plaquiste N1P1', 1, 1, '0000', '', '', 1, 1, 2, NULL),
(4, 0, 11, 'JUST', 'Adam', 'Plaquiqte N3P3', 3, 1, '0000', '', '', 1, 1, 2, NULL),
(5, 0, 11, 'FRANCK', 'MACON', 'MACON', 1, 1, '0000', '', '', 1, 1, NULL, NULL); 
INSERT INTO `places` (`placeId`, `placeClientId`, `placeEtablissementId`, `placeAdresse`, `placeVille`, `placeLat`, `placeLon`, `placeDistance`, `placeDuree`, `placeVolOiseau`, `placeZone`, `placeGoogleId`) VALUES
(1, 1, 11, '59530 Louvignies-Quesnoy, France', 'Louvignies-Quesnoy', 50.2281, 3.64594, 7568, 612, 6050, 1, 'ChIJdxuLSe6LwkcRaGC4slVma_w'),
(2, 2, 11, 'Place du Canada, 59300 Valenciennes, France', 'Valenciennes', 50.3528, 3.52266, 12967, 884, 10754, 2, 'ChIJF4bLjb7twkcRw7ahnLAKgl4'),
(3, 3, 11, 'Rue de Lille, 59870 Marchiennes, France', 'Marchiennes', 50.408, 3.27698, 40937, 1895, 28374, 3, 'ChIJkwasCo3DwkcR8qD3ZVIUaBI'),
(4, 4, 11, '2638 Rue Georges Ozaneaux, 59530 Villers-Pol, France', 'Villers-Pol', 50.2811, 3.62169, 105, 9, 112, 1, 'ChIJtTYzc7qMwkcRMFvev9Ldk-U'),
(5, 4, 11, '12 Rue Saint-Jean, 62520 Le Touquet-Paris-Plage, France', 'Le Touquet-Paris-Plage', 50.5246, 1.58289, 177961, 8313, 147257, 6, 'ChIJUSiOS8fQ3UcR3XrUq7hQ3SY'),
(6, 4, 11, 'Gare de Somain, 19 Rue Carnot, 59156 Lourches, France', 'Lourches', 50.3557, 3.27726, 33161, 1649, 25943, 4, 'ChIJ49u9EjvBwkcRFFjYIFUAckg'),
(7, 5, 11, '12 Rue Nationale, 59800 Lille, France', 'Lille', 50.6371, 3.06221, 64512, 2766, 56167, 6, 'ChIJl3M3zofVwkcRhEpk9Jrr-MU');
INSERT INTO `tauxHoraires` (`tauxHoraireId`, `tauxHorairePersonnelId`, `tauxHoraire`, `tauxHoraireDate`) VALUES
(1, 1, '17.50', 1546297200),
(2, 2, '19.00', 1546297200),
(3, 4, '25.00', 1546297200),
(4, 3, '16.00', 1546297200);
COMMIT;