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
                            <td style="width: 110px; text-align: center;">Qté</td>
                            <td style="width: 120px;">Type</td>
                            <td style="width: 180px;">Date livraison</td>
                            <td style="width: 90px; text-align: right;">Avancement</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($fournisseur->getFournisseurAchats())):
                            foreach ($fournisseur->getFournisseurAchats() as $achat):
                                echo '<tr class="ligneClikable" data-chantierid="' . $achat->getAchatChantierId() . '" data-achatId="' . $achat->getAchatId() . '">'
                                . '<td></td>'
                                . '<td>' . $achat->getAchatDescription() . '</td>'
                                . '<td style="text-align:center;">' . ($achat->getAchatQte() > 0 ?: $achat->getAchatQtePrevisionnel() . ' (prévision)') . '</td>'
                                . '<td>' . $achat->getAchatTypeText() . '</td>'
                                . '<td>' . ($achat->getAchatLivraisonDate() ? $this->cal->dateFrancais($achat->getAchatLivraisonDate()) : '-') . '</td>'
                                . '<td>' . ($achat->getAchatLivraisonAvancement() ? $achat->getAchatLivraisonAvancementText() : '-') . '</td>'
                                . '</tr>';
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>