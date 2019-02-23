<div class="row" style="margin-top: 15px;">
    <div class="col-12 col-sm-4">
        <div class="row">
            <div class="col-12 col-sm-5">
                <span style="font-size:13px;">
                    Catégorie : <?= $chantier->getChantierCategorie(); ?>
                    <br>Chiffrage : <?= number_format($chantier->getChantierPrix(), 2, ',', ' ') . '€ HT'; ?>
                </span>
            </div>
            <div class="col-12 col-sm-7 text-center">
                <?php if ($chantier->getChantierEtat() == 1): ?>
                    <button class="btn btn-sm btn-info" id="btnClotureChantier" <?= $this->ion_auth->in_group(array(54)) ? '' : 'disabled'; ?> >
                        <i class="fas fa-lock"></i> Clôturer ce chantier
                    </button>
                    <?php
                else:
                    echo '<span style="font-size:15px;">Clôturé le ' . $this->cal->dateFrancais($chantier->getChantierDateCloture()) . '</span>';
                    echo '<button data-chantierid="' . $chantier->getChantierId() . '" data-affaireid="' . $chantier->getChantierAffaireId() . '" class="btn btn-sm btn-warning" id="btnReouvertureChantier" ' . ($this->ion_auth->in_group(array(54)) ? '' : 'disabled') . '>'
                    . '<i class="fas fa-key"></i> Réouvrir ce chantier'
                    . '</button>';
                endif;
                ?>
            </div>
            <div class="col-12" style="padding-top:18px;">
                <?php if ($chantier->getChantierEtat() == 1): ?>
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
                    <?php
                endif;
                ?>
            </div>
        </div>

    </div>
    <div class="col-12 col-sm-4">
        <?php
        if (!empty($chantier->getChantierPerformancesPersonnels())):
            foreach ($chantier->getChantierPerformancesPersonnels() as $performance):

                if (isset($dataPersonnel)):
                    $dataPersonnel .= ', ' . $performance->getPerformancePersonnel()->getPersonnelNom() . ' ' . substr($performance->getPerformancePersonnel()->getPersonnelPrenom(), 0, 1);
                else:
                    $dataPersonnel = $performance->getPerformancePersonnel()->getPersonnelNom() . ' ' . substr($performance->getPerformancePersonnel()->getPersonnelPrenom(), 0, 1);
                endif;

                if (isset($dataParticipation)):
                    $dataParticipation .= ', ' . $performance->getPerformanceHeuresPointees();
                else:
                    $dataParticipation = $performance->getPerformanceHeuresPointees();
                endif;

            endforeach;
            ?>
            <canvas id = "graphChantierParticipations" width ="300" height = "200"
                    js-datapersonnels = "<?= $dataPersonnel; ?>"
                    js-dataparticipation ="<?= $dataParticipation; ?>"
                    >
            </canvas>
        <?php endif; ?>
    </div>
    <div class="col-12 col-sm-4">
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