<div class="container">
    <?php include('annee.php'); ?>
    <div class="row fond">
        <div class="col-12" style="border: 1px solid slategray;">

            <canvas id="graphCaCumul" width="400" height="200" chart-labels="<?= $mois; ?>" chart-caCumul="<?= $caCumul; ?>" chart-margeCumul="<?= $margeCumul; ?>" chart-caCumulN="<?= $caCumulN; ?>" chart-margeCumulN="<?= $margeCumulN; ?>"></canvas>

        </div>
    </div>
</div>
