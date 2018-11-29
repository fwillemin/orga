<!DOCTYPE html>
<html lang="fr">
    <head>

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
        <!-- reload gulp --><script src="//localhost:35729/livereload.js"></script>
    </head>

    <body>

        <div id="animation">
            <?php include('animation.php'); ?>
        </div>

        <div class="container-fluid">

            <div class="row" id="header">
                <div class="col-12 col-md-6">
                    <img src="<?= base_url('assets/img/logoClairTexte.png'); ?>" style="padding:5px; max-height: 65px;">
                </div>
                <div class="col-md-6 col-12" style="padding:10px;">
                    <a href="https://www.organibat.com/inscription" class="btn btn-light btn-sm">
                        <i class="fas fa-edit"></i> Créer mon compte gratuitement
                    </a>
                    <a href="https://www.organibat.com/secure/" class="btn btn-success btn-sm">
                        <i class="fas fa-sign-in-alt"></i> Accès client
                    </a>
                </div>
            </div>
        </div>