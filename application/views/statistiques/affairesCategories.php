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
                                <th class="text-center">Nb affaires</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($repartition as $categorie):
                                echo '<tr><td>' . $categorie->categorie . '</td><td class="text-center">' . $categorie->nbAffaires . '</td></tr>';
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-md-8 col-lg-9">
                    <canvas  style="border: 1px solid slategray; margin:5px;" id="graphAffairesCategories" max-width="400" height="200" chart-labels="<?= $labels; ?>" chart-repartition="<?= $valeurs; ?>"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
