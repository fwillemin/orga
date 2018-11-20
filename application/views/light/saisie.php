<div class="row fond">
    <div class="col-12" style="padding-left:20px;">
        <?=
        '<br><h4>' . $personnel->getPersonnelPrenom() . ' ' . $personnel->getPersonnelNom()
        . ' <small class="danger"><a href="' . site_url('light/baseRestrict') . '" style="color: inherit;"><i class="fas fa-sign-out-alt"></i></a></small>'
        . '</h4>';
        ?>
        <?php
        if ($personnel->getPersonnelMessage()):
            echo '<div class="alert alert-info">' . $personnel->getPersonnelMessage() . '</div>';
        endif;
        ?>
    </div>
</div>
<?php
for ($i = $dernierJourSaisie; $i >= $premierJourSaisie; $i -= 86400):
    ?>
    <div class="row fond">
        <div class="col-12" style="border-bottom: 1px solid grey; padding-left:20px;">
            <?= strtoupper($this->cal->dateFrancais($i)); ?>
        </div>
    </div>
    <div class="row fond">
        <div class="col-12" style="margin-bottom:15px; padding: 5px 20px;">
            <?php
            if (!empty($affectations)):
                foreach ($affectations as $affectation):

                    if ($i >= $affectation->getAffectationDebutDate() && $i <= $affectation->getAffectationFinDate()):
                        // Le jour est inclus dans l'affectation
                        $heureSaisie = null;
                        if ($affectation->getAffectationChantier()->getChantierEtat() == 1):
                            $classTrou = 'empty';
                        else:
                            $classTrou = 'locked';
                        endif;
                        if (!empty($affectation->getAffectationHeures())):
                            foreach ($affectation->getAffectationHeures() as $heure):
                                if ($heure->getHeureDate() == $i):
                                    $heureSaisie = $heure;
                                    if ($affectation->getAffectationChantier()->getChantierEtat() == 2):
                                        $classTrou = 'locked';
                                    elseif ($heure->getHeureValide() == 1):
                                        $classTrou = 'valide';
                                    else:
                                        $classTrou = 'unchecked';
                                    endif;
                                    continue;
                                endif;
                            endforeach;
                        endif;
                        ?>
                        <div class="tooltipOk cadreHeureLight"
                             style="color:<?= $affectation->getAffectationChantier()->getChantierCouleurSecondaire(); ?>; background-color:<?= $affectation->getAffectationChantier()->getChantierCouleur(); ?>"
                             data-placement="center" title="<?= $affectation->getAffectationChantier()->getChantierClient()->getClientNom() . ' : ' . $affectation->getAffectationChantier()->getChantierObjet(); ?>">
                            <div class="trou <?= $classTrou; ?>"></div>
                            <div style="font-size:25px;" data-affectationid="<?= $affectation->getAffectationId(); ?>" nbHeure="0" data-date="<?= $i; ?>" data-heureId="<?= $heureSaisie ? $heureSaisie->getHeureId() : 0; ?>">
                                <select class="heureSelect" <?= (($heureSaisie && $heureSaisie->getHeureValide() == 1) || $affectation->getAffectationChantier()->getChantierEtat() == 2) ? 'disabled' : ''; ?> >
                                    <?php
                                    for ($h = 0; $h <= 12; $h++):
                                        $m = 0;
                                        while ($m < 60):
                                            $duree = ($h * 60 + $m);
                                            echo '<option value="' . $duree . '"' . ($heureSaisie && $duree == $heureSaisie->getHeureDuree() ? 'selected' : '') . '>' . str_pad($h, 2, "0", STR_PAD_LEFT) . ':' . str_pad($m, 2, "0", STR_PAD_LEFT) . '</option>';
                                            $m += intval($this->session->userdata('parametres')['tranchePointage']);
                                        endwhile;
                                    endfor;
                                    ?>
                                </select>
                            </div>
                            <div>
                                <a href="#" style="color: <?= $affectation->getAffectationChantier()->getChantierCouleurSecondaire(); ?>;">
                                    <?= substr($affectation->getAffectationChantier()->getChantierClient()->getClientNom(), 0, 7); ?>
                                </a>
                            </div>
                        </div>
                        <?php
                    endif;

                endforeach;
            endif;
            ?>
        </div>
    </div>
<?php endfor;
?>
