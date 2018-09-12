<?= form_open('planning/addAffectation', array('id' => 'formAddAffectation')); ?>
<input type="hidden" name="addAffectationId" id="addAffectationId" value="">
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAffectationChantierId">Sélectionnez un chantier</label><br>
        <select name="addAffectationChantierId" id="addAffectationChantierId" class="selectpicker" data-width="100%" data-live-search="true" required title="Selectionnez un chantier d'intervention">
            <?php
            if (!empty($affairesPlanning)):
                foreach ($affairesPlanning as $affaire):
                    if ($affaire && !empty($affaire->getAffaireChantiers())):
                        foreach ($affaire->getAffaireChantiers() as $chantier):
                            echo '<option value="' . $chantier->getChantierId() . '"'
                            . 'data-content="<span class=\'selectpickerClient\'>' . $affaire->getAffaireClient()->getClientNom() . '</span> <span class=\'selectpickerChantier\'>' . $chantier->getChantierObjet() . '</span>">' . $affaire->getAffaireClient()->getClientNom() . ' ' . $chantier->getChantierObjet() . '</option>';
                        endforeach;
                    endif;
                endforeach;
            endif;
            ?>
        </select>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAffectationPersonnelsIds">Sélectionnez du personnel</label><br>
        <select name="addAffectationPersonnelsIds[]" id="addAffectationPersonnelsIds" class="selectpicker" data-width="60%" multiple required title="Choisissez un ou plusieurs personnels">
            <?php
            $equipeId = 0;
            if (!empty($personnelsActifs)):
                foreach ($personnelsActifs as $personnel):
                    if ($equipeId !== $personnel->getPersonnelEquipeId()):
                        if ($equipeId > 0):
                            echo '</optgroup>';
                        endif;
                        if ($personnel->getPersonnelEquipeId() > 0):
                            echo '<optgroup label="' . $personnel->getPersonnelEquipe()->getEquipeNom() . '">';
                            $equipeId = $personnel->getPersonnelEquipeId();
                        endif;
                    endif;

                    echo '<option data-content="<span class=\'selectpickerPersonnel\'>' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom() . '</span>" value="' . $personnel->getPersonnelId() . '">' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom() . '</option>';
                endforeach;
            endif;
            ?>
        </select>
    </div>
</div>

<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAffectationDebutDate">Date de début</label>
        <input type="date" class="form-control form-control-sm text-right" id="addAffectationDebutDate" name="addAffectationDebutDate" value="">
    </div>
    <div class="col">
        <label for="addAffectationDebutMoment">Moment</label>
        <select class="form-control form-control-sm" name="addAffectationDebutMoment" id="addAffectationDebutMoment">
            <option value="1">Matin</option>
            <option value="2">Après-midi</option>
        </select>
    </div>
    <div class="col">
        <label for="addAffectationNbDemi">Nb Demi</label>
        <select class="form-control form-control-sm" name="addAffectationNbDemi" id="addAffectationNbDemi" style="background-color:lightsteelblue; color:white; text-align: right;">
            <?php
            for ($i = 1; $i <= 100; $i++):
                echo '<option value="' . $i . '">' . $i . '</option>';
            endfor;
            ?>
        </select>
    </div>
    <div class="col">
        <label for="addAffectationFinDate">Date de fin</label>
        <input type="date" class="form-control form-control-sm text-right" id="addAffectationFinDate" name="addAffectationFinDate" value="">
    </div>
    <div class="col">
        <label for="addAffectationFinMoment">Moment</label>
        <select class="form-control form-control-sm" name="addAffectationFinMoment" id="addAffectationFinMoment">
            <option value="1">Matin</option>
            <option value="2">Après-midi</option>
        </select>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col-4">
        <label for="addAffectationType">Type</label>
        <select class="form-control form-control-sm" name="addAffectationType" id="addAffectationType">
            <option value="1" selected>Chantier</option>
            <option value="2">Atelier</option>
            <option value="3">SAV</option>
        </select>
    </div>
    <div class="col">
        <label for="addAffectationDebutMoment">Remarques</label>
        <textarea rows="6" class="form-control form-control-sm" name="addAffectationCommentaire" id="addAffectationCommentaire"></textarea>
    </div>
</div>

<hr>
<center>
    <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;" id="btnSubmitFormAffectation">
        <i class="fas fa-plus-square"></i> Ajouter
    </button
    <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddAffectation"></i>
</center>
<?= form_close(); ?>

