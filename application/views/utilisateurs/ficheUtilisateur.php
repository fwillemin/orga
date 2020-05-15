<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond">
        <div class="row">
            <div class="col-12">
                <br>
                <h2>
                    <a href="<?= site_url('utilisateurs/liste'); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?= $utilisateur->getUserPrenom() . ' ' . $utilisateur->getUserNom(); ?>
                    <button class="btn btn-link" id="btnModUtilisateur">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                Login : <strong><?= $utilisateur->getUsername(); ?></strong>
                <br>Dernière connexion le <?= $this->cal->dateFrancais($utilisateur->getLast_login()); ?>
                <br>
                <br>
                <div id="containerModUtilisateur" class="inPageForm" style="display: none;">
                    <?php include('formUtilisateur.php'); ?>
                    <i class="formClose fas fa-times"></i>
                </div>
                <br>
                <h4>Général</h4>
                <table class="table table-sm style1">
                    <thead>
                        <tr>
                            <td width="20"></td>
                            <td>Accès</td>
                        </tr>
                    </thead>
                    <tr class="alert alert-primary">
                        <td colspan="2">
                            Type de compte
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="typeCompte" class="typeCompte" value="1" <?= in_array(1, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?>>
                        </td>
                        <td>
                            Direction
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="typeCompte" class="typeCompte" value="2" <?= in_array(2, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?>>
                        </td>
                        <td>
                            Personnel administratif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="typeCompte" class="typeCompte" value="4" <?= in_array(4, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?>>
                        </td>
                        <td>
                            Compte de chantier (Accès à la saisie des heures)
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="typeCompte" class="typeCompte" value="9" <?= in_array(9, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?>>
                        </td>
                        <td>
                            Compte Inactif
                        </td>
                    </tr>
                    <tr class="alert alert-primary">
                        <td colspan="2">
                            Accès
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="3" <?= in_array(3, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Commercial
                        </td>
                    </tr>

                    <tr class="alert alert-dark">
                        <td colspan="2">
                            <i class="fas fa-user-edit"></i></div> Utilisateurs administratifs
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="10" <?= in_array(10, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Accès au module
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="11" <?= in_array(11, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Ajouter, modifier et supprimer des Utilisateurs administratifs
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="70" <?= in_array(70, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Ajouter, modifier et supprimer des Fournisseurs
                        </td>
                    </tr>
                    <tr class="alert alert-dark">
                        <td colspan="2">
                            <i class="fas fa-clock"></i></div>  Horaires de travail
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="20" <?= in_array(20, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Accès au module
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="21" <?= in_array(21, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Ajouter, modifier et supprimer des Horaires de travail
                        </td>
                    </tr>
                    <tr class="alert alert-dark">
                        <td colspan="2">
                            <i class="fas fa-object-group"></i> Catégories d'affaire et de chantier
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="40" <?= in_array(40, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Ajouter, modifier et supprimer des Catégories de chantier
                        </td>
                    </tr>
                    <tr class="alert alert-dark">
                        <td colspan="2">
                            <i class="fas fa-user-ninja"></i></div> Personnel de chantier
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="25" <?= in_array(25, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Accès au module
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="26" <?= in_array(26, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Ajouter, modifier et supprimer du personnel de chantier
                        </td>
                    </tr>
                    <tr class="alert alert-dark">
                        <td colspan="2">
                            <i class="fab fa-medapps"></i> Contacts entrants
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="90" <?= in_array(90, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Accès au module
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="changeAcces" value="91" <?= in_array(91, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                        </td>
                        <td>
                            Ajouter, modifier et supprimer des Contacts entrants
                        </td>
                    </tr>

                </table>
            </div>
            <div class="col-12 col-sm-6">
                <h4>Détails</h4>
                <table class="table table-sm style1">
                    <thead>
                        <tr>
                            <td width="20"></td>
                            <td>Accès</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="alert alert-dark">
                            <td colspan="2">
                                <i class="fas fa-user-tie"></i> Clients
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="30" <?= in_array(30, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Accès au module
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="31" <?= in_array(31, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Ajouter et modifier et supprimer des clients
                            </td>
                        </tr>
                        <tr class="alert alert-dark">
                            <td colspan="2">
                                <i class="fas fa-file-signature"></i> Affaires
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="50" <?= in_array(50, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Accès au module
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="51" <?= in_array(51, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Ajouter et modifier des affaires
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="52" <?= in_array(52, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Visualiser les analyses et statistiques globales d'une affaire
                            </td>
                        </tr>
                        <tr class="alert alert-warning">
                            <td>
                                <input type="checkbox" class="changeAcces" value="57" <?= in_array(57, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Supprimer des affaires
                            </td>
                        </tr>
                        <tr class="alert alert-dark">
                            <td colspan="2">
                                Chantiers
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="53" <?= in_array(53, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Accès au module
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="54" <?= in_array(54, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Ajouter et modifier des chantiers
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="56" <?= in_array(56, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Visualiser les analyses et statistiques
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="55" <?= in_array(55, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Ajouter, modifier et supprimer des achats
                            </td>
                        </tr>
                        <tr class="alert alert-warning">
                            <td>
                                <input type="checkbox" class="changeAcces" value="58" <?= in_array(58, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Supprimer des chantiers
                            </td>
                        </tr>
                        <tr class="alert alert-dark">
                            <td colspan="2">
                                <i class="fas fa-calendar-alt"></i> Planning
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="60" <?= in_array(60, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Ajouter, modifier et supprimer des affectations
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="61" <?= in_array(61, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Ajouter, modifier et supprimer des livraisons
                            </td>
                        </tr>
                        <tr class="alert alert-dark">
                            <td colspan="2">
                                <i class="fas fa-hourglass-end"></i> Pointages et heures
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="80" <?= in_array(80, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Accès au module
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="81" <?= in_array(81, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Saisir et valider des heures
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="82" <?= in_array(82, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Visualiser les feuilles de pointages mensuelles
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="83" <?= in_array(83, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?> <?= in_array(4, $utilisateur->getUserGroupsIds()) || in_array(9, $utilisateur->getUserGroupsIds()) ? 'disabled' : ''; ?>>
                            </td>
                            <td>
                                Créer et gérer les feuilles de pointages
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>