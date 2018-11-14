<h3>Analyse</h3>
<?php
if (!$this->ion_auth->in_group(array(52))) :
    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i>' . $this->messageDroitsInsuffisants . '</div>';
else:
    ?>
    <div class="row">
        <div class="col-12 col-lg-7">
            <table class="table table-sm style1 table-bordered" id="tableAnalyseFicheAffaire">
                <thead>
                    <tr>
                        <td width="19%"></td>
                        <td style="text-align: right; width: 27%;">Etat Commercial</td>
                        <td style="text-align: right;; width: 27%;" colspan="2">
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo 'Temps réel';
                            else:
                                echo 'Bilan';
                            endif;
                            ?>
                        </td>
                        <td style="text-align: right;; width: 27%;" colspan="2">
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo 'Estimé fin affaire';
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Chiffrage</td>
                        <td colspan="5" style="text-align: center;">
                            <?= number_format($affaire->getAffairePrix(), 2, ',', ' '); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Main d'oeuvre</td>
                        <td>
                            <?= number_format($analyse['mainO']['commercial'], 2, ',', ' '); ?>
                        </td>
                        <td>
                            <?= number_format($analyse['mainO']['tempsReel'], 2, ',', ' '); ?>
                        </td>
                        <td style="width:10%">
                            <?= $analyse['mainO']['ecartTempsReelHtml']; ?>
                        </td>
                        <td>
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo number_format($analyse['mainO']['finAffaire'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                        <td style="width:10%">
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo $analyse['mainO']['ecartFinAffaireHtml'];
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr style="">
                        <td>Achats</td>
                        <td>
                            <?= number_format($analyse['achats']['commercial'], 2, ',', ' '); ?>
                        </td>
                        <td>
                            <?= number_format($analyse['achats']['tempsReel'], 2, ',', ' '); ?>
                        </td>
                        <td>
                            <?= $analyse['achats']['ecartTempsReelHtml']; ?>
                        </td>
                        <td>
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo number_format($analyse['achats']['finAffaire'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo $analyse['achats']['ecartFinAffaireHtml'];
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr style="font-weight:bold; border-top: 2px solid grey; border-bottom: 2px solid grey;" class="alert alert-secondary">
                        <td>Déboursé sec</td>
                        <td>
                            <?= number_format($analyse['debourseSec']['commercial'], 2, ',', ' '); ?>
                        </td>
                        <td>
                            <?= number_format($analyse['debourseSec']['tempsReel'], 2, ',', ' '); ?>
                        </td>
                        <td>
                            <?= $analyse['debourseSec']['ecartTempsReelHtml']; ?>
                        </td>
                        <td>
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo number_format($analyse['debourseSec']['finAffaire'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo $analyse['debourseSec']['ecartFinAffaireHtml'];
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Frais généraux</td>
                        <td colspan="5" style="text-align: center;">
                            <?= number_format($analyse['fraisGeneraux'], 2, ',', ' '); ?>
                        </td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td>Marge absolue</td>
                        <td>
                            <?= number_format($analyse['marge']['commerciale'], 2, ',', ' '); ?>
                        </td>
                        <td>
                            <?= number_format($analyse['marge']['tempsReel'], 2, ',', ' '); ?>
                        </td>
                        <td>
                            <?= $analyse['marge']['ecartTempsReelHtml']; ?>
                        </td>
                        <td>
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo number_format($analyse['marge']['finAffaire'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo $analyse['marge']['ecartFinAffaireHtml'];
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td>Marge horaire</td>
                        <td>
                            <?= number_format($analyse['margeHoraire']['commerciale'], 2, ',', ' '); ?>
                        </td>
                        <td colspan="2">
                            <?= number_format($analyse['margeHoraire']['tempsReel'], 2, ',', ' '); ?>
                        </td>
                        <td colspan="2">
                            <?php
                            if ($affaire->getAffaireEtat() == 2):
                                echo number_format($analyse['margeHoraire']['finAffaire'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
