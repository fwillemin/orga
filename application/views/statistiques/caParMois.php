<div class="container">
    <?php include('annee.php'); ?>
    <div class="row fond">
        <div class="col-12" style="border: 1px solid slategray;">

            <canvas id="graphCaParMois" width="400" height="200" chart-labels="<?= $mois; ?>" chart-ca="<?= $ca; ?>" chart-marge="<?= $marge; ?>" chart-caN="<?= $caN; ?>" chart-margeN="<?= $margeN; ?>"></canvas>

        </div>
    </div>
</div>
