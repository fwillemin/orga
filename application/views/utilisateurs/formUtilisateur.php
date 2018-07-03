
<?= form_open('utilisateurs/addUtilisateur', array('id' => 'formAddUtilisateur')); ?>
<input type="hidden" name="addUserId" id="addUserId" value="<?= !empty($utilisateur) ? $utilisateur->getId() : ''; ?>">
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <input type="text" class="form-control form-control-sm" id="addUserNom" name="addUserNom" placeholder="Nom" value="<?= !empty($utilisateur) ? $utilisateur->getUserNom() : ''; ?>">
    </div>
    <div class="col">
        <input type="text" class="form-control form-control-sm" id="addUserPrenom" name="addUserPrenom" placeholder="Prénom" value="<?= !empty($utilisateur) ? $utilisateur->getUserPrenom() : ''; ?>">
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <input type="email" class="form-control form-control-sm" id="addUserEmail" name="addUserEmail" placeholder="Email" value="<?= !empty($utilisateur) ? $utilisateur->getEmail() : ''; ?>">
        <small class="form-text text-muted">Changer l'email ne changera pas le login.</small>
    </div>
</div>
<div class="form-row" style="margin-top: 10px;">
    <div class="col">
        <input type="password" class="form-control form-control-sm" id="addUserPassword" name="addUserPassword" placeholder="Mot de passe" autocomplete="off">
    </div>
    <div class="col">
        <input type="password" class="form-control form-control-sm" id="addUserPasswordConfirm" name="addUserPasswordConfirm" placeholder="Confirmation" autocomplete="off">
    </div>
    <div class="col-12">
        <small class="form-text text-muted">Laisser les champs vides pour ne pas modifier le mot de passe</small>
    </div>
</div>
<center>
    <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;">
        <?= !empty($utilisateur) ? '<i class="fas fa-edit"></i> Modifier' : '<i class="fas fa-plus-square"></i> Ajouter'; ?>
    </button
    <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddUser"></i>
</center>
<?= form_close(); ?>