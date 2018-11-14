<div class="row" style="padding-top:10px; font-size:14px;">
    <div class="col-12 col-md-6">
        Affaire attribuée à <strong><?= $affaire->getAffaireCommercial() ? $affaire->getAffaireCommercial()->getUserPrenom() . ' ' . $affaire->getAffaireCommercial()->getUserNom() : '<small class="light">Non attribué</small>'; ?></strong>
        liée au devis N° <?= $affaire->getAffaireDevis(); ?>
        <br>Prix : <?= number_format($affaire->getAffairePrix(), 2, ',', ' ') . '€ HT'; ?>
        <br>Signée le <?= $affaire->getAffaireDateSignature() ? $this->cal->dateFrancais($affaire->getAffaireDateSignature()) : '<small class="light">--</small>'; ?>
        <br>Clôturée le <?= $affaire->getAffaireDateCloture() ? $this->cal->dateFrancais($affaire->getAffaireDateCloture()) : '<small class="light">--</small>'; ?>
        <hr>
        <?= nl2br($affaire->getAffaireRemarque()); ?>
    </div>
    <div class = "col-12 col-md-6" style = "padding-right:5px;">

    </div>
</div>