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
                            <div class="nav-item dropdown active">
                                <a class="dropdown-toggle collapsed" href="#id_content" data-toggle="collapse" aria-haspopup="true" aria-expanded="false">Active item</a>
                                <div class="dropdown-menu col-xl-2 collapse animated" id="id_content" style="max-width: 230px;">
                                    <div class="dropdown-header">Submenu header <span class="description">and some description</span> </div>
                                    <div class="dropdown-divider m-0"></div>
                                    <div class="dropdown-text "><small>This is .dropdown-text content,  for adding strings of text to the submenu.</small></div>
                                    <div class="dropdown-divider m-0"></div>
                                    <a class="dropdown-link" href="#" title="dropdown-link">Regular link<span class="description">Regular link description</span></a>
                                    <div class="dropdown-item"><a href="#">Default item <span class="description">Default item description</span></a></div>
                                    <div class="dropdown-item disabled"><a href="#">Disabled item<span class="description">Disabled item description</span></a></div>
                                    <div class="dropdown-item active"><a href="#">Active item<span class="description">Active item description</span></a></div>
                                    <div class="dropdown-separator"></div>
                                    <div class="dropdown-divider m-0"></div>
                                    <div class="dropdown-text"><i class="fa fa-arrow-up" aria-hidden="true"></i> <small>Separator above this item</small></div>
                                </div>
                            </div>
                            <div class="nav-divider"></div>

                            <!-- Forms -->
                            <div class="nav-item dropdown">
                                <!-- .dropdown .dropup .dropleft .dropright .droptop .dropbottom -->
                                <a class="dropdown-toggle collapsed" href="#item_login" data-toggle="collapse" aria-haspopup="true" aria-expanded="false" aria-controls="item_login">Forms</a>
                                <div class="dropdown-menu col col-sm-6 col-md-5 col-lg-4 col-xl-3" id="item_login"  role="menu">
                                    <div class="p-3">
                                        <nav>
                                            <div class="nav nav-pills" id="nav-tab" role="tablist">
                                                <a class="nav-item  nav-link bg-transparent pl-0" style="color: white;">Demos: </a>
                                                <a class="nav-item nav-link active text-light" id="nav-type1-tab" data-toggle="tab" href="#nav-type1" role="tab" aria-controls="nav-type1" aria-selected="true">1</a>
                                                <a class="nav-item nav-link text-light" id="nav-type2-tab" data-toggle="tab" href="#nav-type2" role="tab" aria-controls="nav-type2" aria-selected="false">2</a>
                                                <a class="nav-item nav-link text-light" id="nav-type3-tab" data-toggle="tab" href="#nav-type3" role="tab" aria-controls="nav-type3" aria-selected="false">3</a>
                                                <a class="nav-item nav-link text-light" id="nav-type4-tab" data-toggle="tab" href="#nav-type4" role="tab" aria-controls="nav-type4" aria-selected="false">4</a>
                                            </div>
                                        </nav>
                                        <div class="dropdown-divider mb-3"></div>
                                        <div class="tab-content" id="nav-tabContent">
                                            <!--type 1-->
                                            <div class="tab-pane fade show active" id="nav-type1" role="tabpanel" aria-labelledby="nav-type1-tab">
                                                <form class="mb-0">
                                                    <div class="form-group">
                                                        <label for="exampleDropdownFormEmail1">Email address</label>
                                                        <input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleDropdownFormPassword1">Password</label>
                                                        <input type="password" autocomplete="off" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                                                    </div>
                                                    <div class="dropdown-divider m-0"></div>
                                                    <div class="form-group ml-1 mt-2 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                            <label class="custom-control-label" for="customCheck1" style="font-size: small; line-height: 24px;">Remember me</label>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary btn-block btn-sm" style="padding: 15px;" type="submit">Sign in</button>
                                                </form>
                                            </div>
                                            <!--type 2-->
                                            <div class="tab-pane fade" id="nav-type2" role="tabpanel" aria-labelledby="nav-type2-tab">
                                                <form class="p-0 mb-0">
                                                    <div class="form-group">
                                                        <label for="exampleDropdownFormEmail2">Email address</label>
                                                        <input type="email" class="form-control" id="exampleDropdownFormEmail2" placeholder="email@example.com">
                                                        <small class="form-text text-muted">We'll never share your email.</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleDropdownFormPassword2">Password</label>
                                                        <input type="password"  autocomplete="off" class="form-control" id="exampleDropdownFormPassword2" placeholder="Password">
                                                        <small id="passwordHelp" class="form-text text-muted"><a class="text-muted" href="#">Forgot password?</a></small>
                                                    </div>
                                                    <div class="dropdown-divider mb-3"></div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group ml-1 mt-0 mb-0 float-left">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                                    <label class="custom-control-label" for="customCheck2" style="font-size: small; line-height: 24px;">keep me logged-in</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mb-0 float-right">
                                                                <button type="submit" class="btn btn-primary btn-sm">Sign up</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!--type 3-->
                                            <div class="tab-pane fade" id="nav-type3" role="tabpanel" aria-labelledby="nav-type3-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        Login via:
                                                        <div class="row mt-2 mb-3 ">
                                                            <div class="col">
                                                                <a href="#" class="btn btn-block" style="color: #fff; background-color: #3b5998;"><i class="fa fa-facebook"></i> Facebook</a>
                                                            </div>
                                                            <div class="col">
                                                                <a href="#" class="btn  btn-block" style="color: #fff; background-color: #55acee;"><i class="fa fa-twitter"></i> Twitter</a>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown-divider my-4" style="height: 3px;"></div>
                                                        <span style="display: block; position: absolute; margin-top: -36px; padding: 0 15px; background: #454b51; border: 1px solid #575e66; left: 50%; margin-left: -22px;">or</span>
                                                        <form class="form" role="form">
                                                            <div class="form-group">
                                                                <label class="sr-only">Email address</label>
                                                                <input type="email" class="form-control" placeholder="Email address" required="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="sr-only" for="exampleInputPassword2">Password</label>
                                                                <input type="password" autocomplete="off" class="form-control" id="exampleInputPassword2" placeholder="Password" required="">
                                                                <small id="passwordHelp" class="form-text text-right"><a class="text-muted" href="#">Forgot password?</a></small>
                                                            </div>
                                                            <div class="form-group ml-1 mt-0 mb-3 float-left">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="customCheck3">
                                                                    <label class="custom-control-label" for="customCheck3" style="font-size: small; line-height: 24px;">keep me logged-in</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-12 bg-light" style="margin-bottom: -16px;">
                                                        <div class="bottom text-center p-3 text-dark">New here ? <a href="#"><b>Join Us</b></a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--type 4-->
                                            <div class="tab-pane fade" id="nav-type4" role="tabpanel" aria-labelledby="nav-type4-tab">
                                                <form class="form" role="form">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1" style="color: #b9b9b9; background: transparent; border-color: #68717a;"><i class="fa fa-user"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="Username or email" aria-label="Username" aria-describedby="basic-addon1">
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon2" style="color: #b9b9b9; background: transparent; border-color: #68717a;"><i class="fa fa-lock"></i></span>
                                                        </div>
                                                        <input type="password" autocomplete="off" class="form-control" placeholder="Please enter password" aria-label="Username" aria-describedby="basic-addon2">
                                                    </div>
                                                    <div class="dropdown-divider mb-3"></div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group ml-1 mt-2 mb-0 float-left">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="customCheck4">
                                                                    <label class="custom-control-label" for="customCheck4" style="font-size: small; line-height: 24px;">keep me logged-in</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group text-right">
                                                                <button type="submit" class="btn btn-primary">Sign up</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- forms end -->

                            <div class="nav-divider"></div>
                            <div class="nav-item dropdown">
                                <a class="dropdown-toggle collapsed" href="#id_dropdown_left" data-toggle="collapse" aria-haspopup="true" aria-expanded="false">Submenus</a>
                                <div class="dropdown-menu collapse" id="id_dropdown_left">
                                    <h5 class="dropdown-header">Top level container</h5>
                                    <div class="dropdown-divider m-0"></div>
                                    <div class="dropdown dropright">
                                        <a data-toggle="collapse" href="#id_ex3" class="dropdown-toggle collapsed" aria-expanded="false">Submenu 1 <span class="description">Click to open next level</span></a>
                                        <div class="dropdown-menu collapse" id="id_ex3">
                                            <h5 class="dropdown-header">Submenu level 1</h5>
                                            <div class="dropdown-divider m-0"></div>
                                            <div class="dropdown dropright">
                                                <a data-toggle="collapse" href="#id_ex4" class="dropdown-toggle collapsed" aria-expanded="false">Submenu 2 <span class="description">Click to open next level</span></a>
                                                <div class="dropdown-menu collapse" id="id_ex4">
                                                    <h5 class="dropdown-header">Submenu level 3</h5>
                                                    <div class="dropdown-divider mt-0"></div>
                                                    <div class="dropdown-text">You can use unlimited level submenu tree.</div>
                                                </div>
                                            </div>
                                        </div>
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
                                            <div class="row" style="background: #000; margin-top:5px;">
                                                <div class="col p-2">
                                                    Bonjour <?= $this->session->userdata('utilisateurPrenom'); ?>,
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