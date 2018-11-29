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

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/bootstrap-colorpicker.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/bootstrap.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/bootstrap-select.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/bootstrap-datepicker3.min.css'); ?>" >
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

        <link rel="stylesheet" href="<?= base_url('assets/MegaNavbarBS4/assets/css/MegaNavbarBS4.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/MegaNavbarBS4/assets/css/skins/navbar-dark.css'); ?>">

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/dataTable/datatables.min.css'); ?>" >

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/jqueryConfirm/jquery-confirm.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/jquery-ui/jquery-ui.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/organibat.css'); ?>" >

        <!-- reload gulp --><script src="//localhost:35729/livereload.js"></script>
    </head>

    <body>

        <?php if ($this->ion_auth->logged_in()): ?>

            <nav class="navbar navbar-dark navbar-expand-xl fixed-onscroll" id="main_navbar" role="navigation">
                <div class="container pl-0">
                    <!-- MegaNavbar BS4 brand -->
                    <a class="navbar-brand" href="#">
                        <img src="<?= base_url('assets/img/logoClair.png'); ?>" height="40" style="padding-right: 30px; padding-bottom: 5px;">
                    </a>
                    <!-- MegaNavbar BS4 toggler -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar">
                        <div class="nav navbar-nav">
                            <div class="nav-divider"></div>
                            <div class="nav-item">
                                <a class="nav-link" href="<?= site_url('planning/base'); ?>"  style="color: lightsteelblue; font-weight: bold; font-size:16px;">
                                    <i class="fas fa-calendar-alt"></i> Planning
                                </a>
                            </div>
                            <div class="nav-divider"></div>
                            <?php if ($this->ion_auth->in_group(array(90, 91))): ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="<?= site_url('contacts/liste'); ?>">
                                        <i class="fab fa-medapps"></i> Contacts
                                    </a>
                                </div>
                                <div class="nav-divider"></div>
                            <?php endif; ?>
                            <?php if ($this->ion_auth->in_group(array(30, 31))): ?>
                                <div class="nav-item dropdown">
                                    <a class="dropdown-toggle collapsed" href="#menu_clients" data-toggle="collapse" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-tie"></i> Clients</a>
                                    <div class="dropdown-menu col-xl-2 collapse animated" id="menu_clients" style="max-width: 280px;">
                                        <div class="dropdown-header">Gestion des clients</div>
                                        <div class="dropdown-divider m-0"></div>
                                        <div class="dropdown-item">
                                            <a href="<?= site_url('clients/liste/ajouter'); ?>">
                                                <i class="fas fa-plus-square"></i> Ajouter un client
                                            </a>
                                        </div>
                                        <div class="dropdown-item">
                                            <a href="<?= site_url('clients/liste'); ?>">
                                                <i class="fas fa-list"></i> Liste
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->ion_auth->in_group(array(50))): ?>
                                <div class="nav-item dropdown">
                                    <a class="dropdown-toggle collapsed" href="#menu_personnels" data-toggle="collapse" aria-haspopup="true" aria-expanded="false"><i class="fas fa-file-signature"></i> Affaires</a>
                                    <div class="dropdown-menu col-xl-2 collapse animated" id="menu_personnels" style="max-width: 280px;">
                                        <div class="dropdown-header">Gestion de vos affaires</div>
                                        <div class="dropdown-divider m-0"></div>
                                        <div class="dropdown-item">
                                            <a href="<?= site_url('affaires/liste/ajouter'); ?>">
                                                <i class="fas fa-plus-square"></i> Ajouter une affaire
                                            </a>
                                        </div>
                                        <div class="dropdown-item">
                                            <a href="<?= site_url('affaires/liste'); ?>">
                                                <i class="fas fa-list"></i> Liste
                                            </a>
                                        </div>
                                        <div class="dropdown-divider m-0"></div>
                                        <div class="dropdown-item">
                                            <a href="<?= site_url('affaires/ficheAffaire/' . $this->session->userdata('affaireDiversId')); ?>">
                                                <i class="fas fa-cloud"></i> Gestion des divers
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="nav-divider"></div>
                            <?php if ($this->ion_auth->in_group(array(80, 81, 82, 83))): ?>
                                <div class="nav-item dropdown">
                                    <a class="dropdown-toggle collapsed" href="#menu_pointages" data-toggle="collapse" aria-haspopup="true" aria-expanded="false"><i class="fas fa-hourglass-end"></i> Pointages</a>
                                    <div class="dropdown-menu col-xl-2 collapse animated" id="menu_pointages" style="max-width: 280px;">
                                        <div class="dropdown-header">Gestion des pointages</div>
                                        <div class="dropdown-divider m-0"></div>
                                        <div class="dropdown-item">
                                            <a href="<?= site_url('pointages/heures'); ?>">
                                                <i class="fas fa-clock"></i> Heures
                                            </a>
                                        </div>
                                        <div class="dropdown-item">
                                            <a href="<?= site_url('pointages/feuilles'); ?>">
                                                <i class="fas fa-file-invoice"></i> Feuilles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="nav navbar-nav navbar-right">
                            <?php if ($this->ion_auth->in_group(array(61))): ?>
                                <div class="nav-item">
                                    <a class="nav-link" href="<?= site_url('fournisseurs/listeFst'); ?>">
                                        <i class="fas fa-parachute-box"></i> Fournisseurs
                                    </a>
                                </div>
                                <div class="nav-divider"></div>
                            <?php endif; ?>
                            <div class="nav-divider"></div>
                            <?php if ($this->ion_auth->in_group(array(4))): ?>
                                <div class="nav-item">
                                    <a href="<?= site_url('organibat/deconnexion'); ?>" style="color: orangered;">
                                        <div style="width:25px; float: left;"><i class="fas fa-sign-out-alt"></i></div> Quitter
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="nav-item dropdown mega-md">
                                    <a data-toggle="collapse" href="#parametrages" class="dropdown-toggle collapsed"><i class="fa fa-cogs" style="font-size:20px; color: lightslategray;"></i><span class="d-expanded-none"> Paramètres</span></a>
                                    <div class="dropdown-menu col-sm-9 col-lg-8"  role="menu" id="parametrages">
                                        <div class="d-flex">
                                            <div class="col-12 col-md-8">
                                                <div class="row" style="background: #000;">
                                                    <div class="col p-2">
                                                        <h2>Bonjour <?= $this->session->userdata('utilisateurPrenom'); ?>,</h2>
                                                        <small class="form-text text-muted">Dernière connexion le <?= $this->cal->dateFrancais($this->session->userdata('old_last_login')); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4 py-3" style="background: #333;">
                                                <div class="row">
                                                    <?php if ($this->ion_auth->in_group(array(25, 26))): ?>
                                                        <div class="col-12" style="min-height: 25px;">
                                                            <a href="<?= site_url('personnels/liste'); ?>" style="color: lightsteelblue;">
                                                                <div style="width:25px; float: left;"><i class="fas fa-user-ninja"></i></div> Personnels de chantier
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($this->ion_auth->in_group(array(10, 11))): ?>
                                                        <div class="col-12" style="min-height: 25px;">
                                                            <a href="<?= site_url('utilisateurs/liste'); ?>" style="color: lightsteelblue;">
                                                                <div style="width:25px; float: left;"><i class="fas fa-user-edit"></i></div> Utilisateurs administratifs
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($this->ion_auth->in_group(array(40))): ?>
                                                        <div class="col-12" style="min-height: 25px;">
                                                            <a href="<?= site_url('categories/liste'); ?>" style="color: lightsteelblue;">
                                                                <div style="width:25px; float: left;"><i class="fas fa-object-group"></i></div> Catégories Affaires/Chantiers
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($this->ion_auth->in_group(array(20, 21))): ?>
                                                        <div class="col-12" style="min-height: 25px;">
                                                            <a href="<?= site_url('horaires/liste'); ?>" style="color: lightsteelblue;">
                                                                <div style="width:25px; float: left;"><i class="fas fa-clock"></i></div> Horaires
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($this->ion_auth->in_group(array(1))): ?>
                                                        <div class="col-12" style="min-height: 25px;">
                                                            <a href="<?= site_url('organibat/parametres'); ?>" style="color: lightsteelblue;">
                                                                <div style="width:25px; float: left;"><i class="fas fa-cog"></i></div> Paramètres
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="col-12">
                                                        <a href="<?= site_url('organibat/deconnexion'); ?>" style="color: orangered;">
                                                            <div style="width:25px; float: left;"><i class="fas fa-sign-out-alt"></i></div> Quitter
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </nav>


        <?php endif;
        ?>