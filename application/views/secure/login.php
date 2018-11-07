<div class="row" style="margin-top:40px;">
    <div class="col-12 col-sm-5 offset-sm-1" style="padding-top:70px;">
        <h1 style="font-weight: bold;">Nouveautés</h1>
        <h4><i class="fas fa-chevron-circle-right"></i> Nouvelle gestion des utilisateurs</h4>
        Vous pouvez maintenant définir pour chaque utilisateur des accès spécifiques à chaque module d'Organibat (Heures, affaires, planning, ...)
        <br>
        <br>
        <h4><i class="fas fa-chevron-circle-right"></i> Association des ouvriers en équipes</h4>
        Créez des équipes et accelérer vos plannifications et vos saisies d'heures.
        <br>
        <br>
        <h4><i class="fas fa-chevron-circle-right"></i> Gestion des clients</h4>
        Historiques des chantiers par client, ...
        <br>
        <br>
        <h4><i class="fas fa-chevron-circle-right"></i> Nouvelles fonctionnalités de planning</h4>
        Décallage de mutiples affectations
        <br>Insersion d'affectation sur plusieurs personnels
        <br>Glisser/Déposer des livraisons
        <br>Ajout d'achats et de livraisons depuis le planning
        <br>Décallez toutes les affectations d'un personnel en une fois
    </div>

    <div class="col-12 col-sm-3 offset-sm-2">
        <div class="row" style="border:1px solid grey; border-radius:10px; border: 2px solid #293042; text-align: center; background-color: #FFF;">
            <div class="col-12" style="background-color: #293042; padding:10px;">
                <img src="<?php echo base_url('assets/img/logoClairTexte.png'); ?>" style="height:60px;" >
            </div>
            <div class="col-12" style="padding:10px">
                <?= form_open('secure/tryLogin', array('class' => 'form-horizontal', 'id' => 'formLogin')); ?>
                <div class="form-group">
                    <input type="text" name="login" id="login" value="" placeholder="Identifiant" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" name="pass" id="pass" value="" placeholder="Mot de passe" class="form-control">
                </div>
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </button>
                <?= form_close(); ?>
            </div>

        </div>
    </div>
</div>