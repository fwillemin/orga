<div class="container">
    <div class="row">
        <div class="col-12 fond" style="padding: 20px 10px;">
            <h1>Statistiques et analyses</h1>
            <br>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="cadre">
                        <h3>Chiffre d'affaires</h3>
                        <span class="description">
                            Analyse de votre chiffre d'affaires et des marges sur l'année fiscale
                        </span>
                        <p>
                            <br><a href="<?= site_url('statistiques/caParMois'); ?>">Evolution CA et marges</a>
                            <br><a href="<?= site_url('statistiques/caCumul'); ?>">CA et marges cumulés</a>
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="cadre">
                        <h3>Temps d'intervention</h3>
                        <span class="description">
                            Analyse du temps passé sur les chantiers comparé au temps prévu au moment du devis. Vous retrouverez cette statistique calculée par ouvrier dans <a href="<?= site_url('personnels/liste'); ?>">leur fiche</a>
                        </span>
                        <p>
                            <br><a href="<?= site_url('statistiques/performanceGlobale'); ?>">Performance globale</a>
                            <br><a href="<?= site_url(''); ?>">Comparaison des performances pour les catégories</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:10px;">
                <div class="col-12 col-md-6">
                    <div class="cadre">
                        <h3>Affaires</h3>
                        <span class="description">
                            Analyse des affaires <b>terminées</b> par année fiscale.
                        </span>
                        <p>
                            <br><a href="<?= site_url(''); ?>">Affaires signées</a>
                            <br><a href="<?= site_url(''); ?>">Affaires signées par catégories</a>
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="cadre">
                        <h3>Chantiers</h3>
                        <span class="description">
                            Analyse des chantiers <b>terminés</b> par année fiscale
                        </span>
                        <p>
                            <br><a href="<?= site_url(''); ?>">Répartition des chantiers par catégories</a>
                            <br><a href="<?= site_url(''); ?>">Performances par catégories de chantier</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:10px;">
                <div class="col-12 col-md-6">
                    <div class="cadre">
                        <h3>Personnalisés</h3>
                        <span class="description">
                            Vos rapports et analyses personnalisés
                        </span>
                        <p>
                            <br><a href="<?= site_url(''); ?>">Nous contacter</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>