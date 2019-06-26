<br>
<?= 'Solde en début de période : ' . $soldeBefore . ' heure(s)'; ?>
<br>
<br>
<table class="table table-bordered style1" cellspacing="0" cellpadding="2" style="font-size:9px; width: 540px; border: 1px solid black;">
    <tr style="background-color: #454a59; color: #FFF;">
        <td style="width: 80px;">Annee</td>
        <td style="width: 80px;">Semaine</td>
        <td style="width: 240px;">Période</td>
        <td style="width: 100px; text-align: right;">Delta</td>
    </tr>

    <?php
    if (!empty($heuresSupp)):
        foreach ($heuresSupp as $hs):
            ?>
            <tr nobr="true">
                <td style="border-bottom: 1px solid grey;">
                    <?= $hs->getHsAnnee(); ?>
                </td>
                <td style="border-bottom: 1px solid grey;">
                    <?= $hs->getHsSemaine(); ?>
                </td>
                <td style="border-bottom: 1px solid grey;">
                    <?php
                    $premierJour = $this->cal->premierJourFromNumSemaine($hs->getHsSemaine(), $hs->getHsAnnee());
                    echo $this->cal->dateFrancais($premierJour, 'jDmA') . ' au ' . $this->cal->dateFrancais(($premierJour + 86400 * 6), 'jdmA');
                    ?>
                </td>
                <td style="border-bottom: 1px solid grey; text-align: right;">
                    <?= $hs->getHsNbHeuresSupp(); ?>
                </td>
            </tr>
            <?php
        endforeach;
    endif;
    ?>
</table>
<br><br>
<?= 'Solde en fin de période : ' . $soldeAfter . ' heure(s)'; ?>
