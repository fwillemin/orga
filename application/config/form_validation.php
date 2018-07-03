<?php

$config = array(
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
    )
);
?>