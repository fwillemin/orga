<h2>Détail des heures de la semaine <?= $semaine . ' de ' . $annee; ?></h2>
Impression le <?= date('d/m/Y') . ' à ' . date('H:i'); ?><br>
<br>
<hr>

<?php
//print_r($dossiers);
?>


<table style="font-size:11px;">

    <?php
    if (!empty($dossiers)):
        foreach ($dossiers as $d):
            echo '<tr><td colspan="2" style="border-top: 1px solid grey;">Dossier : <strong>' . $d->getClient() . '</strong></td></tr>';
            foreach ($d->getDossierChantiers() as $c):
                echo '<tr><td width="20px">...</td>'
                . '<td colspan="2">' . $c->getObjet() . '</td></tr>';
                echo '<tr><td>...</td><td width="380">'
                . '<table border="1" cellpadding="2">';
                $sstotal = 0;
                if (!empty($c->getChantierHeures())):
                    foreach ($c->getChantierHeures() as $h):
                        $sstotal += $h->getNb_heure();
                        echo '<tr><td width="40">' . date('d-m', $h->getDate()) . '</td><td width="110">' . $h->getHeurePersonnel()->getNom() . ' ' . substr($h->getHeurePersonnel()->getPrenom(), 0, 2) . '.</td>'
                        . '<td width="30" align="right">' . $h->getNb_heure() . '</td>'
                        . '<td width="190">' . $h->getHeureAffectationObjet() . '</td>'
                        . '<td width = "150"></td></tr>';
                    endforeach;
                    echo '<tr style = "background-color: lightgrey;"><td colspan = "2" align = "right">Total du dossier</td><td align = "right">' . $sstotal . '</td><td></td><td></td></tr>';
                else:
                    echo 'Aucune heure saisie';
                endif;

                echo '</table><br>'
                . '</td></tr>';
            endforeach;
        endforeach;
    endif;
    ?>

</table>
