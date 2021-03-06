<div class="row">
    <div class="col-3 col-sm-3" style="text-align:center; padding-left : 30px;">

        <?php
        //recherche des semaines et années pour le Next et le Previous
        if ($semaine > 1):
            $prevSemaine = $semaine - 1;
            $prevAnnee = $annee;
        else:
            $prevSemaine = date('W', mktime(0, 0, 0, 12, 28, $annee - 1));
            $prevAnnee = $annee - 1;
        endif;

        if (date('W', mktime(0, 0, 0, 12, 31, $annee - 1)) == 52):
            $derniereSemaineAnnee = 52;
        else:
            $derniereSemaineAnnee = 53;
        endif;
        if ($semaine < $derniereSemaineAnnee):
            $nextSemaine = $semaine + 1;
            $nextAnnee = $annee;
        else:
            $nextSemaine = 1;
            $nextAnnee = $annee + 1;
        endif;
        ?>
        <div style="position: fixed;">
            <br>
            <div class="btn-group">
                <a href="<?= site_url('pointages/heures/' . $prevSemaine . '/' . $prevAnnee); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-chevron-left"></i></a>
                <button class="btn btn-sm btn-dark"><?= 'Semaine ' . $semaine . ' - ' . $annee; ?></button>
                <a href="<?= site_url('pointages/heures/' . $nextSemaine . '/' . $nextAnnee); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-chevron-right"></i></a>
            </div>
            <br>
            <div class="col-12" id="datepicker-container" data-date="<?= date('Y-m-d', $premierJourSemaine); ?>" style="display: block; margin-top:5px;">
                <div data-cible="heures" data-date="<?= date('Y-m-d', $premierJourSemaine); ?>"></div>
            </div>
        </div>
    </div>

    <div class="col-9 col-sm-9" style="padding:24px;">

        <table class="table table-bordered style1" id="tableHeures">
            <thead>
            <th width="16%"><i class="fas fa-user-ninja"></i></th>
            <?php
            for ($i = 0; $i < 7; $i++):
                echo '<th width="12%" style="text-align:center;">' . $this->cal->dateFrancais(($premierJourSemaine + $i * 86400), 'jDma') . '</th>';
            endfor;
            ?>
            </thead>
            <tbody>

                <?php
                if (!empty($personnels)):
                    foreach ($personnels as $personnel):

                        echo '<tr>';
                        echo '<td><a href="' . site_url('personnels/fichePersonnel/' . $personnel->getPersonnelId()) . '">' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom() . '</a></td>';

                        $jour = 0; // jour de la case du tableau 0 = lundi, ...

                        for ($i = 1; $i < 8; $i++):

                            $jourEncours = $premierJourSemaine + ($i - 1) * 86400;

                            echo '<td>';

                            if (!empty($results[$personnel->getPersonnelId()]['indisponibilites'])):
                                foreach ($results[$personnel->getPersonnelId()]['indisponibilites'] as $indispo):

                                    if ($jourEncours >= $indispo->getIndispoDebutDate() && $jourEncours <= $indispo->getIndispoFinDate()):
                                        ?>
                                    <div class="indispo ombragelight text-center" style="position:relative; width:95%; font-weight: bold;">
                                        <?= $indispo->getIndispoMotif()->getMotifNom(); ?>
                                    </div>
                                    <?php
                                endif;

                            endforeach;
                        endif;


                        if (!empty($results[$personnel->getPersonnelId()]['affectations'])):
                            foreach ($results[$personnel->getPersonnelId()]['affectations'] as $affectation):
                                $isHeure = false;
                                unset($heure);

                                if ($jourEncours >= $affectation->getAffectationDebutDate() && $jourEncours <= $affectation->getAffectationFinDate()):

                                    if (!empty($affectation->getAffectationHeures())):
                                        foreach ($affectation->getAffectationHeures() as $heureTest):
                                            if ($heureTest->getHeureDate() == $jourEncours):
                                                $heure = $heureTest;
                                                continue;
                                            endif;
                                        endforeach;

                                        if (!empty($heure)):
                                            if ($heure->getHeureValide() == 1):
                                                $classHeure = 'valide';
                                            else:
                                                $classHeure = 'unchecked';
                                            endif;
                                        else:
                                            $classHeure = 'empty';
                                        endif;

                                    else:
                                        $classHeure = 'empty';
                                    endif;
                                    if ($affectation->getAffectationChantier()->getChantierEtat() == 2):
                                        $classHeure = 'locked';
                                    endif;
                                    ?>
                                    <div class="tooltipOk cadreHeure"
                                         style="color:<?= $affectation->getAffectationChantier()->getChantierCouleurSecondaire(); ?>; background-color:<?= $affectation->getAffectationChantier()->getChantierCouleur(); ?>"
                                         data-placement="center" title="<?= $affectation->getAffectationChantier()->getChantierClient()->getClientNom() . ' : ' . $affectation->getAffectationChantier()->getChantierObjet(); ?>">

                                        <div class="trou <?= $classHeure; ?>"></div>
                                        <div style="font-size:25px;" data-affectationid="<?= $affectation->getAffectationId(); ?>" nbHeure="0" data-date="<?= $jourEncours; ?>" data-heureId="<?= !empty($heure) ? $heure->getHeureId() : ''; ?>">
                                            <select class="heureSelect" <?= $classHeure == 'locked' ? 'disabled' : ''; ?> >
                                                <?php
                                                for ($h = 0; $h <= 12; $h++):
                                                    $m = 0;
                                                    while ($m < 60):
                                                        $duree = ($h * 60 + $m);
                                                        echo '<option value="' . $duree . '"' . (!empty($heure) && $duree == $heure->getHeureDuree() ? 'selected' : '') . '>' . str_pad($h, 2, "0", STR_PAD_LEFT) . ':' . str_pad($m, 2, "0", STR_PAD_LEFT) . '</option>';
                                                        $m += intval($this->session->userdata('parametres')['tranchePointage']);
                                                    endwhile;
                                                endfor;
                                                ?>
                                            </select>
                                        </div>
                                        <div>
                                            <a href = "<?php
                                            if ($affectation->getAffectationChantier()->getChantierAffaireId() != $this->session->userdata('affaireDiversId')):
                                                echo site_url('chantiers/ficheChantier/' . $affectation->getAffectationChantierId());
                                            else:
                                                echo '#';
                                            endif;
                                            ?>" style = "color: <?= $affectation->getAffectationChantier()->getChantierCouleurSecondaire(); ?>;"><?= substr($affectation->getAffectationChantier()->getChantierClient()->getClientNom(), 0, 7);
                                            ?></a>
                                        </div>
                                    </div>
                                    <?php
                                endif;

                            endforeach;
                        endif;



                        echo '</td>';
                    endfor;
                    echo '</tr>';
                endforeach;
            endif;
            ?>

            </tbody>

        </table>

    </div>
</div>

<!-- Bulle de saisie des heures qui apparait lors du click sur une affectation -->
<div id="quick_select" style="display: none; position:absolute; padding:5px; background-color: grey; border: 1px solid grey; border-radius:5px; z-index: 10;">
    <div class="form-group" style="width:120px;">
        <i class="glyphicon glyphicon-remove" id="close_select" style="color:#f8b3e7; position:absolute; top:38px; right:5px;"></i>
        <i class="glyphicon glyphicon-play" style="color:grey; position:absolute; top:12px; right:-10px;"></i>
        <div class="input-group col-lg-12 col-sm-12 col-xs-12">
            <select class="form-control input-sm" required id="selectNbHeure" date="" affectation="" heureId="" source="" >
                <?php for ($j = 0; $j < 13; $j++): ?>
                    <option value="<?= $j; ?>"><?= $j; ?></option>
                    <option value="<?= $j . '.5'; ?>"><?= $j . '.5'; ?></option>
                <?php endfor; ?>
            </select>
            <span class="input-group-addon"> <strong>H</strong></span>
        </div>
    </div>
</div>