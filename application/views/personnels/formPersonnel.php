<?= form_open('personnels/addPersonnel', array('id' => 'formAddPersonnel')); ?>
<input type="hidden" name="addPersonnelId" id="addPersonnelId" value="<?= !empty($personnel) ? $personnel->getPersonnelId() : ''; ?>">
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <input type="text" class="form-control form-control-sm" id="addPersonnelNom" name="addPersonnelNom" placeholder="Nom" value="<?= !empty($personnel) ? $personnel->getPersonnelNom() : ''; ?>">
    </div>
    <div class="col">
        <input type="text" class="form-control form-control-sm" id="addPersonnelPrenom" name="addPersonnelPrenom" placeholder="Prénom" value="<?= !empty($personnel) ? $personnel->getPersonnelPrenom() : ''; ?>">
    </div>
</div>

<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addPersonnelQualif">Qualification et niveau</label>
        <input type="text" class="form-control form-control-sm" id="addPersonnelQualif" name="addPersonnelQualif" placeholder="Qualification et niveau" value="<?= !empty($personnel) ? $personnel->getPersonnelQualif() : ''; ?>">
        <small class="form-text text-muted">Changer l'email ne changera pas le login.</small>
    </div>
</div>
<div class="form-row">
    <div class="col">
        <label for="addPersonnelEquipeId">Equipe</label>
        <select name="addPersonnelEquipeId" id="addPersonnelEquipeId" class="form-control form-control-sm">
            <option value="0" <?= (!empty($personnel) && !$personnel->getPersonnelEquipeId()) ? 'selected' : ''; ?>>Seul</option>
            <?php
            if (!empty($equipes)):
                foreach ($equipes as $equipe):
                    echo '<option value="' . $equipe->getEquipeId() . '" ' . ((!empty($personnel) && $personnel->getPersonnelEquipeId() == $equipe->getEquipeId()) ? 'selected' : '') . '>' . $equipe->getEquipeNom() . '</option>';
                endforeach;
            endif;
            ?>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="col">
        <label for="addPersonnelHoraireId">Horaire</label>
        <select name="addPersonnelHoraireId" id="addPersonnelHoraireId" class="form-control form-control-sm">
            <option value="0" <?= (!empty($personnel) && !$personnel->getPersonnelHoraireId()) ? 'selected' : ''; ?>>Aucun horaire spécifié</option>
            <?php
            if (!empty($horaires)):
                foreach ($horaires as $horaire):
                    echo '<option value="' . $horaire->getHoraireId() . '" ' . ((!empty($personnel) && $personnel->getPersonnelHoraireId() == $horaire->getHoraireId()) ? 'selected' : '') . '>' . $horaire->getHoraireNom() . '</option>';
                endforeach;
            endif;
            ?>
        </select>
    </div>
    <div class="col">
        <label for="addPersonnelPointages">Générer les fiches de pointages</label>
        <select name="addPersonnelPointages" id="addPersonnelPointages" class="form-control form-control-sm">
            <option value="1" <?= (!empty($personnel) && $personnel->getPersonnelPointages() == 1) ? 'selected' : ''; ?>>Au réél des heures</option>
            <option value="2" <?= (!empty($personnel) && $personnel->getPersonnelPointages() == 2) ? 'selected' : ''; ?>>Suivant l'horaire attribué</option>
        </select>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addPersonnelMessage">Message personnel</label>
        <div class="input-group">
            <textarea name="addPersonnelMessage" id="addPersonnelMessage" rows="3" class="form-control form-control-sm"><?= !empty($personnel) ? $personnel->getPersonnelMessage() : ''; ?></textarea>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="btnDelMessage"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col-4">
        <label for="addPersonnelCode">Code</label>
        <input type="text" class="form-control form-control-sm" id="addPersonnelCode" name="addPersonnelCode" placeholder="Code secret" value="<?= !empty($personnel) ? $personnel->getPersonnelCode() : ''; ?>">
        <small class="form-text text-muted">4 chiffres</small>
    </div>
    <div class="col-6">
        <label for="addPersonnelPortable">Portable</label>
        <input type="text" class="form-control form-control-sm" id="addPersonnelPortable" name="addPersonnelPortable" placeholder="N° Tel Portable" value="<?= !empty($personnel) ? $personnel->getPersonnelPortable() : ''; ?>">
        <small class="form-text text-muted">pour SMS</small>
    </div>
    <div class="col-2" style="text-align: center;">
        <label for="addPersonnelActif">Actif</label>
        <input class="form-control form-control-sm" type="checkbox" class="form-control form-control-sm" id="addPersonnelActif" name="addPersonnelActif" <?= (!empty($personnel) && $personnel->getPersonnelActif() == 0) ? '' : 'checked'; ?>>
    </div>
</div>
<center>
    <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;">
        <?= !empty($personnel) ? '<i class="fas fa-edit"></i> Modifier' : '<i class="fas fa-plus-square"></i> Ajouter'; ?>
    </button
    <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddPersonnel"></i>
</center>
<?= form_close(); ?>
