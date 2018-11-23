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
                    <th style="text-align: right;">Plan</th>
                    <th style="text-align: right;">Point</th>
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
                    . '<td align="right">' . $affectation->getAffectationHeuresPlanifiees() . '</td>'
                    . '<td align="right">' . $affectation->getAffectationHeuresPointees() . '</td>'
                    . '<td align="right">' . ($tauxADate > 0 ? $tauxADate : '<a href="' . site_url('personnels/fichePersonnel/' . $affectation->getAffectationPersonnel()->getPersonnelId()) . '"><i class="fas fa-globe"></i></a> ' . $this->session->userdata('etablissementTHM')) . ' €/h</td>'
                    . '<td align="right">' . number_format($cout, 2, ',', ' ') . ' €/h</td>'
                    . '</tr>';
                    echo '<tr class="' . $classAffect . '" data-affectationid="' . $affectation->getAffectationId() . '">'
                    . '<td colspan="2" style="border-bottom:1px solid black;">' . $affectation->getAffectationCommentaire() . '</td>'
                    . '<td colspan="4" style="border-bottom:1px solid black;">'
                    . '<button class="btn btn-sm btn-link btnReAffecter" style="color: steelblue;">Lier à un autre chantier</button>'
                    . '</td>';
                endforeach;
                echo '<tr class="alert alert-dark"><td colspan="2" align="right">Totaux</td><td align="right">' . $chantier->getChantierHeuresPlanifiees() . '</td><td align="right">' . $chantier->getChantierHeuresPointees() . '</td><td></td><td align="right">' . number_format($coutMOChantier, 2, ',', ' ') . '€</td></tr>';
            endif;
            ?>
        </table>
    </div>
</div>

<div class="modal fade" id="modalLierAffectationChantier" data-show="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lier cette affectation DIVERS à un chantier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <strong>Notice :</strong>
                <span style="font-size:14px;">
                    <br>Ce formulaire permet de lier une affectation du chantier DIVERS sur un chantier CLIENT.
                    <br>Cela vous permet de réaffecter des temps de prise de côtes, de SAV, etc... afin d'optimiser les calculs de rentabilité et la précision des données.
                </span>
                <hr>
                <?= form_open('planning/relierAffectation', array('id' => 'formLierAffectation')); ?>
                <input type="hidden" name="lierAffectationId" id="lierAffectationId" value="">
                <div class="form-row" style="margin-top: 4px;">
                    <div class="col">
                        <label for="lierChantierId">Sélectionnez un chantier</label><br>
                        <select name="lierChantierId" id="lierChantierId" class="selectpicker" data-width="100%" data-live-search="true" required title="Selectionnez un chantier d'intervention">
                            <?php
                            if (!empty($affairesALier)):
                                foreach ($affairesALier as $affaire1):
                                    if ($affaire1 && !empty($affaire1->getAffaireChantiers())):
                                        foreach ($affaire1->getAffaireChantiers() as $chantier1):
                                            echo '<option value="' . $chantier1->getChantierId() . '"'
                                            . 'data-content="<span class=\'selectpickerClient\'>' . $affaire1->getAffaireClient()->getClientNom() . '</span> <span class=\'selectpickerAnnotation\'>' . $affaire1->getAffaireObjet() . ' > ' . $chantier1->getChantierObjet() . '</span>">' . $affaire1->getAffaireClient()->getClientNom() . ' ' . $chantier1->getChantierObjet() . '</option>';
                                        endforeach;
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row" style="margin-top: 6px;">
                    <div class="col-4">
                        <input type="checkbox" id="suivreApresLier"> Aller sur la fiche chantier liée
                    </div>
                    <div class="col-4">
                        <button class="btn btn-primary btn-sm" type="submit">
                            Ré-affecter
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>