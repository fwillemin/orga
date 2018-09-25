<div class="row">
    <div class="col-12 col-md-10 offset-md-1 fond">
        <div class="row">
            <div class="col-12">
                <br>
                <h2>
                    <a href="<?= site_url('fournisseurs/listeFst'); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?= $fournisseur->getFournisseurNom(); ?>
                    <button class="btn btn-link" id="btnModFournisseur">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-4">
                <div id="containerModFournisseur" class="inPageForm" style="display: none; padding: 10px; margin-bottom:10px;">
                    <?php include('formFournisseur.php'); ?>
                    <i class="formClose fas fa-times"></i>
                </div>
                <?=
                $fournisseur->getFournisseurAdresse()
                . '<br>' . $fournisseur->getFournisseurCp() . ' ' . $fournisseur->getFournisseurVille()
                . '<br><i class="fas fa-phone"></i> ' . $fournisseur->getFournisseurTelephone()
                . '<br><i class="far fa-envelope"></i> <a href="mailto:' . $fournisseur->getFournisseurEmail() . '">' . $fournisseur->getFournisseurEmail() . '</a>';
                ?>

            </div>
            <div class="col-12 col-sm-8">
                <h5>Achats</h5>
                <table class="table table-sm style1" id="tableFournisseurAchats">
                    <thead>
                        <tr>
                            <td style="width:30px;"></td>
                            <td>Description</td>
                            <td style="width: 50px; text-align: center;">Qté</td>
                            <td style="width: 80px;">Type</td>
                            <td style="width: 120px; text-align: right;">Date liv.</td>
                            <td style="width: 80px; text-align: right;">Etat</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($fournisseur->getFournisseurAchats())):
                            foreach ($fournisseur->getFournisseurAchats as $achat):

                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>