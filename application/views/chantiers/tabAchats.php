<div class="row">
    <div class="col-6 col-lg-3" style="font-size:14px;">
        <br>
        Budget achats de ce chantier : <?= number_format($chantier->getChantierBudgetAchats(), 2, ',', ' ') . '€'; ?>
        <br>Budget prévu : <?= number_format($chantier->getChantierBudgetPrevu(), 2, ',', ' ') . '€'; ?>
        <br>Budget consommé : <?= number_format($chantier->getChantierBudgetConsomme(), 2, ',', ' ') . '€'; ?>
    </div>
    <div class="col-6 col-lg-9">
        <canvas id="graphChantierEtatAchats" width="400" height="40" js-budget="<?= $chantier->getChantierBudgetAchats(); ?>" js-prevu="<?= $chantier->getChantierBudgetPrevu(); ?>" js-consomme="<?= $chantier->getChantierBudgetConsomme(); ?>"></canvas>
    </div>
</div>
<hr>

<?php if ($this->ion_auth->in_group(55) && $chantier->getChantierEtat() == 1): ?>
    <button class = "btn btn-link" id = "btnAddAchat">
        <i class = "fas fa-edit"></i> Ajouter un achat
    </button>
<?php endif; ?>

<div id="containerAddAchat" class ="inPageForm col-md-12 col-lg-7" style = "padding:3px; <?= !empty($achat) ? '' : 'display: none;' ?>">
    <?php include('formAchat.php'); ?>
    <i class="formClose fas fa-times"></i>
</div>
<br>

<table class="table table-sm style1" id="tableAchats">
    <thead>
        <tr>
            <td rowspan="2" style="width: 120px;">Date</td>
            <td rowspan="2" style="width: 350px;">Description</td>
            <td colspan="3" style="border-left:1px solid lightgrey; text-align: center;">Prévisionnel</td>
            <td colspan="3" style="border-left:1px solid lightgrey; border-right:1px solid lightgrey; text-align: center;">Réel</td>
            <td rowspan="2" style="width: 50px;"></td>
            <td colspan="3" style="border-left:1px solid lightgrey; text-align: center;">Livraison</td>
        </tr>
        <tr>
            <td style="width:60px; border-left:1px solid lightgrey; text-align: right;">Qte</td>
            <td style="width:80px; text-align: right;">Prix</td>
            <td style="width:80px; text-align: right;">Total</td>
            <td style="width:60px; border-left:1px solid lightgrey; text-align: right;">Qte</td>
            <td style="width:80px; text-align: right;">Prix</td>
            <td style="width:80px; text-align: right;">Total</td>
            <td style="width:120px; border-left:1px solid lightgrey;">Fournisseur</td>
            <td style="width:80px;">Date</td>
            <td style="width:90px;">Avancement</td>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($chantier->getChantierAchats())):
            foreach ($chantier->getChantierAchats() as $a):
                if (!empty($achat) && $a->getAchatId() == $achat->getAchatId()):
                    $style = 'class="ligneClikable ligneSelectionnee"';
                elseif ($this->ion_auth->in_group(55)):
                    $style = 'class="ligneClikable"';
                else:
                    $style = '';
                endif;
                if ($a->getAchatTotal() > $a->getAchatTotalPrevisionnel()):
                    $statColor = 'red';
                else:
                    $statColor = 'green';
                endif;

                echo '<tr data-achatid="' . $a->getAchatId() . '"' . $style . '>'
                . '<td>' . $this->cal->dateFrancais($a->getAchatDate(), 'DmA') . '</td>'
                . '<td>' . $a->getAchatDescription() . '</td>'
                . '<td style="text-align: right; border-left: 1px solid black;">' . $a->getAchatQtePrevisionnel() . '</td>'
                . '<td style="text-align: right;">' . $a->getAchatPrixPrevisionnel() . '</td>'
                . '<td style="text-align: right;">' . $a->getAchatTotalPrevisionnel() . '</td>'
                . '<td style="text-align: right; border-left: 1px solid black;">' . $a->getAchatQte() . '</td>'
                . '<td style="text-align: right;">' . $a->getAchatPrix() . '</td>'
                . '<td style="text-align: right; border-right: 1px solid black;">' . $a->getAchatTotal() . '</td>'
                . '<td style="text-align: right; color:' . $statColor . ';">' . ($a->getAchatTotalPrevisionnel() > 0 ? floor($a->getAchatTotal() / $a->getAchatTotalPrevisionnel() * 100) : '-' ) . '%</td>'
                . '<td style="border-left: 1px solid black;">' . (!is_null($a->getAchatFournisseur()) ? $a->getAchatFournisseur()->getFournisseurNom() : '-') . '</td>'
                . '<td>' . $this->cal->dateFrancais($a->getAchatLivraisonDate(), 'Dma') . '</td>'
                . '<td style="border-right: 1px solid black;">' . $a->getAchatLivraisonAvancementText() . '</td>'
                . '</tr>';

            endforeach;
        endif;
        ?>
    </tbody>
</table>