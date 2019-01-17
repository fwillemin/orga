<?= form_open('chantiers/addAchat', array('id' => 'formAddAchat'));
?>
<input type="hidden" name="addAchatId" id="addAchatId" value="<?= !empty($achat) ? $achat->getAchatId() : ''; ?>">
<input type="hidden" name="addAchatChantierId" id="addAchatChantierId" value="<?= !empty($chantier) ? $chantier->getChantierId() : ''; ?>">
<span class="badge badge-danger js-onAchatMod" style="<?= !$this->uri->segment(4) ? 'display:none;' : ''; ?>">
    Modification d'un achat en cours...
</span>
<div class="form-row" style="margin-top: 4px;">
    <div class="col-3">
        <label for="addAchatDate">Date</label>
        <input type="date" class="form-control form-control-sm" id="addAchatDate" name="addAchatDate" value="<?= !empty($achat) ? date('Y-m-d', $achat->getAchatDate()) : date('Y-m-d'); ?>">
    </div>
    <div class="col-3">
        <label for="addAchatType">Type</label>
        <select name="addAchatType" id="addAchatType" class="form-control form-control-sm">
            <option value="1" <?= !empty($achat) && $achat->getAchatType() == 1 ? 'selected' : ''; ?>>Matière première</option>
            <option value="2" <?= !empty($achat) && $achat->getAchatType() == 2 ? 'selected' : ''; ?>>Matériel</option>
            <option value="3" <?= !empty($achat) && $achat->getAchatType() == 3 ? 'selected' : ''; ?>>Outillage</option>
            <option value="4" <?= !empty($achat) && $achat->getAchatType() == 4 ? 'selected' : ''; ?>>Sous-traitance</option>
        </select>
    </div>
    <div class="col-4">
        <label for="addAchatFournisseurId">Fournisseur</label>
        <select name="addAchatFournisseurId" id="addAchatFournisseurId" class="form-control form-control-sm">
            <option value="0">Non référencé</option>
            <?php
            if (!empty($fournisseurs)):
                foreach ($fournisseurs as $fournisseur):
                    $isFournisseurSelect = '';
                    if (!empty($achat) && $fournisseur->getFournisseurId() == $achat->getAchatFournisseurId()):
                        $isFournisseurSelect = 'selected';
                    endif;
                    echo '<option value="' . $fournisseur->getFournisseurId() . '"' . $isFournisseurSelect . '>' . $fournisseur->getFournisseurNom() . '</option>';
                endforeach;
            endif;
            ?>
        </select>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAchatDescription">Description</label>
        <input type="text" class="form-control form-control-sm" id="addAchatDescription" name="addAchatDescription" placeholder="Description de l'achat" value="<?= !empty($achat) ? $achat->getAchatDescription() : ''; ?>">
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAchatQtePrevisionnel">Prévisionnel</label>
        <div class="input-group input-group-sm">
            <input type="numeric" class="form-control form-control-sm text-right" id="addAchatQtePrevisionnel" name="addAchatQtePrevisionnel" placeholder="Qté" value="<?= !empty($achat) ? $achat->getAchatQtePrevisionnel() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">[QTE] x [PRIX]</span>
            </div>
            <input type="numeric" class="form-control form-control-sm text-right" id="addAchatPrixPrevisionnel" name="addAchatPrixPrevisionnel" placeholder="Prix HT" value="<?= !empty($achat) ? $achat->getAchatPrixPrevisionnel() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">=</span>
            </div>
            <input type="numeric" class="form-control form-control-sm text-right" id="addAchatTotalPrevisionnel" placeholder="Total prévisionnel" value="<?= !empty($achat) ? $achat->getAchatTotalPrevisionnel() : ''; ?>" disabled>
        </div>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col">
        <label for="addAchatQte">Réel</label>
        <div class="input-group input-group-sm">
            <input type="numeric" class="form-control form-control-sm text-right" id="addAchatQte" name="addAchatQte" placeholder="Qté" value="<?= !empty($achat) ? $achat->getAchatQte() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">[QTE] x [PRIX]</span>
            </div>
            <input type="numeric" class="form-control form-control-sm text-right" id="addAchatPrix" name="addAchatPrix" placeholder="Prix HT" value="<?= !empty($achat) ? $achat->getAchatPrix() : ''; ?>">
            <div class="input-group-append">
                <span class="input-group-text">=</span>
            </div>
            <input type="numeric" class="form-control form-control-sm text-right" id="addAchatTotal" placeholder="Total réel" value="<?= !empty($achat) ? $achat->getAchatTotal() : ''; ?>" disabled>
        </div>
    </div>
</div>
<div class="form-row" style="margin-top: 4px;">
    <div class="col-2">
        <div style="position: relative; top:30px; text-align: right; font-size: 18px;">
            Livraison  <i class="fas fa-truck-loading" style="margin-left: 15px;"></i>
        </div>
    </div>
    <div class="col-3">
        <label for="addAchatLivraisonDate">Date</label>
        <input type="date" class="form-control form-control-sm" id="addAchatLivraisonDate" name="addAchatLivraisonDate" value="<?= !empty($achat) && $achat->getAchatLivraisonDate() ? date('Y-m-d', $achat->getAchatLivraisonDate()) : ''; ?>">
    </div>
    <div class="col-3">
        <label for="addAchatLivraisonAvancement">Type</label>
        <select name="addAchatLivraisonAvancement" id="addAchatLivraisonAvancement" class="form-control form-control-sm">
            <option value="0" <?= empty($achat) || !$achat->getAchatLivraisonAvancement() ? 'selected' : ''; ?>>Non concerné</option>
            <option value="1" <?= !empty($achat) && $achat->getAchatLivraisonAvancement() == 1 ? 'selected' : ''; ?>>En attente</option>
            <option value="2" <?= !empty($achat) && $achat->getAchatLivraisonAvancement() == 2 ? 'selected' : ''; ?>>Confirmée</option>
            <option value="3" <?= !empty($achat) && $achat->getAchatLivraisonAvancement() == 3 ? 'selected' : ''; ?>>Récéptionée</option>
        </select>
    </div>
</div>
<button type="button" class="btn btn-sm btn-link js-onAchatMod" id="btnDelAchat" style="<?= !$this->uri->segment(4) ? 'display:none;' : ''; ?> color: red; position: absolute; bottom: 0px;">
    <i class="fas fa-trash"></i>
</button>
<center>
    <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;" id="btnSubmitFormAchat">
        <?= !empty($achat) ? '<i class="fas fa-edit"></i> Modifier' : '<i class="fas fa-plus-square"></i> Ajouter'; ?>
    </button
    <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddAchat"></i>
</center>

<?= form_close(); ?>