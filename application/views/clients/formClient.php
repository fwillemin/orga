<?= form_open('clients/addClient', array('id' => 'formAddClient')); ?>
<input type="hidden" name="addClientId" id="addClientId" value="<?= !empty($client) ? $client->getClientId() : ''; ?>">
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addClientNom">Nom</label>
        <input type="text" class="form-control form-control-sm" id="addClientNom" name="addClientNom" placeholder="Nom" value="<?= !empty($client) ? $client->getClientNom() : ''; ?>" required>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addClientAdresse">Adresse</label>
        <input type="text" class="form-control form-control-sm" id="addClientAdresse" name="addClientAdresse" placeholder="Adresse" value="<?= !empty($client) ? $client->getClientAdresse() : ''; ?>">
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <input type="text" class="form-control form-control-sm" id="addClientCp" name="addClientCp" placeholder="Code postal" value="<?= !empty($client) ? $client->getClientCp() : ''; ?>">
    </div>
    <div class="col">
        <input type="text" class="form-control form-control-sm" id="addClientVille" name="addClientVille" placeholder="Ville" value="<?= !empty($client) ? $client->getClientVille() : ''; ?>" required>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <select name="addClientPays" id="addClientPays" class="form-control form-control-sm" required>
            <option value="FRANCE">France</option>
        </select>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addClientFixe">Téléphones</label>
        <input type="text" class="form-control form-control-sm" id="addClientFixe" name="addClientFixe" placeholder="Fixe" value="<?= !empty($client) ? $client->getClientFixe() : ''; ?>">
    </div>
    <div class="col">
        <label for="addClientPortable">...</label>
        <input type="text" class="form-control form-control-sm" id="addClientPortable" name="addClientPortable" placeholder="Portable" value="<?= !empty($client) ? $client->getClientPortable() : ''; ?>">
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <input type="email" class="form-control form-control-sm" id="addClientEmail" name="addClientEmail" placeholder="Email" value="<?= !empty($client) ? $client->getClientEmail() : ''; ?>" autocomplete="off">
    </div>
</div>

<center>
    <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;" id="btnSubmitFormClient">
        <?= !empty($client) ? '<i class="fas fa-edit"></i> Modifier' : '<i class="fas fa-plus-square"></i> Ajouter'; ?>
    </button
    <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddClient"></i>
</center>
<?= form_close(); ?>
