<div class="container-fluid">
    <?php include('annee.php'); ?>
    <div class="row">
        <div class="col-12 col-lg-10 offset-lg-1">
            <div class="row fond">
                <div class="col-12 col-md-3">
                    <br><br>
                    <table class="table table-sm table-bordered style1">
                        <thead>
                            <tr>
                                <th rowspan="2">Mois</th>
                                <th colspan="3" class="text-center">CA affaires lancées</th>
                            </tr>
                            <tr>
                                <th class="text-center">N</th>
                                <th class="text-center">N-1</th>
                                <th class="text-center">+/-</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($repartitionsAffaires, true));
                            foreach ($repartitionsAffaires as $key => $value):

                                if ($repartitionsAffairesN[$key] > 0):
                                    $prog = round(($value / $repartitionsAffairesN[$key]) - 1, 2) * 100;
                                    if ($prog > 0):
                                        $prog = '<span style="color: #00b3b3;">+' . $prog . '%</span>';
                                    else:
                                        $prog = '<span style="color: #ff6666;">' . $prog . '%</span>';
                                    endif;
                                else:
                                    $prog = '-';
                                endif;

                                echo '<tr><td>' . $this->cal->dateFrancais(mktime(0, 0, 0, $key, 1, 1970), 'M') . '</td><td class="text-right">' . number_format($value, 0, ',', ' ') . '</td><td class="text-right">' . number_format($repartitionsAffairesN[$key], 0, ',', ' ') . '</td><td class="text-center">' . $prog . '</td></tr>';
                            endforeach;
                            ?>
                        </tbody>
                    </table>

                </div>
                <div class="col-12 col-md-9" style=" padding:20px;">

                    <canvas style="border: 1px solid slategray;" id="graphValeurAffairesParMois" width="400" height="200" chart-labels="<?= $mois; ?>" chart-affaires="<?= $valeursAffaires; ?>" chart-affairesN="<?= $valeursAffairesN; ?>" chart-cumul="<?= $cumulAffaires; ?>" chart-cumulN="<?= $cumulAffairesN; ?>"></canvas>

                </div>
            </div>
        </div>
    </div>

</div>
