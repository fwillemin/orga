<?= form_open('horaires/addHoraire', array('id' => 'formAddHoraire')); ?>
<input type="hidden" name="addHoraireId" id="addHoraireId" value="<?= !empty($horaire) ? $horaire->getHoraireId() : ''; ?>">
<div class="form-row" style="margin-top: 4px;">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Nom de l'horaire</span>
        </div>
        <input type="text" class="form-control form-control-sm" id="addHoraireNom" name="addHoraireNom" value="<?= !empty($horaire) ? $horaire->getHoraireNom() : ''; ?>" placeholder="Nom de l'horaire">
    </div>
</div>
Lundi
<div class="form-row">
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Le matin de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireLun1" name="addHoraireLun1" value="<?= !empty($horaire) ? $horaire->getHoraireLun1() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireLun2" name="addHoraireLun2" value="<?= !empty($horaire) ? $horaire->getHoraireLun2() : ''; ?>">
        </div>
    </div>
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">L'après-midi de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireLun3" name="addHoraireLun3" value="<?= !empty($horaire) ? $horaire->getHoraireLun3() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireLun4" name="addHoraireLun4" value="<?= !empty($horaire) ? $horaire->getHoraireLun4() : ''; ?>">
        </div>
    </div>
</div>
Mardi
<div class="form-row">
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Le matin de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireMar1" name="addHoraireMar1" value="<?= !empty($horaire) ? $horaire->getHoraireMar1() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireMar2" name="addHoraireMar2" value="<?= !empty($horaire) ? $horaire->getHoraireMar2() : ''; ?>">
        </div>
    </div>
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">L'après-midi de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireMar3" name="addHoraireMar3" value="<?= !empty($horaire) ? $horaire->getHoraireMar3() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireMar4" name="addHoraireMar4" value="<?= !empty($horaire) ? $horaire->getHoraireMar4() : ''; ?>">
        </div>
    </div>
</div>
Mercredi
<div class="form-row">
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Le matin de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireMer1" name="addHoraireMer1" value="<?= !empty($horaire) ? $horaire->getHoraireMer1() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireMer2" name="addHoraireMer2" value="<?= !empty($horaire) ? $horaire->getHoraireMer2() : ''; ?>">
        </div>
    </div>
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">L'après-midi de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireMer3" name="addHoraireMer3" value="<?= !empty($horaire) ? $horaire->getHoraireMer3() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireMer4" name="addHoraireMer4" value="<?= !empty($horaire) ? $horaire->getHoraireMer4() : ''; ?>">
        </div>
    </div>
</div>
Jeudi
<div class="form-row">
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Le matin de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireJeu1" name="addHoraireJeu1" value="<?= !empty($horaire) ? $horaire->getHoraireJeu1() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireJeu2" name="addHoraireJeu2" value="<?= !empty($horaire) ? $horaire->getHoraireJeu2() : ''; ?>">
        </div>
    </div>
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">L'après-midi de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireJeu3" name="addHoraireJeu3" value="<?= !empty($horaire) ? $horaire->getHoraireJeu3() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireJeu4" name="addHoraireJeu4" value="<?= !empty($horaire) ? $horaire->getHoraireJeu4() : ''; ?>">
        </div>
    </div>
</div>
Vendredi
<div class="form-row">
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Le matin de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireVen1" name="addHoraireVen1" value="<?= !empty($horaire) ? $horaire->getHoraireVen1() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireVen2" name="addHoraireVen2" value="<?= !empty($horaire) ? $horaire->getHoraireVen2() : ''; ?>">
        </div>
    </div>
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">L'après-midi de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireVen3" name="addHoraireVen3" value="<?= !empty($horaire) ? $horaire->getHoraireVen3() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireVen4" name="addHoraireVen4" value="<?= !empty($horaire) ? $horaire->getHoraireVen4() : ''; ?>">
        </div>
    </div>
</div>
Samedi
<div class="form-row">
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Le matin de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireSam1" name="addHoraireSam1" value="<?= !empty($horaire) ? $horaire->getHoraireSam1() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireSam2" name="addHoraireSam2" value="<?= !empty($horaire) ? $horaire->getHoraireSam2() : ''; ?>">
        </div>
    </div>
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">L'après-midi de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireSam3" name="addHoraireSam3" value="<?= !empty($horaire) ? $horaire->getHoraireSam3() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireSam4" name="addHoraireSam4" value="<?= !empty($horaire) ? $horaire->getHoraireSam4() : ''; ?>">
        </div>
    </div>
</div>
Dimanche
<div class="form-row">
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Le matin de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireDim1" name="addHoraireDim1" value="<?= !empty($horaire) ? $horaire->getHoraireDim1() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireDim2" name="addHoraireDim2" value="<?= !empty($horaire) ? $horaire->getHoraireDim2() : ''; ?>">
        </div>
    </div>
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">L'après-midi de</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireDim3" name="addHoraireDim3" value="<?= !empty($horaire) ? $horaire->getHoraireDim3() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">à</span>
            </div>
            <input type="time" class="form-control form-control-sm" id="addHoraireDim4" name="addHoraireDim4" value="<?= !empty($horaire) ? $horaire->getHoraireDim4() : ''; ?>">
        </div>
    </div>
</div>

<center>
    <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;">
        <?= !empty($horaire) ? '<i class="fas fa-edit"></i> Modifier' : '<i class="fas fa-plus-square"></i> Ajouter'; ?>
    </button
    <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddHoraire"></i>
</center>
<?= form_close(); ?>