<!DOCTYPE html>
<html lang="fr" xmlns:og="http://ogp.me/ns#">
    <head>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-36076135-6"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-36076135-6');
        </script>

        <meta charset="utf-8">
        <title><?= (!empty($title)) ? $title : false; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?= (!empty($description)) ? $description : false; ?>">
        <meta name="author" content="Xanthellis - Créateur de sites internet et d'applications professionnelles - http://www.xanthellis.com">

        <!-- Le styles -->
        <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/bootstrap.min.css'); ?>" >
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/showroom.css'); ?>" >
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <!-- reload gulp -->

        <meta property="og:site_name" content="Organinat : Logiciel de planification et d'analyse de chantier" >
        <meta property="og:locale" content="fr_FR" >
        <meta property="og:title" content="<?= $title; ?>" >
        <meta property="og:description" content="<?= $description; ?>" >
        <meta property="og:type" content="<?= $type; ?>" >
        <meta property="og:url" content="<?= $url; ?>" >
        <?= $image ? '<meta property="og:image" content="' . $image . '" >' : ''; ?>

        <script type="application/ld+json">
            {
            "@context" : "http://schema.org",
            "@type" : "Organization",
            "name" : "Organibat",
            "url" : "https://www.organibat.com",
            "sameAs" : [
            "https://twitter.com/xanthellis",
            "https://www.facebook.com/organibat"
            ],
            "address": {
            "@type": "PostalAddress",
            "streetAddress": "2638 Rue Georges Ozaneaux",
            "addressRegion": "VILLERS POL",
            "postalCode": "59530",
            "addressCountry": "FRANCE"
            }
            }
        </script>
    </head>

    <body>

        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v3.2';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

        <div class="container-fluid">

            <div class="row" id="header">
                <div class="col-12 col-md-6">
                    <a href="<?= site_url(); ?>"><img src="<?= base_url('assets/img/logoClairTexte.png'); ?>" style="padding:5px; max-height: 65px;" alt="Logo du logiciel Organibat"></a>
                </div>
                <div class="col-md-6 col-12" style="padding: 10px 4px 4px 0px; text-align: right;">
                    <a href="<?= site_url(); ?>" class="btn btn-link btn-lg">
                        <i class="fas fa-home"></i>
                    </a>
                    <a href="<?= site_url('essai-gratuit-logiciel-gestion-chantier'); ?>" class="btn btn-link btn-lg">
                        Essai gratuit
                    </a>
                    <a href="<?= site_url('acces-client'); ?>" class="btn btn-link btn-lg">
                        Accès client
                    </a>
                </div>
            </div>
        </div>