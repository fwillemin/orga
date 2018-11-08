<div class="row" style="margin-top:10px;">
    <div class="col-5">

        <h5>Temps<small> (heures)</small></h5>
        <table class="table-sm table-bordered" style="background-color: #FFF;">
            <tr>
                <td style="width: 100px;">Prévues</td>
                <td style="width: 100px; text-align: right;"><?= $chantier->getChantierHeuresPrevues() . 'h'; ?></td>
                <td style="width: 100px; text-align: right;"></td>
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
            <tr>
                <td colspan="2" style="text-align: right; font-weight:bold;">Cumulé</td>
                <td style="text-align: right;">
                    <?php
                    if (($chantier->getChantierHeuresPointees() + $chantier->getChantierHeuresPlanifiees()) > $chantier->getChantierHeuresPrevues()):
                        $budgetColor = 'red';
                    else:
                        $budgetColor = 'green';
                    endif;
                    echo '<span style="color: ' . $budgetColor . ';">' . ( ($chantier->getChantierHeuresPointees() || $chantier->getChantierHeuresPlanifiees()) && $chantier->getChantierHeuresPrevues() > 0 ? floor(($chantier->getChantierHeuresPointees() + $chantier->getChantierHeuresPlanifiees()) / $chantier->getChantierHeuresPrevues() * 100) : '-' ) . '%</span>';
                    ?>
                </td>
            </tr>
        </table>
        <br>
        <canvas id="graphChantierEtatHeures" width="400" height="300" js-prevues="<?= $chantier->getChantierHeuresPrevues(); ?>" js-planifiees="<?= $chantier->getChantierHeuresPlanifiees(); ?>" js-pointees="<?= $chantier->getChantierHeuresPointees(); ?>"></canvas>
    </div>
    <div class="col-7">
        <h4>Affectations</h4>
        <table class="table table-sm style1">
            <thead>
                <tr>
                    <th>Période</th>
                    <th>Personnel</th>
                    <th style="text-align: right;">Taux Horaire</th>
                    <th style="text-align: right;">Coût de l'affect.</th>
                </tr>
            </thead>
            <?php
            $coutMOChantier = 0;
            if (!empty($chantier->getChantierAffectations())):
                foreach ($chantier->getChantierAffectations() as $affectation):
                    if ($affectation->getAffectationDebutDate() <= time() && $affectation->getAffectationFinDate() >= time()):
                        /* Affectation en cours */
                        $classAffect = 'alert alert-success';
                    elseif ($affectation->getAffectationFinDate() < time()):
                        /* Affectation terminée */
                        $classAffect = 'alert alert-light';
                    else:
                        $classAffect = 'alert alert-default';
                    endif;
                    $tauxADate = $affectation->getAffectationPersonnel()->getTauxHoraireADate($affectation->getAffectationDebutDate());
                    $cout = $tauxADate * $affectation->getAffectationNbDemi() * 4;
                    $coutMOChantier += $cout;

                    echo '<tr class="' . $classAffect . '">'
                    . '<td>' . $this->cal->dateFrancais($affectation->getAffectationDebutDate(), 'jDma') . ' ' . $affectation->getAffectationDebutMomentText() . ' <i class="fas fa-arrows-alt-h"></i> ' . $this->cal->dateFrancais($affectation->getAffectationFinDate(), 'jDma') . ' ' . $affectation->getAffectationFinMomentText() . '</td>'
                    . '<td><strong>' . $affectation->getAffectationPersonnel()->getPersonnelNom() . '</strong> ' . $affectation->getAffectationPersonnel()->getPersonnelPrenom() . '</td>'
                    . '<td align="right">' . $tauxADate . ' €/h</td>'
                    . '<td align="right">' . number_format($cout, 2, ',', ' ') . ' €/h</td>'
                    . '</tr>';
                endforeach;
                echo '<tr class="alert alert-dark"><td colspan="3" align="right">Coût total de main d\'oeuvre</td><td align="right">' . number_format($coutMOChantier, 2, ',', ' ') . '€</td></tr>';
            endif;
            ?>
        </table>
    </div>
</div>