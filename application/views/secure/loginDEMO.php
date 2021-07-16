<div class="container fond" id="contenu">
    <div class="row">
        <div class="col-12 col-sm-5" style="padding: 50px 30px;">
            <div class="row" style="border:1px solid grey; border-radius:10px; border: 2px solid #293042; text-align: center; background-color: #FFF;">
                <div class="col-12" style="background-color: #293042; color: lightgrey; padding:5px; font-weight: bold;">
                    Identification Accès Démo
                </div>
                <div class="col-12" style="padding:20px">
                    <?= form_open('secure/tryLogin', array('class' => 'form-horizontal', 'id' => 'formLogin')); ?>
                    <div class="form-group">
                        <input type="text" name="login" id="login" value="demo.direction@organibat.com" placeholder="Identifiant" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" name="pass" id="pass" value="Organibat2021" placeholder="Mot de passe" class="form-control" autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-outline-primary" id="btnSubmitLogin">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </button>
                    <i class="fas fa-spinner fa-pulse" style="display:none;" id="loader"></i>

                    <?= form_close(); ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-7" style="padding:50px 20px 30px 40px;">
            <div class="alert alert-info">
                <h5>Comment se connecter en tant que gérant</h5>
                <em>identifiant :</em><b> demo.direction@organibat.com</b>
                <br><em>Mot de passe :</em><b> Organibat2021</b>
                <hr>
                <h5>Comment se connecter en tant que personnel de chantier</h5>
                <em>identifiant :</em><b> demo.chantier@organibat.com</b>
                <br><em>Mot de passe :</em><b> Organibat2021</b>
                <hr>
                Vous pouvez aussi <a href="https://www.organibat.com/essai-gratuit-logiciel-gestion-chantier">créer un compte gratuitement</a>, <b>sans carte bancaire</b> et tester Organibat pendant 1 mois. Toutes vos données seront conservées au moment de vous abonner.
            </div>
        </div>
    </div>
</div>