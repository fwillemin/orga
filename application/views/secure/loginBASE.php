<div class="container fond" id="contenu">
    <div class="row">
        <div class="col-12 col-sm-5" style="padding: 50px 30px;">
            <div class="row" style="border:1px solid grey; border-radius:10px; border: 2px solid #293042; text-align: center; background-color: #FFF;">
                <div class="col-12" style="background-color: #293042; color: lightgrey; padding:5px; font-weight: bold;">
                    ACCES CLIENT
                </div>
                <div class="col-12" style="padding:20px">
                    <?= form_open('secure/tryLogin', array('class' => 'form-horizontal', 'id' => 'formLogin')); ?>
                    <div class="form-group">
                        <input type="text" name="login" id="login" value="" placeholder="Identifiant" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" name="pass" id="pass" value="" placeholder="Mot de passe" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-outline-primary" id="btnSubmitLogin">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </button>
                    <i class="fas fa-spinner fa-pulse" style="display:none;" id="loader"></i>

                    <?= form_close(); ?>
                </div>
            </div>
            <br><br>
            <h5>Derniers Articles</h5>
            <div class="row" style="border-top: 1px dashed steelblue; margin-bottom:9px;">
                <div class="col-12 col-sm-3" style="padding:3px;">
                    <img src="<?= base_url('assets/img/organiser-ses-chantiers.jpg'); ?>" style="width:100%; border:1px solid grey;">
                </div>
                <div class="col-12 col-sm-9" style="font-size:14px;">
                    <a href="<?= site_url('planifier-ses-chantier-un-petit-effort-organisation-pour-une-meilleure-visibilite'); ?>"><h7>Planifier ses chantiers, un petit effort d'organisation pour une meilleure visibilité.</h7></a>
                    <br><small>Rédigé le 16 décembre 2018 - François W.</small>
                </div>
            </div>
            <div class="row" style="border-top: 1px dashed steelblue; margin-bottom:9px;">
                <div class="col-12 col-sm-3" style="padding:3px;">
                    <img src="<?= base_url('assets/img/cloud-vs-local.png'); ?>" style="width:100%; border:1px solid grey;">
                </div>
                <div class="col-12 col-sm-9" style="font-size:14px;">
                    <a href="<?= site_url('logiciel-de-gestion-de-chantier-en-local-ou-en-ligne'); ?>"><h7>Logiciel traditionnel ou en ligne ? Quelles différences pour quels impacts ?</h7></a>
                    <br><small>Rédigé le 21 janvier 2019 - François W.</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-7" style="padding:50px 20px 30px 40px;">
            <h1 style="font-weight: bold;">Nouveautés</h1>
            <h4 style="color:#1cb3fa;"><i class="fas fa-chevron-circle-right"></i> Mai 19 - Gestion des heures supplémentaires</h4>
            Nouveau module de gestion des heures supplémentaires avec compteur individuel et interface de gestion.
            <br>
            <br>
            <h5><i class="fas fa-chevron-circle-right"></i> Février 19</h5>
            - A la clôture d'un chantier, la date de clôture est celle de la dernière heure saisie pour ce chantier.
            <br>- Analyse des indisponibilités et des performances d'un ouvrier par année.
            <br>
            <br>
            <h5><i class="fas fa-chevron-circle-right"></i> Janvier 19 - Améliorations pour le module "Ouvriers de chantier"</h5>
            Le personnel de chantier peut être classé en 3 catégories (Salarié, intérimaire ou apprenti)
            <br>La gestion des équipes devient plus visuelle avec un code couleur par équipe, visible sur le planning.
            <br>
            <br>
            <h5><i class="fas fa-chevron-circle-right"></i> Planifications et de vos calculs d'avancement <strong style='color:orangered;'>à l'heure !</strong></h5>
            <h5><i class="fas fa-chevron-circle-right"></i> Nouvelle gestion des utilisateurs administratifs</h5>
            Vous pouvez maintenant définir pour chaque utilisateur des accès spécifiques à chaque module d'Organibat (Heures, affaires, planning, ...)
            <br>
            <br>
            <h5><i class="fas fa-chevron-circle-right"></i> Ouvriers et pointages</h5>
            Créez des équipes et accelérer vos plannifications et vos saisies d'heures.
            <br>Vous pouvez pointez avec une tranche personnalisable (5, 10, 15, 20, 30, 60 minutes)
            <br>
            <br>
            <h5><i class="fas fa-chevron-circle-right"></i> Gestion des clients</h5>
            Historiques des affaires et chantiers par client, ...
            <br>
            <br>
            <h5><i class="fas fa-chevron-circle-right"></i> Nouvelles fonctionnalités de planning</h5>
            Décallage de mutiples affectations
            <br>Insersion d'affectation sur plusieurs personnels
            <br>Glisser/Déposer des livraisons
            <br>Ajout d'achats et de livraisons depuis le planning
            <br>Décallez toutes les affectations d'un ouvrier en une fois
            <br>
            <br>
            <h5><i class="fas fa-chevron-circle-right"></i> Contacts entrants</h5>
            Nouvelle interface de gestion des contacts entrants
        </div>
    </div>
</div>