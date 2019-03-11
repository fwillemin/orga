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
                                <th>Categories</th>
                                <th class="text-center">Perf moyenne</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $perfs = $perfsN = $perfsLabels = $backgroundColors = '';

                            foreach ($perfsMoyennes as $perf):

                                if ($perfs != ''):
                                    $perfs .= ',';
                                    $perfsLabels .= ',';
                                    $backgroundColors .= ',';
                                endif;
                                $perfsLabels .= $perf->categorie;
                                $perfs .= $perf->perfMoyenne;
                                if ($perf->perfMoyenne <= 0):
                                    $backgroundColors .= '#00b3b3';
                                else:
                                    $backgroundColors .= '#ff6666';
                                endif;
                                echo '<tr><td>' . $perf->categorie . '</td><td class="text-center">' . ($perf->perfMoyenne > 0 ? '+' . $perf->perfMoyenne . '%' : $perf->perfMoyenne . '%') . '</td></tr>';
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-md-8 col-lg-9">
                    <canvas  style="border: 1px solid slategray; margin:5px;" id="graphPerformancesCategories" max-width = "400" height="200" chart-labels = "<?= $perfsLabels; ?>" chart-performances = "<?= $perfs; ?>" chart-backgroundcolors="<?= $backgroundColors; ?>"></canvas>
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
