<div class="row" style="margin-top:10px;">
    <div class="col-3">

        <h5>Temps<small> (heures)</small></h5>
        <table class="table-sm table-bordered" style="background-color: #FFF;">
            <tr>
                <td style="width: 100px;">Prévues</td>
                <td style="width: 100px; text-align: right;"><?= $chantier->getChantierHeuresPrevues() . 'h'; ?></td>
                <td style="width: 100px; text-align: right;"></td>
            </tr>
            <tr>
                <td>Planifiées</td>
                <?php
                if ($chantier->getChantierEtat() == 1):
                    $heuresPlanifieesRestantes = $chantier->getChantierHeuresPlanifiees() - $chantier->getChantierHeuresPointees();
                else:
                    $heuresPlanifieesRestantes = 0;
                endif;
                ?>
                <td style="text-align: right;"><?= $heuresPlanifieesRestantes . 'h'; ?></td>
                <td style="text-align: right;">
                    <?php
                    if ($heuresPlanifieesRestantes > $chantier->getChantierHeuresPrevues()):
                        $budgetColor = 'red';
                    else:
                        $budgetColor = 'green';
                    endif;
                    echo '<span style="color: ' . $budgetColor . ';">' . ($heuresPlanifieesRestantes && $chantier->getChantierHeuresPrevues() > 0 ? floor($heuresPlanifieesRestantes / $chantier->getChantierHeuresPrevues() * 100) : '-' ) . '%</span>';
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
                    if (($chantier->getChantierHeuresPointees() + $heuresPlanifieesRestantes) > $chantier->getChantierHeuresPrevues()):
                        $budgetColor = 'red';
                    else:
                        $budgetColor = 'green';
                    endif;
                    echo '<span style="color: ' . $budgetColor . ';">' . ( ($chantier->getChantierHeuresPointees() || $heuresPlanifieesRestantes) && $chantier->getChantierHeuresPrevues() > 0 ? floor(($chantier->getChantierHeuresPointees() + $heuresPlanifieesRestantes) / $chantier->getChantierHeuresPrevues() * 100) : '-' ) . '%</span>';
                    ?>
                </td>
            </tr>
        </table>
        <br>
        <canvas id="graphChantierEtatHeures" width="400" height="500" js-prevues="<?= $chantier->getChantierHeuresPrevues(); ?>" js-planifiees="<?= $heuresPlanifieesRestantes; ?>" js-pointees="<?= $chantier->getChantierHeuresPointees(); ?>"></canvas>
    </div>
    <div class="col-9">
        <h4>Affectations</h4>
        <table class="table table-sm style1">
            <thead>
                <tr>
                    <th>Période</th>
                    <th>Personnel</th>
                    <th>Plan</th>
                    <th>Point</th>
                    <th style="text-align: right;">Taux Horaire</th>
                    <th style="text-align: right;">Coût de l'affect.</th>
                </tr>
            </thead>
            <?php
            $coutMOChantier = 0;
            if (!empty($chantier->getChantierAffectations())):
                foreach ($chantier->getChantierAffectations() as $affectation):

                    /* Nombre d'heures prises en compte dans le calcul du cout de MO : Si le chantier est terminé on prend les heures rééllement pointées, sinon on prend les heures théoriques */
                    if ($chantier->getChantierEtat() == 1):
                        $heuresComptees = $affectation->getAffectationHeuresPlanifiees();
                    else:
                        $heuresComptees = $affectation->getAffectationHeuresPointees();
                    endif;

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
                    $cout = ($tauxADate > 0 ? $tauxADate : $this->session->userdata('etablissementTHM')) * $heuresComptees;
                    $coutMOChantier += $cout;

                    echo '<tr class="' . $classAffect . '">'
                    . '<td>' . $this->cal->dateFrancais($affectation->getAffectationDebutDate(), 'jDma') . ' ' . $affectation->getAffectationDebutMomentTextSmall() . ' <i class="fas fa-arrows-alt-h"></i> ' . $this->cal->dateFrancais($affectation->getAffectationFinDate(), 'jDma') . ' ' . $affectation->getAffectationFinMomentTextSmall() . '</td>'
                    . '<td><strong>' . $affectation->getAffectationPersonnel()->getPersonnelNom() . '</strong> ' . $affectation->getAffectationPersonnel()->getPersonnelPrenom() . '</td>'
                    . '<td align="right" ' . ($chantier->getChantierEtat() == 1 ? 'style="background-color: lightgreen;"' : '') . '>' . $affectation->getAffectationHeuresPlanifiees() . '</td>'
                    . '<td align="right" ' . ($chantier->getChantierEtat() == 2 ? 'style="background-color: lightgreen;"' : '') . '>' . $affectation->getAffectationHeuresPointees() . '</td>'
                    . '<td align="right">' . ($tauxADate > 0 ? $tauxADate : '<a href="' . site_url('personnels/fichePersonnel/' . $affectation->getAffectationPersonnel()->getPersonnelId()) . '"><i class="fas fa-globe"></i></a> ' . $this->session->userdata('etablissementTHM')) . ' €/h</td>'
                    . '<td align="right">' . number_format($cout, 2, ',', ' ') . ' €/h</td>'
                    . '</tr>';
                endforeach;
                echo '<tr class="alert alert-dark"><td colspan="2" align="right">Totaux</td><td align="right">' . $chantier->getChantierHeuresPlanifiees() . '</td><td align="right">' . $chantier->getChantierHeuresPointees() . '</td><td></td><td align="right">' . number_format($coutMOChantier, 2, ',', ' ') . '€</td></tr>';
            endif;
            ?>
        </table>
    </div>
</div>