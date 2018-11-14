
<?php if ($this->ion_auth->in_group(54)): ?>
    <button class="btn btn-outline-primary btn-sm" id="btnAddChantier" style="position: absolute; right: 5px;">
        <i class="fas fa-plus-square"></i> Ajouter un chantier
    </button>
<?php endif; ?>
<h3>Chantiers</h3>
<div class="row">
    <?php
    if (!empty($affaire->getAffaireChantiers())):
        foreach ($affaire->getAffaireChantiers() as $chantier):
            ?>
            <div class="col-12 col-md-4" style="padding: 5px 10px;">
                <div class="cadre js-linkChantier" data-chantierid="<?= $chantier->getChantierId(); ?>">
                    <span style="position: absolute; top:6px; right:2px; z-index: 3;">
                        <?= $chantier->getChantierEtatHtml(); ?>
                    </span>
                    <div class="affectationEnPage" style="margin: 0px 0px 5px 0px; color: <?= $chantier->getChantierCouleurSecondaire(); ?>; border-color: <?= $chantier->getChantierCouleurSecondaire(); ?>; background-color: <?= $chantier->getChantierCouleur(); ?>;">
                        <strong>Objet : <?= $chantier->getChantierObjet(); ?></strong>
                        <br><small style="position: relative; top:-6px;">Catégorie : <?= $chantier->getChantierCategorie(); ?></small>
                    </div>
                    <div class="row" style="border-top:1px dashed grey; font-size:12px;">
                        <div class="col">
                            <h5>Temps<small> (heures)</small></h5>
                            <table class="table-sm table-bordered condensed" style="background-color: #FFF;">
                                <tr>
                                    <td style="width: 80px;">Prévues</td>
                                    <td style="width: 60px; text-align: right;"><?= $chantier->getChantierHeuresPrevues() . 'h'; ?></td>
                                    <td style="width: 50px; text-align: right;"></td>
                                </tr>
                                <tr>
                                    <td>Planifiées</td>
                                    <td style="text-align: right;"><?= $chantier->getChantierHeuresPlanifiees() . 'h'; ?></td>
                                    <td style="text-align: right;">
                                        <?php
                                        if ($chantier->getChantierHeuresPlanifiees() > $chantier->getChantierHeuresPrevues()):
                                            $budgetColor = 'red';
                                        else:
                                            $budgetColor = 'green';
                                        endif;
                                        echo '<span style="color: ' . $budgetColor . ';">' . ($chantier->getChantierHeuresPlanifiees() && $chantier->getChantierHeuresPrevues() > 0 ? floor($chantier->getChantierHeuresPlanifiees() / $chantier->getChantierHeuresPrevues() * 100) : '-' ) . '%</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pointées</td>
                                    <td style="text-align: right;"><?= ($chantier->getChantierHeuresPointees() ?: '0') . 'h'; ?></td>
                                    <td style="text-align: right;">
                                        <?php
                                        if ($chantier->getChantierHeuresPointees() > $chantier->getChantierHeuresPrevues()):
                                            $budgetColor = 'red';
                                        else:
                                            $budgetColor = 'green';
                                        endif;
                                        echo '<span style="color: ' . $budgetColor . ';">' . ($chantier->getChantierHeuresPointees() && $chantier->getChantierHeuresPrevues() > 0 ? floor($chantier->getChantierHeuresPointees() / $chantier->getChantierHeuresPrevues() * 100) : '-' ) . '%</span>';
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col">
                            <h5>Achats</h5>
                            <table class="table-sm table-bordered condensed" style="background-color: #FFF;">
                                <tr>
                                    <td style="width: 80px;">Budget</td>
                                    <td style="width: 60px; text-align: right;"><?= $chantier->getChantierBudgetAchats() . '€'; ?></td>
                                    <td style="width: 50px; text-align: right;"></td>
                                </tr>
                                <tr>
                                    <td>Prévisionnel</td>
                                    <td style="text-align: right;"><?= $chantier->getChantierBudgetPrevu() . '€'; ?></td>
                                    <td style="text-align: right;">
                                        <?php
                                        if ($chantier->getChantierBudgetPrevu() > $chantier->getChantierBudgetAchats()):
                                            $budgetColor = 'red';
                                        else:
                                            $budgetColor = 'green';
                                        endif;
                                        echo '<span style="color: ' . $budgetColor . ';">' . ($chantier->getChantierBudgetPrevu() && $chantier->getChantierBudgetAchats() > 0 ? floor($chantier->getChantierBudgetPrevu() / $chantier->getChantierBudgetAchats() * 100) : '-' ) . '%</span>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dépensé</td>
                                    <td style="text-align: right;"><?= ($chantier->getChantierBudgetConsomme() ?: '0') . '€'; ?></td>
                                    <td style="text-align: right;">
                                        <?php
                                        if ($chantier->getChantierBudgetConsomme() > $chantier->getChantierBudgetAchats()):
                                            $budgetColor = 'red';
                                        else:
                                            $budgetColor = 'green';
                                        endif;
                                        echo '<span style="color: ' . $budgetColor . ';">' . ($chantier->getChantierBudgetConsomme() && $chantier->getChantierBudgetAchats() > 0 ? floor($chantier->getChantierBudgetConsomme() / $chantier->getChantierBudgetAchats() * 100) : '-' ) . '%</span>';
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endforeach;
        unset($chantier);
    endif;
    ?>
</div>