<?php

$config = array(
    'error_prefix' => '',
    'error_suffix' => '<br>',
    /* Connexion */
    'identification' => array(
        array(
            'field' => 'login',
            'label' => 'Login',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'pass',
            'label' => 'Mot de passe',
            'rules' => 'required|trim'
        )
    ),
    /* get RS */
    'getRs' => array(
        array(
            'field' => 'rsId',
            'label' => 'ID de la raison sociale',
            'rules' => 'required|callback_existRs'
        )
    ),
    /* Add Rs */
    'addRs' => array(
        array(
            'field' => 'addRsId',
            'label' => 'ID de la raison sociale',
            'rules' => 'callback_existRs'
        ),
        array(
            'field' => 'addRsNom',
            'label' => 'Nom la raison sociale',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addRsInscription',
            'label' => 'Inscription de la raison sociale',
            'rules' => 'trim|numeric'
        ),
        array(
            'field' => 'addRsMoisFiscal',
            'label' => 'Mois fiscal de la raison sociale',
            'rules' => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12]'
        ),
        array(
            'field' => 'addRsCategorieNC',
            'label' => 'ID Categorie NON CLASSEE de la raison sociale',
            'rules' => 'callback_existCategorie'
        )
    ),
    /* get Etablissement */
    'getEtablissement' => array(
        array(
            'field' => 'etablisementId',
            'label' => 'ID de l\'établissement',
            'rules' => 'required|callback_existEtablissement'
        )
    ),
    /* Add Etablissement */
    'addEtablissement' => array(
        array(
            'field' => 'addEtablissementId',
            'label' => 'ID de l\'établissement',
            'rules' => 'callback_existRs'
        ),
        array(
            'field' => 'addEtablissementRsId',
            'label' => 'ID de l\'établissement',
            'rules' => 'required|callback_existRs'
        ),
        array(
            'field' => 'addEtablissementNom',
            'label' => 'Nom de l\'établissement',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addEtablissementAdresse',
            'label' => 'Adresse de l\'établissement',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addEtablissementCp',
            'label' => 'Code postal de l\'établissement',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addEtablissementVille',
            'label' => 'Ville de l\'établissement',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addEtablissementContact',
            'label' => 'Contact de l\'établissement',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addEtablissementTelephone',
            'label' => 'Téléphone de l\'établissement',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addEtablissementEmail',
            'label' => 'Email de l\'établissement',
            'rules' => 'required|valid_email'
        ),
        array(
            'field' => 'addEtablissementMessage',
            'label' => 'Message de l\'établissement',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addEtablissementTauxFraisGeneraux',
            'label' => 'Taux de frais généraux de l\'établissement',
            'rules' => 'trim|numeric'
        ),
        array(
            'field' => 'addEtablissementTauxHoraireMoyen',
            'label' => 'Taux horaire moyen de l\'établissement',
            'rules' => 'trim|numeric'
        )
    ),
    /* Get Utilisateur */
    'getUtilisateur' => array(
        array(
            'field' => 'userId',
            'label' => 'ID de l\'utilisateur',
            'rules' => 'required|callback_existUtilisateur'
        )
    ),
    /* Add Etablissement */
    'addUtilisateur' => array(
        array(
            'field' => 'addUserId',
            'label' => 'ID de l\'utilisateur',
            'rules' => 'callback_existUtilisateur'
        ),
        array(
            'field' => 'addUserNom',
            'label' => 'Nom de l\'utilisateur',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addUserPrenom',
            'label' => 'Prénom de l\'utilisateur',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addUserEmail',
            'label' => 'Email de l\'utilisateur',
            'rules' => 'required|trim|valid_email'
        ),
        array(
            'field' => 'addUserPassword',
            'label' => 'Mot de passe de l\'utilisateur',
            'rules' => 'trim|min_length[8]|matches[addUserPasswordConfirm]|callback_passwordCheck'
        ),
        array(
            'field' => 'addUserPasswordConfirm',
            'label' => 'Confirmation',
            'rules' => 'trim'
        )
    ),
    /* Modification des droits d'acces */
    'modAcces' => array(
        array(
            'field' => 'userId',
            'label' => 'ID de l\'utilisateur',
            'rules' => 'required|callback_existUtilisateur'
        ),
        array(
            'field' => 'groupeId',
            'label' => 'ID Groupe',
            'rules' => 'required'
        ),
        array(
            'field' => 'acces',
            'label' => 'Etat',
            'rules' => 'required|in_list[0,1]'
        )
    ),
    /* get Horaire */
    'getHoraire' => array(
        array(
            'field' => 'horaireId',
            'label' => 'ID de l\'horaire',
            'rules' => 'required|callback_existHoraire'
        )
    ),
    /* Add Horaire */
    'addHoraire' => array(
        array(
            'field' => 'addHoraireId',
            'label' => 'ID de l\'horaire',
            'rules' => 'callback_existHoraire'
        ),
        array(
            'field' => 'addHoraireNom',
            'label' => 'Nom de l\'horaire',
            'rules' => 'trim|required'
        )
    ),
    /* get Equipe */
    'getEquipe' => array(
        array(
            'field' => 'equipeId',
            'label' => 'ID de l\'équipe',
            'rules' => 'required|callback_existEquipe'
        )
    ),
    /* Add Equipe */
    'addEquipe' => array(
        array(
            'field' => 'addEquipeId',
            'label' => 'ID de l\'équipe',
            'rules' => 'callback_existEquipe'
        ),
        array(
            'field' => 'addEquipeNom',
            'label' => 'Nom de l\'équipe',
            'rules' => 'trim|required'
        )
    ),
    /* get Personnel */
    'getPersonnel' => array(
        array(
            'field' => 'personnelId',
            'label' => 'ID du personnel',
            'rules' => 'required|callback_existPersonnel'
        )
    ),
    /* Add Equipe */
    'addPersonnel' => array(
        array(
            'field' => 'addPersonnelId',
            'label' => 'ID du personnel',
            'rules' => 'callback_existPersonnel'
        ),
        array(
            'field' => 'addPersonnelNom',
            'label' => 'Nom du personnel',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addPersonnelPrenom',
            'label' => 'Prenom du personnel',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addPersonnelQualif',
            'label' => 'Qualification du personnel',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addPersonnelCode',
            'label' => 'code personnel',
            'rules' => 'trim|numeric|exact_length[4]'
        ),
        array(
            'field' => 'addPersonnelMessage',
            'label' => 'Message personnel',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addPersonnelEquipeId',
            'label' => 'Equipe du personnel',
            'rules' => 'trim|callback_existEquipe'
        ),
        array(
            'field' => 'addPersonnelPointages',
            'label' => 'Pointages du personnel',
            'rules' => 'trim|in_list[1,2]'
        ),
        array(
            'field' => 'addPersonnelHoraireId',
            'label' => 'Horaire du personnel',
            'rules' => 'trim|callback_existHoraire'
        )
    ),
    /* get TauxHoraire */
    'getTauxHoraire' => array(
        array(
            'field' => 'tauxHoraireId',
            'label' => 'ID du taux horaire',
            'rules' => 'required|callback_existTauxHoraire'
        )
    ),
    /* Add TauxHoraire */
    'addTauxHoraire' => array(
        array(
            'field' => 'addTauxHoraireId',
            'label' => 'ID du taux horaire',
            'rules' => 'callback_existTauxHoraire'
        ),
        array(
            'field' => 'addTauxHorairePersonnelId',
            'label' => 'ID du personnel',
            'rules' => 'required|callback_existPersonnel'
        ),
        array(
            'field' => 'addTauxHoraireDate',
            'label' => 'Date de prise en compte',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addTauxHoraire',
            'label' => 'Taux horaire',
            'rules' => 'trim|required|numeric'
        )
    ),
    /* get Client */
    'getClient' => array(
        array(
            'field' => 'clientId',
            'label' => 'ID du client',
            'rules' => 'required|callback_existClient'
        )
    ),
    /* Add Client */
    'addClient' => array(
        array(
            'field' => 'addclientId',
            'label' => 'ID du client',
            'rules' => 'callback_existClient'
        ),
        array(
            'field' => 'addClientNom',
            'label' => 'Nom du client',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addClientAdresse',
            'label' => 'adresse du client',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addClientCp',
            'label' => 'Code postal du client',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addClientVille',
            'label' => 'Ville du client',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addClientPays',
            'label' => 'Pays du client',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addClientFixe',
            'label' => 'Téléphone fixe du client',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addClientPortable',
            'label' => 'Téléphone portable du client',
            'rules' => 'trim|callback_isPortable'
        ),
        array(
            'field' => 'addClientEmail',
            'label' => 'Email du client',
            'rules' => 'trim|valid_email'
        )
    ),
    /* get Place */
    'getPlace' => array(
        array(
            'field' => 'placeId',
            'label' => 'ID de la place',
            'rules' => 'required|callback_existPlace'
        )
    ),
    /* Add Place */
    'addPlace' => array(
        array(
            'field' => 'addPlaceId',
            'label' => 'ID de la place',
            'rules' => 'callback_existPlace'
        ),
        array(
            'field' => 'addPlaceClientId',
            'label' => 'ID du client',
            'rules' => 'required|callback_existClient'
        ),
        array(
            'field' => 'addPlaceAdresse',
            'label' => 'Adresse de la place',
            'rules' => 'required|trim'
        )
    ),
    /* get Categorie */
    'getCategorie' => array(
        array(
            'field' => 'categorieId',
            'label' => 'ID de la categorie',
            'rules' => 'required|callback_existCategorie'
        )
    ),
    /* Add Categorie */
    'addCategorie' => array(
        array(
            'field' => 'addCategorieId',
            'label' => 'ID de la categorie',
            'rules' => 'callback_existCategorie'
        ),
        array(
            'field' => 'addCategorieNom',
            'label' => 'Nom de la catégorie',
            'rules' => 'required|trim'
        )
    ),
    /* get Affaire */
    'getAffaire' => array(
        array(
            'field' => 'affaireId',
            'label' => 'ID de la affaire',
            'rules' => 'required|callback_existAffaire'
        )
    ),
    /* Add Affaire */
    'addAffaire' => array(
        array(
            'field' => 'addAffaireId',
            'label' => 'ID de l\'affaire',
            'rules' => 'callback_existAffaire'
        ),
        array(
            'field' => 'addAffairePlaceId',
            'label' => 'Localisation',
            'rules' => 'callback_existPlace'
        ),
        array(
            'field' => 'addAffaireClientId',
            'label' => 'ID du client',
            'rules' => 'required|callback_existClient'
        ),
        array(
            'field' => 'addAffaireCategorieId',
            'label' => 'Catégorie de l\' affaire',
            'rules' => 'callback_existCategorie'
        ),
        array(
            'field' => 'addAffaireCommercialId',
            'label' => 'ID du commercial',
            'rules' => 'callback_existUtilisateur'
        ),
        array(
            'field' => 'addAffaireDevis',
            'label' => 'N° Devis associé',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addAffaireObjet',
            'label' => 'Objet de l\'affaire',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addAffairePrix',
            'label' => 'Prix',
            'rules' => 'trim|numeric'
        ),
        array(
            'field' => 'addAffaireDateSignature',
            'label' => 'Date de signature du devis',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addAffaireCouleur',
            'label' => 'Couleur de l\'affaire',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addAffaireRemarque',
            'label' => 'Informations',
            'rules' => 'trim'
        )
    ),
    /* get Chantier */
    'getChantier' => array(
        array(
            'field' => 'chantierId',
            'label' => 'ID du chantier',
            'rules' => 'required|callback_existChantier'
        )
    ),
    /* Add Chantier */
    'addChantier' => array(
        array(
            'field' => 'addChantierId',
            'label' => 'ID du chantier',
            'rules' => 'callback_existChantier'
        ),
        array(
            'field' => 'addChantierPlaceId',
            'label' => 'Localisation',
            'rules' => 'callback_existPlace'
        ),
        array(
            'field' => 'addChantierAffaireId',
            'label' => 'ID de l\'affaire',
            'rules' => 'required|callback_existAffaire'
        ),
        array(
            'field' => 'addChantierCategorieId',
            'label' => 'Catégorie du chantier',
            'rules' => 'callback_existCategorie'
        ),
        array(
            'field' => 'addChantierObjet',
            'label' => 'Objet du chantier',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addChantierPrix',
            'label' => 'Prix',
            'rules' => 'trim|numeric'
        ),
        array(
            'field' => 'addChantierCouleur',
            'label' => 'Couleur du chantier',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addChantierRemarque',
            'label' => 'Informations',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addChantierHeuresPrevues',
            'label' => 'Nombres d\'heures prévues',
            'rules' => 'trim|numeric|required|greater_than[0]'
        ),
        array(
            'field' => 'addChantierBudgetAchats',
            'label' => 'Budget achats pour ce chantier',
            'rules' => 'trim|numeric|required'
        ),
        array(
            'field' => 'addChantierFraisGeneraux',
            'label' => 'Taux de frais généraux',
            'rules' => 'trim|numeric|required'
        ),
        array(
            'field' => 'addChantierTauxHoraireMoyen',
            'label' => 'Taux horaire moyen',
            'rules' => 'trim|numeric|required'
        )
    ),
    /* get Achat */
    'getAchat' => array(
        array(
            'field' => 'achatId',
            'label' => 'ID achat',
            'rules' => 'required|callback_existAchat'
        )
    ),
    /* Add Achat */
    'addAchat' => array(
        array(
            'field' => 'addAchatId',
            'label' => 'ID Achat',
            'rules' => 'callback_existAchat'
        ),
        array(
            'field' => 'addAchatChantierId',
            'label' => 'ID Chantier',
            'rules' => 'callback_existChantier'
        ),
        array(
            'field' => 'addAchatDate',
            'label' => 'Date',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addAchatFournisseurId',
            'label' => 'ID Fournisseur',
            'rules' => 'callback_existFournisseur'
        ),
        array(
            'field' => 'addAchatIndispoDate',
            'label' => 'Date',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addAchatIndispoAvancement',
            'label' => 'Avancement de la livraison',
            'rules' => 'in_list[0,1,2,3]'
        ),
        array(
            'field' => 'addAchatIndispoOriginId',
            'label' => 'ID livraison V1',
            'rules' => 'trim|numeric'
        ),
        array(
            'field' => 'addAchatDescription',
            'label' => 'Description',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addAchatType',
            'label' => 'Type achat',
            'rules' => 'required|in_list[1,2,3,4]'
        ),
        array(
            'field' => 'addAchatQtePrevisionnel',
            'label' => 'Quantité prévisionnelle',
            'rules' => 'numeric'
        ),
        array(
            'field' => 'addAchatPrixPrevisionnel',
            'label' => 'Prix unitaire prévisionnel',
            'rules' => 'numeric'
        ),
        array(
            'field' => 'addAchatQte',
            'label' => 'Quantité',
            'rules' => 'numeric'
        ),
        array(
            'field' => 'addAchatPrix',
            'label' => 'Prix unitaire',
            'rules' => 'numeric'
        )
    ),
    /* Modification des parametres */
    'modParametres' => array(
        array(
            'field' => 'nbSemainesAvant',
            'label' => 'Nombre de semaines passées du planning',
            'rules' => 'is_natural_no_zero|required'
        ),
        array(
            'field' => 'nbSemainesApres',
            'label' => 'Nombre de semaines futures du planning',
            'rules' => 'is_natural_no_zero|required'
        ),
        array(
            'field' => 'tranchePointage',
            'label' => 'Tranche pointage',
            'rules' => 'required|in_list[5,10,15,20,30]'
        ),
        array(
            'field' => 'tailleAffectations',
            'label' => 'Taille des affectations du planning',
            'rules' => 'required|in_list[1,2,3]'
        )
    ),
    /* get Affectation */
    'getAffectation' => array(
        array(
            'field' => 'affectationId',
            'label' => 'ID affectation',
            'rules' => 'required|callback_existAffectation'
        )
    ),
    /* Add Affectation */
    'addAffectation' => array(
        array(
            'field' => 'addAffectationId',
            'label' => 'ID Affectation',
            'rules' => 'callback_existAffectation'
        ),
        array(
            'field' => 'addAffectationChantierId',
            'label' => 'ID Chantier',
            'rules' => 'callback_existChantier|required'
        ),
//        array(
//            'field' => 'addAffectationPersonnelsIds',
//            'label' => 'ID personnels',
//            'rules' => 'required'
//        ),
        array(
            'field' => 'addAffectationDebutDate',
            'label' => 'Début',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addAffectationDebutMoment',
            'label' => 'Début à quel moment',
            'rules' => 'required|in_list[1,2]'
        ),
        array(
            'field' => 'addAffectationFinDate',
            'label' => 'Fin',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addAffectationFinMoment',
            'label' => 'Fin à quel moment',
            'rules' => 'required|in_list[1,2]'
        ),
        array(
            'field' => 'addAffectationCommentaire',
            'label' => 'Commentaire',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addAffectationType',
            'label' => 'Type',
            'rules' => 'required|in_list[1,2,3]'
        ),
        array(
            'field' => 'addAffectationAffichage',
            'label' => 'Affichage',
            'rules' => 'in_list[1,2,3]'
        )
    ),
    /* get Achat */
    'getHeure' => array(
        array(
            'field' => 'heureId',
            'label' => 'ID Heure',
            'rules' => 'required|callback_existHeure'
        )
    ),
    /* Add Heure */
    'addHeure' => array(
        array(
            'field' => 'heureId',
            'label' => 'ID Heure',
            'rules' => 'callback_existHeure'
        ),
        array(
            'field' => 'affectationId',
            'label' => 'ID Affectation',
            'rules' => 'required|callback_existAffectation'
        ),
        array(
            'field' => 'jour',
            'label' => 'Date',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'duree',
            'label' => 'Durée du pointage',
            'rules' => 'required|trim'
        )
    ),
    /* get fournisseur */
    'getFournisseur' => array(
        array(
            'field' => 'fournisseurId',
            'label' => 'ID Fournisseur',
            'rules' => 'required|callback_existFournisseur'
        )
    ),
    /* Add Fournisseur */
    'addFournisseur' => array(
        array(
            'field' => 'addFournisseurId',
            'label' => 'ID Fournisseur',
            'rules' => 'callback_existFournisseur'
        ),
        array(
            'field' => 'addFournisseurNom',
            'label' => 'Nom',
            'rules' => 'required|trim'
        ),
        array(
            'field' => 'addFournisseurAdresse',
            'label' => 'Adresse',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addFournisseurCp',
            'label' => 'Code postal',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addFournisseurVille',
            'label' => 'Ville',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addFournisseurTelephone',
            'label' => 'Téléphone',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addFournisseurEmail',
            'label' => 'Email',
            'rules' => 'valid_email|trim'
        )
    ),
    /* get Indispo */
    'getIndispo' => array(
        array(
            'field' => 'indispoId',
            'label' => 'ID Indispo',
            'rules' => 'required|callback_existIndispo'
        )
    ),
    /* Add Indispo */
    'addIndispo' => array(
        array(
            'field' => 'addIndispoId',
            'label' => 'ID Indispo',
            'rules' => 'callback_existIndispo'
        ),
//        array(
//            'field' => 'addIndispoPersonnelsIds',
//            'label' => 'ID Personnels',
//            'rules' => 'required'
//        ),
        array(
            'field' => 'addIndispoDebutDate',
            'label' => 'Date de début',
            'rules' => 'required'
        ),
        array(
            'field' => 'addIndispoDebutMoment',
            'label' => 'Moment de début',
            'rules' => 'required|in_list[1,2]'
        ),
        array(
            'field' => 'addIndispoFinDate',
            'label' => 'Date de fin',
            'rules' => 'required'
        ),
        array(
            'field' => 'addIndispoFinMoment',
            'label' => 'Moment de fin',
            'rules' => 'required|in_list[1,2]'
        ),
        array(
            'field' => 'addIndispoMotifId',
            'label' => 'ID du motif',
            'rules' => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12,13,14]'
        )
    ),
    /* get Pointage */
    'getPointage' => array(
        array(
            'field' => 'pointageId',
            'label' => 'ID Pointage',
            'rules' => 'required|callback_existPointage'
        )
    ),
    /* Add Pointage */
    'addPointage' => array(
        array(
            'field' => 'personnelId',
            'label' => 'ID Personnel',
            'rules' => 'required|callback_existPersonnel'
        ),
        array(
            'field' => 'periode',
            'label' => 'Mois-Annee du pointage',
            'rules' => 'required'
        ),
        array(
            'field' => 'pointageHTML',
            'label' => 'HTML du pointage',
            'rules' => 'trim'
        )
    ),
    /* get Contact */
    'getContact' => array(
        array(
            'field' => 'contactId',
            'label' => 'ID Contact',
            'rules' => 'required|callback_existContact'
        )
    ),
    /* Add Contact */
    'addContact' => array(
        array(
            'field' => 'addContactId',
            'label' => 'ID Contact',
            'rules' => 'callback_existContact'
        ),
        array(
            'field' => 'addContactDate',
            'label' => 'Date du contact',
            'rules' => 'required'
        ),
        array(
            'field' => 'addContactMode',
            'label' => 'Mode de contact',
            'rules' => 'required|in_list[1,2,3]'
        ),
        array(
            'field' => 'addContactNom',
            'label' => 'Nom du contact',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addContactAdresse',
            'label' => 'Adresse du contact',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addContactCp',
            'label' => 'Code postal du contact',
            'rules' => 'trim'
        ),
        array(
            'field' => 'addContactVille',
            'label' => 'Ville du contact',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addContactTelephone',
            'label' => 'Téléphone du contact',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addContactEmail',
            'label' => 'Email du contact',
            'rules' => 'trim|valid_email'
        ),
        array(
            'field' => 'addContactObjet',
            'label' => 'Objet du contact',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'addContactCategorieId',
            'label' => 'Catégorie du contact',
            'rules' => 'trim|callback_existCategorie'
        ),
        array(
            'field' => 'addContactSource',
            'label' => 'Source du contact',
            'rules' => 'trim|required|in_list[1,2,3,4,5,6]'
        ),
        array(
            'field' => 'addContactCommercialId',
            'label' => 'Commercial affecté au contact',
            'rules' => 'callback_existUtilisateur'
        ),
        array(
            'field' => 'addContactEtat',
            'label' => 'Mode de contact',
            'rules' => 'in_list[1,2,3,4,5]'
        )
    ),
    /* Lier Affectation à un chantier */
    'relierAffectation' => array(
        array(
            'field' => 'lierAffectationId',
            'label' => 'ID de l\'affectation',
            'rules' => 'required|callback_existAffectation'
        ),
        array(
            'field' => 'lierChantierId',
            'label' => 'ID du chantier',
            'rules' => 'required|callback_existChantier'
        )
    )
);
?>