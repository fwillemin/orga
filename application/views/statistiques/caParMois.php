<div class="container">
    <?php include('annee.php'); ?>
    <div class="row fond">

        <div class="col-12" style="padding: 10px;">
            <canvas style="border: 1px solid slategray;" id="graphCaParMois" width="400" height="200" chart-labels="<?= $mois; ?>" chart-ca="<?= implode(",", $ca); ?>" chart-marge="<?= implode(",", $marge); ?>" chart-caN="<?= implode(",", $caN); ?>" chart-margeN="<?= implode(",", $margeN); ?>"></canvas>
        </div>
        <div class="col-12 col-lg-8 offset-lg-2">
            <table class="table table-sm table-bordered style1">
                <thead>
                    <tr>
                        <th rowspan="2">Mois</th>
                        <th colspan="3" class="text-center">Chiffre d'affaires</th>
                        <th colspan="3" class="text-center">Marges</th>
                    </tr>
                    <tr>
                        <th class="text-center">N</th>
                        <th class="text-center">N-1</th>
                        <th class="text-center">+/-</th>
                        <th class="text-center">N</th>
                        <th class="text-center">N-1</th>
                        <th class="text-center">+/-</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($ca as $key => $value):

                        if ($caN[$key] > 0):
                            $prog = round(($value / $caN[$key]) - 1, 2) * 100;
                            if ($prog > 0):
                                $prog = '<span style="color: #00b3b3;">+' . $prog . '%</span>';
                            else:
                                $prog = '<span style="color: #ff6666;">' . $prog . '%</span>';
                            endif;
                        else:
                            $prog = '-';
                        endif;

                        echo '<tr><td style="border-right:2px solid lightgrey;">' . $this->cal->dateFrancais(mktime(0, 0, 0, $key, 1, 1970), 'M') . '</td><td class="text-right">' . number_format($value, 0, ',', ' ') . '</td><td class="text-right">' . number_format($caN[$key], 0, ',', ' ') . '</td><td class="text-center" style="border-right:2px solid lightgrey;">' . $prog . '</td>';

                        if ($margeN[$key] > 0):
                            $progMarge = round(($marge[$key] / $margeN[$key]) - 1, 2) * 100;
                            if ($progMarge > 0):
                                $progMarge = '<span style="color: #00b3b3;">+' . $progMarge . '%</span>';
                            else:
                                $progMarge = '<span style="color: #ff6666;">' . $progMarge . '%</span>';
                            endif;
                        else:
                            $progMarge = '-';
                        endif;

                        echo '<td class="text-right">' . number_format($marge[$key], 0, ',', ' ') . '</td><td class="text-right">' . number_format($margeN[$key], 0, ',', ' ') . '</td><td class="text-center" style="border-right:2px solid lightgrey;">' . $progMarge . '</td></tr>';
                    endforeach;
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

