<div class="row" style="margin-top: 15px;">
    <div class="col-5">
        <?php if ($chantier->getChantierEtat() == 1): ?>
            <button class="btn btn-sm btn-info" id="btnClotureChantier" <?= $this->ion_auth->in_group(array(54)) ? '' : 'disabled'; ?> >
                <i class="fas fa-lock"></i> Clôturer ce chantier
            </button>
            <?php
        else:
            echo '<h5>Chantier clôturé le ' . $this->cal->dateFrancais($chantier->getChantierDateCloture()) . '</h5>';
            echo '<button data-chantierid="' . $chantier->getChantierId() . '" data-affaireid="' . $chantier->getChantierAffaireId() . '" class="btn btn-sm btn-warning" id="btnReouvertureChantier" ' . ($this->ion_auth->in_group(array(54)) ? '' : 'disabled') . '>'
            . '<i class="fas fa-key"></i> Réouvrir ce chantier'
            . '</button>';
        endif;
        ?>

        <br>
        <br>
        <span style="font-size:13px;">
            Catégorie : <?= $chantier->getChantierCategorie(); ?>
            <br>Chiffrage : <?= number_format($chantier->getChantierPrix(), 2, ',', ' ') . '€ HT'; ?>
        </span>
        <br>
        <br>
        <h5>Livraisons</h5>
        <table class="table table-sm style1" id="tableResumeAchats" data-chantierid="<?= $chantier->getChantierId(); ?>">
            <thead>
                <tr>
                    <th>Achat</th>
                    <th>Fournisseur</th>
                    <th width="120">Date livraison</th>
                    <th width="100">Etat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $isLivraison = false;
                if (!empty($chantier->getChantierAchats())):
                    foreach ($chantier->getChantierAchats() as $achatResume):
                        if ($achatResume->getAchatLivraisonDate()):
                            echo '<tr class="ligneClikable" data-achatid="' . $achatResume->getAchatId() . '"><td>' . $achatResume->getAchatDescription() . '</td><td>' . ($achatResume->getAchatFournisseur() ? $achatResume->getAchatFournisseur()->getFournisseurNom() : '-') . '</td><td>' . $this->cal->dateFrancais($achatResume->getAchatLivraisonDate(), 'jDma') . '</td><td>' . $achatResume->getAchatLivraisonAvancementText() . '</td></tr>';
                            $isLivraison = true;
                        endif;
                    endforeach;
                endif;
                if ($isLivraison === false):
                    echo '<tr><td colspan="4">Aucune livraison attendue pour ce chantier</td></tr>';
                endif;
                ?>
            </tbody>
        </table>

    </div>
    <div class="col-7">
        <?php
        $dataHeures = $analyse['mainO']['commercial'] . ',' . $analyse['mainO']['tempsReel'];
        $dataAchats = $chantier->getChantierBudgetAchats() . ',' . $chantier->getChantierBudgetConsomme();
        $dataMarge = $analyse['marge']['commerciale'] . ',' . $analyse['marge']['tempsReel'];
        $dataFG = $analyse['fraisGeneraux'] . ',' . $analyse['fraisGeneraux'];
        if ($chantier->getChantierEtat() == 1):
            $dataHeures .= ',' . $analyse['mainO']['finChantier'];
            $dataAchats .= ',' . $analyse['achats']['finChantier'];
            $dataMarge .= ',' . $analyse['marge']['finChantier'];
            $dataFG .= ',' . $analyse['fraisGeneraux'];
            $dataLabels = "Commercial, Temps réel, Fin chantier";
        else:
            $dataLabels = "Commercial, Bilan";
        endif;
        ?>
        <canvas id = "graphChantierResume" width ="300" height = "200"
                js-dataheures = "<?= $dataHeures; ?>"
                js-dataachats ="<?= $dataAchats; ?>"
                js-datamarge ="<?= $dataMarge; ?>"
                js-dataFG ="<?= $dataFG; ?>"
                js-chiffrage="<?= $chantier->getChantierPrix(); ?>",
                js-datalabels="<?= $dataLabels; ?>"
                >
        </canvas>

    </div>
</div>