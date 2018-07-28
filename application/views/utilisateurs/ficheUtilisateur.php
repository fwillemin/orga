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
                <br>Dernière connexion le <?= $this->own->dateFrancais($utilisateur->getLast_login()); ?>
                <br>
                <br>
                <div id="containerModUtilisateur" class="inPageForm" style="display: none;">
                    <?php include('formUtilisateur.php'); ?>
                    <i class="formClose fas fa-times"></i>
                </div>
                <br>
            </div>
            <div class="col-12 col-sm-6">
                <h3>Liste des accès</h3>
                <table class="table table-sm style1">
                    <thead>
                        <tr>
                            <td width="20"></td>
                            <td>Accès</td>
                        </tr>
                    </thead>
                    <?php foreach ($listeGroupes as $groupe): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="changeAcces" value="<?= $groupe->id; ?>" <?= in_array($groupe->id, $utilisateur->getUserGroupsIds()) ? 'checked' : ''; ?>
                            </td>
                            <td>
                                <?= $groupe->description; ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>