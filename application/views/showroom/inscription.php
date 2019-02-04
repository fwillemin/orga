<div class="container fond" id="contenu">
    <div class="row" style="padding-top:20px;">
        <div class="col-12">
            <h1 style="font-size:36px;">Inscription gratuite Organibat</h1>
            Afin de vous faire découvrir notre logiciel et tout ce qu'il vous apportera dans la gestion quotidienne de vos chantiers et interventions, nous vous proposons une inscription gratuite qui vous donnera accès pendant 1 mois à <b>toutes les fonctionnalités sans restriction</b>.
            <br>
            <br>
            <p>
                Nous vous proposons également de vous appeller afin de vous accompagner dans le lancement d'Organibat : premiers pas, saisie du personnel de chantier, présentation des concepts du logiciel.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2">
            <br>
            <?= form_open('showroom/addInscription', array('id' => 'formInscription')); ?>
            <div class="form-row">
                <div class="form-group col-12">
                    <input type="text" class="form-control" id="inscriptionRS" name="inscriptionRS" placeholder="Raison sociale" value="" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-6">
                    <input type="text" class="form-control" id="inscriptionPrenom" name="inscriptionNom" placeholder="Nom" value="" required>
                </div>
                <div class="form-group col-6">
                    <input type="text" class="form-control" id="inscriptionNom" name="inscriptionPrenom" placeholder="Prénom" value="" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <input type="text" class="form-control" id="inscriptionAdresse" name="inscriptionAdresse" placeholder="Adresse" value="">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-4">
                    <input type="text" class="form-control" id="inscriptionCp" name="inscriptionCp" placeholder="Code postal" value="" required>
                </div>
                <div class="form-group col-8">
                    <input type="text" class="form-control" id="inscriptionVille" name="inscriptionVille" placeholder="Ville" value="" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-7 col-12">
                    <input type="email" class="form-control" id="inscriptionEmail" name="inscriptionEmail" placeholder="Email" value="" required>
                    <small>Vous recevrez vos identifiants de connexion sur cet email</small>
                </div>
                <div class="form-group col-12 col-sm-5">
                    <input type="text" class="form-control" id="inscriptionTelephone" name="inscriptionTelephone" placeholder="Téléphone" value="" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12 col-sm-4">
                    <label for="inscriptionMoisFiscal">Début d'exercice fiscal</label>
                    <select class="form-control" id="inscriptionMoisFiscal" name="inscriptionMoisFiscal">
                        <option value="1">Janvier</option>
                        <option value="2">Février</option>
                        <option value="3">Mars</option>
                        <option value="4">Avril</option>
                        <option value="5">Mai</option>
                        <option value="6">Juin</option>
                        <option value="7">Juillet</option>
                        <option value="8">Août</option>
                        <option value="9">Septembre</option>
                        <option value="10">Octobre</option>
                        <option value="11">Novembre</option>
                        <option value="12">Décembre</option>
                    </select>
                </div>
                <div class="form-group col-12 col-sm-8">
                    <label for="inscriptionMoisFiscal">Votre identifiant</label>
                    <input class="form-control" type="text" name="inscriptionDomaine" id="inscriptionDomaine" value="" readonly="readonly">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-1" style="padding:5px 0px;">
                    <input type="checkbox" class="form-control" id="inscriptionHelp" name="inscriptionHelp" value="1">
                </div>
                <div class="form-group col-10">
                    Je souhaite être rappelé afin d'être accompagné dans la découverte du logiciel
                </div>
            </div>
            <button type="submit" class="btn btn-info btn-sm col-12" id="btnSubmitInscription">
                <i class="fas fa-plus-circle"></i> Valider mon inscription
            </button>
            <i class="fas fa-circle-notch fa-spin formloader" id="loaderInscription"></i>
            <?= form_close(); ?>
            <br>
        </div>
    </div>
</div>