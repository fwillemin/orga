<?= form_open('personnels/addIndisponibilite', array('id' => 'formAddIndispo')); ?>
<input type="hidden" name="addIndispoId" id="addIndispoId" value="">
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addIndispoPersonnelsIds">Sélectionnez du personnel</label><br>
        <select name="addIndispoPersonnelsIds[]" id="addIndispoPersonnelsIds" class="selectpicker" data-width="60%" multiple data-actions-box="true" required title="Choisissez un ou plusieurs personnels">
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
        <label for="addIndispoDebutDate">Date de début</label>
        <input type="date" class="form-control form-control-sm text-right" id="addIndispoDebutDate" name="addIndispoDebutDate" value="">
    </div>
    <div class="col">
        <label for="addIndispoDebutMoment">Moment</label>
        <select class="form-control form-control-sm" name="addIndispoDebutMoment" id="addIndispoDebutMoment">
            <option value="1">Matin</option>
            <option value="2">Après-midi</option>
        </select>
    </div>
    <div class="col">
        <label for="addIndispoNbDemi">Nb Demi</label>
        <select class="form-control form-control-sm" name="addIndispoNbDemi" id="addIndispoNbDemi" style="background-color:lightsteelblue; color:white; text-align: right;">
            <?php
            for ($i = 1; $i <= 100; $i++):
                echo '<option value="' . $i . '">' . $i . '</option>';
            endfor;
            ?>
        </select>
    </div>
    <div class="col">
        <label for="addIndispoFinDate">Date de fin</label>
        <input type="date" class="form-control form-control-sm text-right" id="addIndispoFinDate" name="addIndispoFinDate" value="">
    </div>
    <div class="col">
        <label for="addIndispoFinMoment">Moment</label>
        <select class="form-control form-control-sm" name="addIndispoFinMoment" id="addIndispoFinMoment">
            <option value="1">Matin</option>
            <option value="2">Après-midi</option>
        </select>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col-12 col-sm-6">
        <label for="addIndispoMotifId">Motif</label><br>
        <select name="addIndispoMotifId" id="addIndispoMotifId" class="selectpicker" data-width="60%" required title="Choisissez un motif">
            <?php
            $groupe = '';
            foreach ($motifs as $motif):
                if ($groupe !== $motif->getMotifGroupe()):
                    if ($groupe):
                        echo '</optgroup>';
                    endif;
                    echo '<optgroup label="' . $motif->getMotifGroupe() . '">';
                    $groupe = $motif->getMotifGroupe();
                endif;

                echo '<option data-content="<span class=\'selectpickerPersonnel\'>' . $motif->getMotifNom() . '</span>" value="' . $motif->getMotifId() . '">' . $motif->getMotifNom() . '</option>';
            endforeach;
            unset($motif);
            ?>
        </select>

    </div>
</div>

<hr>
<div class="row">
    <div class="col-6">
        <button class="btn btn-sm btn-outline-danger" id="btnDelIndispo" style="display: none;">
            <i class="fas fa-trash"></i> Supprimer
        </button>
    </div>
    <div class="col-6 text-right">
        <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;" id="btnSubmitFormIndispo">
            <i class="fas fa-plus-square"></i> Ajouter
        </button
        <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddIndispo"></i>
    </div>
</div>

<?= form_close(); ?>

