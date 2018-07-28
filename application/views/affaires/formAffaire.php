<?= form_open('affaires/addAffaire', array('id' => 'formAddAffaire')); ?>
<input type="hidden" name="addAffaireId" id="addAffaireId" value="<?= !empty($affaire) ? $affaire->getAffaireId() : ''; ?>">
<div class="form-row" style="margin-top: 4px; border-bottom: 1px dashed grey;">
    <div class="col">
        <label for="addAffaireClientId">Sélectionnez un client</label><br>
        <select name="addAffaireClientId" id="addAffaireClientId" class="col-12 selectpicker show-tick" data-live-search="true"  title="Sélectionnez un client..." required>
            <?php
            if (!empty($clients)):
                foreach ($clients as $client):
                    $isClientSelect = '';
                    if (!empty($affaire) && $client->getClientId() == $affaire->getAffaireClientId()):
                        $isClientSelect = 'selected';
                    endif;
                    echo '<option value="' . $client->getClientId() . '" data-subtext="' . $client->getClientVille() . '" ' . $isClientSelect . '>' . $client->getClientNom() . '</option>';
                endforeach;
                unset($client);
            endif;
            ?>
        </select>
        <button type="button" class="btn btn-link btn-sm" id="btnAddClient">
            <i class="fas fa-plus-square"></i> Ajouter un client
        </button>
        <button type="button" class="btn btn-link btn-sm" style="position: absolute; right: 15px; bottom:1px; color:grey;" id="btnFicheClient">
            <i class="fas fa-link"></i>
        </button>
    </div>
    <div class="col">
        <label for="addAffairePlaceId">Sélectionnez une place</label><br>
        <select name="addAffairePlaceId" id="addAffairePlaceId" class="col-12 selectpicker show-tick"  title="Sélectionnez une localisation..." required>
            <?php
            if (!empty($affaire) && $affaire->getAffaireClient()->getClientPlaces()):
                foreach ($affaire->getAffaireClient()->getClientPlaces() as $place):
                    $isPlaceSelect = '';
                    if ($affaire->getAffairePlaceId() == $place->getPlaceId()):
                        $isPlaceSelect = 'selected';
                    endif;
                    echo '<option value="' . $place->getPlaceId() . '" ' . $isPlaceSelect . '>' . $place->getPlaceAdresse() . '</option>';
                endforeach;
                unset($place);
            endif;
            ?>
        </select>
        <button type="button" class="btn btn-link btn-sm" id="btnAddPlaceAffaire">
            <i class="fas fa-plus-square"></i> Ajouter une place
        </button>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAffaireCategorieId">Catégorie</label><br>
        <select name="addAffaireCategorieId" id="addAffaireCategorieId" class="selectpicker show-tick" data-width="auto" data-live-search="true" title="Sélectionnez une catégorie...">
            <option value="0">Non classée</option>
            <?php
            if (!empty($categories)):
                foreach ($categories as $categorie):
                    $isCategorieSelect = '';
                    if (!empty($affaire) && $categorie->getCategorieId() == $affaire->getAffaireCategorieId()):
                        $isCategorieSelect = 'selected';
                    endif;
                    echo '<option value="' . $categorie->getCategorieId() . '" ' . $isCategorieSelect . '>' . $categorie->getCategorieNom() . '</option>';
                endforeach;
            endif;
            ?>
        </select>
    </div>
    <div class="col">
        <label for="addAffaireCommercialId">Commercial</label><br>
        <select name="addAffaireCommercialId" id="addAffaireCommercialId" class="selectpicker show-tick" data-width="auto">
            <option value="0">Non attribué</option>
            <?php
            if (!empty($commerciaux)):
                foreach ($commerciaux as $commercial):
                    $isCommercialSelect = '';
                    if (!empty($affaire) && $commercial->getId() == $affaire->getAffaireCommercialId()):
                        $isCommercialSelect = 'selected';
                    endif;
                    echo '<option value="' . $commercial->getId() . '"' . $isCommercialSelect . '>' . $commercial->getuserPrenom() . ' ' . $commercial->getUserNom() . '</option>';
                endforeach;
            endif;
            ?>
        </select>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAffaireObjet">Objet</label>
        <input type="text" class="form-control form-control-sm" id="addAffaireObjet" name="addAffaireObjet" placeholder="Objet de l'affaire" value="<?= !empty($affaire) ? $affaire->getAffaireObjet() : ''; ?>">
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAffaireDevis">Devis N°</label>
        <input type="text" class="form-control form-control-sm" id="addAffaireDevis" name="addAffaireDevis" placeholder="N° du devis" value="<?= !empty($affaire) ? $affaire->getAffaireDevis() : ''; ?>">
    </div>
    <div class="col">
        <label for="addAffairePrix">Prix HT</label>
        <input type="text" class="form-control form-control-sm" id="addAffairePrix" name="addAffairePrix" placeholder="Prix vendu HT" value="<?= !empty($affaire) ? $affaire->getAffairePrix() : ''; ?>" >
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col-5">
        <label for="addAffaireSignature">Signée le</label>
        <input type="date" class="form-control form-control-sm" id="addAffaireDateSignature" name="addAffaireDateSignature" value="<?= !empty($affaire) && $affaire->getAffaireDateSignature() ? date('Y-m-d', $affaire->getAffaireDateSignature()) : ''; ?>" >
    </div>
    <div class="col">
        <br>
        <div id="demoAffaire" style="width: 100%;" class="affectation">
            <?= !empty($affaire) ? character_limiter($affaire->getAffaireClient()->getClientNom(), 10) : 'DEMO'; ?>
        </div>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAffaireFixe">Informations complémentaires</label>
        <textarea class="form-control form-control-sm" id="addAffaireRemarque" name="addAffaireRemarque" rows="5"><?= !empty($affaire) ? $affaire->getAffaireRemarque() : ''; ?></textarea>
    </div>
    <div class="col-4">
        <label for="addAffaireCouleur">Couleur</label><br>
        <div id="selectCouleurAffaire" style="display: inline-block;"></div>
        <br>
        <input type="hidden" id="addAffaireCouleur" name="addAffaireCouleur" value="<?= !empty($affaire) ? $affaire->getAffaireCouleur() : '#CCCCCC'; ?>">
    </div>
</div>
<hr>
<center>
    <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;" id="btnSubmitFormAffaire">
        <?= !empty($affaire) ? '<i class="fas fa-edit"></i> Modifier' : '<i class="fas fa-plus-square"></i> Ajouter'; ?>
    </button
    <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddAffaire"></i>
</center>
<?= form_close(); ?>