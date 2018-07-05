<div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 fond">
        <div class="row">
            <div class="col-12">
                <br>
                <h2>
                    <a href="<?= site_url('personnels/liste'); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?= $personnel->getPersonnelPrenom() . ' ' . $personnel->getPersonnelNom(); ?>
                    <button class="btn btn-link" id="btnModPersonnel">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                Qualification : <strong><?= $personnel->getPersonnelQualif(); ?></strong>
                <br><?= $personnel->getPersonnelActif() ? '<label class="badge badge-info">Actif</label>' : '<label class="badge badge-secondary">Inactif</label>'; ?>
                <br>
                <br>
                <div id="containerModPersonnel" class="inPageForm" style="display: none; padding: 10px;">
                    <?php include('formPersonnel.php'); ?>
                    <i class="formClose fas fa-times"></i>
                </div>
                <br>
            </div>
            <div class="col-12 col-sm-6">

            </div>
        </div>
    </div>
</div>