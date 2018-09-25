<?php echo form_open('fournisseurs/addFournisseur/', array('id' => 'formAddFournisseur')); ?>
<input type="hidden" name="addFournisseurId" value="<?= !empty($fournisseur) ? $fournisseur->getFournisseurId() : ''; ?>" id="addFournisseurId" >
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addFournisseurNom">Nom</label>
        <input type="text" name="addFournisseurNom" id="addFournisseurNom" class="form-control" value="<?= !empty($fournisseur) ? $fournisseur->getFournisseurNom() : ''; ?>" required placeholder="Nom - Raison sociale" >
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addFournisseurAdresse">Adresse</label>
        <input type="text" name="addFournisseurAdresse" id="addFournisseurAdresse" class="form-control" value="<?= !empty($fournisseur) ? $fournisseur->getFournisseurAdresse() : ''; ?>" placeholder="Adresse">
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col-4">
        <label for="addFournisseurCp">Code postal</label>
        <input type="text" name="addFournisseurCp" id="addFournisseurCp" class="form-control" value="<?= !empty($fournisseur) ? $fournisseur->getFournisseurCp() : ''; ?>" placeholder="Code postal">
    </div>
    <div class="col-8">
        <label for="addFournisseurVille">Ville</label>
        <input type="text" name="addFournisseurVille" id="addFournisseurVille" class="form-control" value="<?= !empty($fournisseur) ? $fournisseur->getFournisseurVille() : ''; ?>" placeholder="Ville">
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addFournisseurTelephone">Téléphone</label>
        <input type="text" name="addFournisseurTelephone" id="addFournisseurTelephone" class="form-control" value="<?= !empty($fournisseur) ? $fournisseur->getFournisseurTelephone() : ''; ?>" placeholder="Téléphone">
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addFournisseurEmail">Email</label>
        <input type="text" name="addFournisseurEmail" id="addFournisseurEmail" class="form-control" value="<?= !empty($fournisseur) ? $fournisseur->getFournisseurEmail() : ''; ?>" placeholder="Email">
    </div>
</div>
<br>
<center>
    <button class="btn btn-outline-primary btn-sm mx-auto" id="formFournisseurSubmit">
        <?= !empty($fournisseur) ? '<i class="fas fa-edit"></i> Modifier' : '<i class="fas fa-plus-square"></i> Ajouter'; ?>
    </button>
</center>
<?php echo form_close(); ?>
