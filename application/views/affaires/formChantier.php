<?= form_open('chantiers/addChantier', array('id' => 'formAddChantier')); ?>
<input type="hidden" name="addChantierId" id="addChantierId" value="<?= !empty($chantier) ? $chantier->getChantierId() : ''; ?>">
<input type="hidden" name="addChantierAffaireId" id="addChantierAffaireId" value="<?= !empty($chantier) ? $chantier->getChantierAffaireId() : $affaire->getAffaireId(); ?>">
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addChantierObjet">Objet</label>
        <input type="text" class="form-control form-control-sm" id="addChantierObjet" name="addChantierObjet" placeholder="Objet du chantier" value="<?= !empty($chantier) ? $chantier->getChantierObjet() : ''; ?>" required>
    </div>
</div>
<div class="form-row" style="margin-top: 4px; border-bottom: 1px dashed grey;">
    <div class="col">
        <label for="addChantierPlaceId">Sélectionnez une place</label><br>
        <select name="addChantierPlaceId" id="addChantierPlaceId" class="col-12 selectpicker show-tick"  title="Sélectionnez une localisation..." required>
            <?php
            $places = false;
            if (!empty($chantier) && $chantier->getChantierClient()->getClientPlaces()):
                $places = $chantier->getChantierClient()->getClientPlaces();
            elseif ($affaire->getAffaireClient()->getClientPlaces()):
                $places = $affaire->getAffaireClient()->getClientPlaces();
            endif;
            if ($places):
                foreach ($places as $place):
                    $isPlaceSelect = '';
                    if (!empty($chantier) && $chantier->getChantierPlaceId() == $place->getPlaceId()):
                        $isPlaceSelect = 'selected';
                    endif;
                    echo '<option value="' . $place->getPlaceId() . '" ' . $isPlaceSelect . '>' . $place->getPlaceAdresse() . '</option>';
                endforeach;
                unset($place);
            endif;
            ?>
        </select>
        <button type="button" class="btn btn-link btn-sm" id="btnAddPlaceChantier">
            <i class="fas fa-plus-square"></i> Ajouter une place
        </button>
    </div>
    <div class="col">
        <label for="addChantierCategorieId">Catégorie</label><br>
        <select name="addChantierCategorieId" id="addChantierCategorieId" class="selectpicker show-tick" data-width="auto" data-live-search="true" title="Sélectionnez une catégorie...">
            <option value="0">Non classée</option>
            <?php
            if (!empty($categories)):
                foreach ($categories as $categorie):
                    $isCategorieSelect = '';
                    if (!empty($chantier) && $categorie->getCategorieId() == $chantier->getChantierCategorieId()):
                        $isCategorieSelect = 'selected';
                    endif;
                    echo '<option value="' . $categorie->getCategorieId() . '" ' . $isCategorieSelect . '>' . $categorie->getCategorieNom() . '</option>';
                endforeach;
            endif;
            ?>
        </select>
    </div>
</div>

<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addChantierPrix">Prix vendu</label>
        <div class="input-group input-group-sm">
            <input type="nuemric" class="form-control form-control-sm text-right" id="addChantierPrix" name="addChantierPrix" placeholder="Valeur du chantier" value="<?= $affaire->getAffairePrixNonAttribue(); ?>">
            <div class="input-group-append">
                <span class="input-group-text">€</span>
            </div>
        </div>
    </div>
    <div class="col">
        <label for="addChantierHeuresPrevues">Heures prévues</label>
        <div class="input-group input-group-sm">
            <input type="numeric" required class="form-control form-control-sm text-right" id="addChantierHeuresPrevues" name="addChantierHeuresPrevues" placeholder="Nombre heures prévues" value="<?= !empty($chantier) ? $chantier->getChantierHeuresPrevues() : ''; ?>" >
            <div class="input-group-append">
                <span class="input-group-text">heures</span>
            </div>
        </div>
    </div>
    <div class="col">
        <label for="addChantierBudgetAchats">Budget achats</label>
        <div class="input-group input-group-sm">
            <input type="numeric" required class="form-control form-control-sm text-right" id="addChantierBudgetAchats" name="addChantierBudgetAchats" placeholder="Budget pour les achats" value="<?= !empty($chantier) ? $chantier->getChantierBudgetAchats() : ''; ?>" >
            <div class="input-group-append">
                <span class="input-group-text">€</span>
            </div>
        </div>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addChantierFraisGeneraux">Frais généraux</label>
        <div class="input-group input-group-sm">
            <input type="numeric" required class="form-control form-control-sm text-right" id="addChantierFraisGeneraux" name="addChantierFraisGeneraux" placeholder="Taux de frais généraux" value="<?= !empty($chantier) ? $chantier->getChantierFraisGeneraux() : $this->session->userdata('etablissementTFG'); ?>">
            <div class="input-group-append">
                <span class="input-group-text">%</span>
            </div>
        </div>
    </div>
    <div class="col">
        <label for="addChantierTauxHoraireMoyen">Taux horaire moyen</label>
        <div class="input-group input-group-sm">
            <input type="numeric" required class="form-control form-control-sm text-right" id="addChantierTauxHoraireMoyen" name="addChantierTauxHoraireMoyen" placeholder="Taux horaire moyen" value="<?= !empty($chantier) ? $chantier->getChantierTauxHoraireMoyen() : $this->session->userdata('etablissementTHM'); ?>">
            <div class="input-group-append">
                <span class="input-group-text">€/h</span>
            </div>
        </div>
    </div>
    <div class="col">
        <br>
        <div id="demoChantier" style="width: 100%;" class="affectation" data-couleuraffaire="<?= !empty($affaire) ? $affaire->getAffaireCouleur() : $chantier->getChantierAffaire()->getAffaireCouleur(); ?>">
            <?= !empty($chantier) ? character_limiter($chantier->getChantierClient()->getClientNom(), 10) : 'DEMO'; ?>
        </div>
    </div>
</div>

<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addChantierFixe">Informations complémentaires</label>
        <textarea class="form-control form-control-sm" id="addChantierRemarque" name="addChantierRemarque" rows="5"><?= !empty($chantier) ? $chantier->getChantierRemarque() : ''; ?></textarea>
    </div>
    <div class="col-4">

        <div id="selectCouleurChantier" style="display: inline-block;"></div>
        <br>
        <input type="hidden" id="addChantierCouleur" name="addChantierCouleur" value="<?= !empty($chantier) ? $chantier->getChantierCouleur() : $affaire->getAffaireCouleur(); ?>">
        <button class="btn btn-link btn-sm" type="button" id="btnResetCouleurChantier">
            <i class="fas fa-paint-roller"></i> Appliquer la couleur de l'affaire
        </button>
    </div>
</div>
<hr>
<center>
    <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;" id="btnSubmitFormChantier">
        <?= !empty($chantier) ? '<i class="fas fa-edit"></i> Modifier' : '<i class="fas fa-plus-square"></i> Ajouter'; ?>
    </button
    <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddChantier"></i>
</center>
<?= form_close(); ?>

