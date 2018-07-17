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

        <!--<link rel="stylesheet" type="text/css" href="<?= base_url('assets/leaflet/leaflet.css'); ?>" >-->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/bootstrap.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/bootstrap-select.min.css'); ?>" >


        <link rel="stylesheet" href="<?= base_url('assets/MegaNavbarBS4/assets/css/MegaNavbarBS4.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/MegaNavbarBS4/assets/css/skins/navbar-dark.css'); ?>">

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/dataTable/datatables.min.css'); ?>" >

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/jqueryConfirm/jquery-confirm.min.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/styles/css/organibat.css'); ?>" >


        <!-- reload gulp --><script src="//localhost:35729/livereload.js"></script>
    </head>

    <body>

        <?php if ($this->ion_auth->logged_in()): ?>

            <nav class="navbar navbar-dark navbar-expand-xl fixed-onscroll" id="main_navbar" role="navigation">
                <div class="container pl-0">
                    <!-- MegaNavbar BS4 brand -->
                    <a class="navbar-brand" href="#">
                        <img src="<?= base_url('assets/img/logo_white.png'); ?>" height="40">
                    </a>
                    <!-- MegaNavbar BS4 toggler -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar">
                        <div class="nav navbar-nav">
                            <div class="nav-divider"></div>
                            <div class="navbar-text"><i class="fa fa-h-square"></i> Text</div>
                            <div class="nav-divider"></div>
                            <div class="nav-item"><a class="nav-link" href="#"><i class="fa fa-link"></i> Link</a></div>
                            <div class="nav-divider"></div>
                            <div class="nav-item disabled"><a class="nav-link" href="#">Disabled</a></div>
                            <div class="nav-divider"></div>
                            <!--div class="nav-item active"><a class="nav-link" href="#">Active</a></div>
                              <div class="nav-divider"></div-->
                            <div class="nav-item dropdown">
                                <a class="dropdown-toggle collapsed" href="#menu_personels" data-toggle="collapse" aria-haspopup="true" aria-expanded="false">Personnels</a>
                                <div class="dropdown-menu col-xl-2 collapse animated" id="menu_personels" style="max-width: 280px;">
                                    <div class="dropdown-header">Gestion du personnel de chantier</div>
                                    <div class="dropdown-divider m-0"></div>
                                    <div class="dropdown-item">
                                        <a href="<?= site_url('personnels/liste/ajouter'); ?>">
                                            <i class="fas fa-plus-square"></i> Ajouter du personnel
                                        </a>
                                    </div>
                                    <div class="dropdown-item">
                                        <a href="<?= site_url('personnels/liste'); ?>">
                                            <i class="fas fa-list"></i> Liste
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="nav-item dropdown">
                                <a class="dropdown-toggle collapsed" href="#menu_clients" data-toggle="collapse" aria-haspopup="true" aria-expanded="false">Clients</a>
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
                            <div class="nav-divider"></div>

                        </div>
                        <div class="nav navbar-nav navbar-right">

                            <form class="form-inline mr-0" >
                                <input class="form-control form-control-sm mr-sm-2 " type="search" placeholder="Placeholder" aria-label="Search">
                                <button class="btn btn-default btn-sm my-2 my-sm-0" type="submit">Button</button>
                            </form>
                            <div class="nav-separator"></div>

                            <!--SHOPPING CART-->
                            <div class="nav-item dropdown mega-md">
                                <a data-toggle="collapse" href="#id_shop3" class="dropdown-toggle collapsed"><i class="fa fa-shopping-cart"></i><span class="d-expanded-none"> Shoping cart</span></a>
                                <div class="dropdown-menu col-sm-9 col-lg-8"  role="menu" id="id_shop3">
                                    <div class="d-flex">
                                        <div class="col-12 col-md-8">
                                            <div class="row" style="background: #000;">
                                                <div class="col p-2">
                                                    <h2>Bonjour <?= $this->session->userdata('utilisateurPrenom'); ?>,</h2>
                                                    <small class="form-text text-muted">Dernière connexion le <?= $this->own->dateFrancais($this->session->userdata('old_last_login')); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 py-3" style="background: #333;">
                                            <div class="row">
                                                <?php if ($this->ion_auth->in_group(array(10, 11))): ?>
                                                    <div class="col-12" style="min-height: 25px;">
                                                        <a href="<?= site_url('utilisateurs/liste'); ?>" style="color: lightsteelblue;">
                                                            <i class="fas fa-users"></i> Utilisateurs
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($this->ion_auth->in_group(array(40))): ?>
                                                    <div class="col-12" style="min-height: 25px;">
                                                        <a href="<?= site_url('categories/liste'); ?>" style="color: lightsteelblue;">
                                                            <i class="fas fa-object-group"></i> Catégories de chantiers
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($this->ion_auth->in_group(array(20, 21))): ?>
                                                    <div class="col-12" style="min-height: 25px;">
                                                        <a href="<?= site_url('horaires/liste'); ?>" style="color: lightsteelblue;">
                                                            <i class="fas fa-clock"></i> Horaires
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-12">
                                                    <a href="<?= site_url('organibat/deconnexion'); ?>" style="color: orangered;">
                                                        <i class="fas fa-sign-out-alt"></i> Quitter
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </nav>


        <?php endif;
        ?>