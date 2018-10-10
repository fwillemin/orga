<?= form_open('affaires/ModAffaireDivers', array('id' => 'formModAffaireDivers')); ?>
<div class="form-row" style="margin-top: 4px;">
    <div class="col-4 offset-4">
        <div id="demoAffaire" style="width: 100%;" class="affectation">
            <?= !empty($affaire) ? character_limiter($affaire->getAffaireClient()->getClientNom(), 10) : 'DEMO'; ?>
        </div>
    </div>
</div>
<div class="form-row" style="margin-top: 4px; text-align: center;">
    <div class="col-4 offset-4">
        <label for="addAffaireCouleur">Couleur</label><br>
        <div id="selectCouleurAffaire" style="display: inline-block;"></div>
        <br>
        <input type="hidden" id="addAffaireCouleur" name="addAffaireCouleur" value="<?= !empty($affaire) ? $affaire->getAffaireCouleur() : '#CCCCCC'; ?>">
    </div>
</div>
<hr>
<center>
    <button type="submit" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-edit"></i> Modifier
    </button
    <i class="fas fa-circle-notch fa-spin formloader" id="loaderModAffaireDivers"></i>
</center>
<?= form_close(); ?>