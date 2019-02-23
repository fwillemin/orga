<div class="container-fluid">
    <?php include('annee.php'); ?>
    <div class="row">
        <div class="col-12 col-xl-10 offset-xl-1">
            <div class="row fond">
                <div class="col-12 col-md-4 col-lg-3">
                    <br>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2">Taux %</th>
                                <th colspan="2" class="text-center">Nb de chantiers</th>
                            </tr>
                            <tr>
                                <th class="text-center">N</th>
                                <th class="text-center">N-1</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $perfs = $perfsN = $perfsLabels = '';
                            $nbGain = $nbPerte = $nbGainN = $nbPerteN = 0;

                            foreach ($performances as $key => $value):

                                if (substr($key, 0, 1) == '-'):
                                    $nbGain += $value;
                                else:
                                    $nbPerte += $value;
                                endif;

                                if ($perfs != ''):
                                    $perfs .= ',';
                                    $perfsLabels .= ',';
                                endif;
                                $perfsLabels .= $key;
                                $perfs .= $value;
                                echo '<tr><td>' . $key . '</td><td class="text-center">' . $value . '</td><td class="text-center">' . $performancesN[$key] . '</td></tr>';
                            endforeach;

                            foreach ($performancesN as $key => $value):

                                if (substr($key, 0, 1) == '-'):
                                    $nbGainN += $value;
                                else:
                                    $nbPerteN += $value;
                                endif;

                                if ($perfsN != ''):
                                    $perfsN .= ',';
                                endif;
                                $perfsN .= $value;
                            endforeach;
                            ?>
                            <tr><td colspan="3" style="height:15px; background-color: lightgrey;"></td></tr>
                            <tr style="background-color: #e6ffff; color: #004d4d;"><td>Nombre chantiers Gain</td><td class="text-center"><?= $nbGain; ?></td><td class="text-center"><?= $nbGainN; ?></td></tr>
                            <tr style="background-color: #ffd6cc; color: #991f00;"><td>Nombre chantiers Perte</td><td class="text-center"><?= $nbPerte; ?></td><td class="text-center"><?= $nbPerteN; ?></td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-md-8 col-lg-9">
                    <canvas  style="border: 1px solid slategray; margin:5px;" id="graphPerformances" max-width = "400" height="150" chart-labels = "<?= $perfsLabels; ?>" chart-performances = "<?= $perfs; ?>" chart-performancesN = "<?= $perfsN; ?>"></canvas>
                </div>
            </div>
            <div class="row fond">
                <div class="col-12" style="padding-bottom:30px;">
                    <br>
                    <h4>Détails des performances</h4>
                    <table class="table table-sm table-bordered style1" id="tableDetailsPerfs">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Affaire</th>
                                <th>Chantier</th>
                                <th>Categorie</th>
                                <th class="text-right">Delta heures</th>
                                <th class=""text-right>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="6" class="text-center">Sélectionnez une barre dans le graphique ci-dessus</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
