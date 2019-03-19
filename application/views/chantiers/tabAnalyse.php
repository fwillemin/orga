<h3>Analyse</h3>
<?php
if (!$this->ion_auth->in_group(array(56))) :
    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i>' . $this->messageDroitsInsuffisants . '</div>';
else:
    ?>
    <div class="row">
        <div class="col-12 col-lg-7">
            <table class="table table-sm style1 table-bordered" id="tableAnalyseFicheChantier">
                <thead>
                    <tr>
                        <td width="19%"></td>
                        <td style="text-align: right; width: 27%;">Etat Commercial</td>
                        <td style="text-align: right;; width: 27%;" colspan="2">
                            <?php
                            if ($chantier->getChantierEtat() == 1):
                                echo 'Temps réel';
                            else:
                                echo 'Bilan';
                            endif;
                            ?>
                        </td>
                        <td style="text-align: right;; width: 27%;" colspan="2">
                            <?php
                            if ($chantier->getChantierEtat() == 1):
                                echo 'Estimé fin chantier';
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
                            <?= number_format($chantier->getChantierPrix(), 2, ',', ' '); ?>
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
                            if ($chantier->getChantierEtat() == 1):
                                echo number_format($analyse['mainO']['finChantier'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                        <td style="width:10%">
                            <?php
                            if ($chantier->getChantierEtat() == 1):
                                echo $analyse['mainO']['ecartFinChantierHtml'];
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr style="">
                        <td>Achats</td>
                        <td>
                            <?= number_format($chantier->getChantierBudgetAchats(), 2, ',', ' '); ?>
                        </td>
                        <td>
                            <?= number_format($chantier->getChantierBudgetConsomme(), 2, ',', ' '); ?>
                        </td>
                        <td>
                            <?= $analyse['achats']['ecartTempsReelHtml']; ?>
                        </td>
                        <td>
                            <?php
                            if ($chantier->getChantierEtat() == 1):
                                echo number_format($analyse['achats']['finChantier'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($chantier->getChantierEtat() == 1):
                                echo $analyse['achats']['ecartFinChantierHtml'];
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
                            if ($chantier->getChantierEtat() == 1):
                                echo number_format($analyse['debourseSec']['finChantier'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($chantier->getChantierEtat() == 1):
                                echo $analyse['debourseSec']['ecartFinChantierHtml'];
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Frais généraux</td>
                        <td colspan="5" style="text-align: center;">
                            <?= number_format($analyse['fraisGeneraux'], 2, ',', ' ') . ' (' . $chantier->getChantierFraisGeneraux() . '%)'; ?>
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
                            if ($chantier->getChantierEtat() == 1):
                                echo number_format($analyse['marge']['finChantier'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($chantier->getChantierEtat() == 1):
                                echo $analyse['marge']['ecartFinChantierHtml'];
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
                            if ($chantier->getChantierEtat() == 1):
                                echo number_format($analyse['margeHoraire']['finChantier'], 2, ',', ' ');
                            else:
                                echo '--';
                            endif;
                            ?>
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>
        <div class="col-12 col-sm-5">
            <?php
            $datas = $analyse['marge']['tempsReel'] . ',' . $chantier->getChantierBudgetConsomme() . ',' . ($analyse['mainO']['tempsReel'] ?: '0') . ',' . ($analyse['fraisGeneraux'] ?: '0');
            //echo $datas;
            ?>
            <canvas style="" id="graphAnalyseChantier" width="300" height="150" chart-labels="Marge,Achats,Main d'oeuvre,Frais généraux" chart-repartition="<?= $datas; ?>"></canvas>
        </div>
    </div>
<?php endif; ?>
