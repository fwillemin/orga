<?php
$totalPanier = 0;
$totalZ1 = 0;
$totalZ2 = 0;
$totalZ3 = 0;
$totalZ4 = 0;
$totalZ5 = 0;
?>
<div class="row" id="formPointage">
    <div class="col-12" style="margin-bottom: 20px;">

        <div class="form-row">
            <div class="col-2 offset-1">
                <label for="pointagePersonnelId">Sélectionnez du personnel</label><br>
                <select name="pointagePersonnelId" id="pointagePersonnelId" class="selectpicker" data-width="100%" required title="Choisissez un personnel">
                    <?php
                    $actif = 1;
                    echo '<optgroup label="Personnels Actifs">';
                    if (!empty($personnels)):
                        foreach ($personnels as $perso):
                            $isSelect = '';
                            if ($perso->getPersonnelActif() == 0 && $actif == 1):
                                echo '</optgroup>';
                                echo '<optgroup label="Inactifs">';
                                $actif = 0;
                            endif;
                            if (!empty($personnel) && $perso->getPersonnelId() == $personnel->getPersonnelId()):
                                $isSelect = 'selected';
                            endif;
                            echo '<option ' . $isSelect . ' data-content="<span class=\'selectpickerPersonnel\'>' . $perso->getPersonnelNom() . ' ' . $perso->getPersonnelPrenom() . '</span>" value="' . $perso->getPersonnelId() . '">' . $perso->getPersonnelNom() . ' ' . $perso->getPersonnelPrenom() . '</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
            <div class="col-1">
                <label for="pointageMois">Mois de la période</label><br>
                <select name="pointageMois" id="pointageMois" class="form-control">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i; ?>" <?php if ($i == $mois) echo 'selected'; ?> ><?= $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-1">
                <label for="pointageAnnee">Annee de la période</label><br>
                <select name="pointageAnnee" id="pointageAnnee" class="form-control">
                    <?php for ($i = date('Y'); $i >= 2013; $i--): ?>
                        <option value="<?= $i; ?>" <?php if ($i == $annee) echo 'selected'; ?> ><?= $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-1">
                <label for="btnRechPeriode"></label><br>
                <button class="btn btn-dark" id="btnRechPeriode">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </div>
            <div class="col-6 col-sm-5" style="margin-top:20px; text-align: right;"  data-personnelid="<?= $this->uri->segment(3); ?>" data-periode="<?= $this->uri->segment(4); ?>">

                <button id="btnReleveSave" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Enregistrer la feuille
                </button>
                <?php if (!empty($sauvegarde)): ?>
                    <button class="btn btn-default btn-sm" id="btnReleveDel" data-pointageid="<?= $sauvegarde->getPointageId(); ?>">
                        <i class="fas fa-trash"></i> Supprimer et regénérer la feuille
                    </button>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 fond" style="padding: 0px 50px;">

        <?php if ($personnel): ?>

            <table id="enteteReleve">
                <tr>
                    <td width="250">
                        <span style="font-size:11px; font-weight: bold;">
                            <?= $etablissement->getEtablissementNom(); ?>
                        </span>
                        <span style="font-size:9px;">
                            <?= '<br>' . $etablissement->getEtablissementAdresse() . ', ' . $etablissement->getEtablissementCp() . ' ' . $etablissement->getEtablissementVille() . '<br>Tel : ' . $etablissement->getEtablissementTelephone() . ' - Email : ' . $etablissement->getEtablissementEmail(); ?>
                        </span>
                    </td>
                    <td width="500" align="center">
                        <h4>Fiche de pointage <b><?= $this->cal->dateFrancais(mktime(0, 0, 0, $mois, 1, $annee), 'mA'); ?></b></h4>
                    </td>
                    <td width="250">
                        <span style="font-size:12px;">
                            Salarié : <?= $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom(); ?><br>
                            Interlocuteur : <?= $etablissement->getEtablissementContact(); ?>
                        </span>
                    </td>
                </tr>
            </table>

            <?php if (empty($sauvegarde)): ?>
                <table id="tablePointages" border="1">
                    <tr>
                        <td rowspan="2">Jour</td>
                        <td colspan="2" rowspan="2" width="20%;" style="text-align: center;">Heures</td>
                        <td rowspan="2" style="text-align: center;">Panier</td>
                        <td rowspan="2" style="text-align: center;">Formation</td>
                        <td rowspan="2" style="text-align: center;">Chantiers</td>
                        <td colspan="5" style="text-align: center;">ZI trajet</td>
                        <td rowspan="2" style="text-align: center;">ZI transport</td>
                        <td rowspan="2" style="text-align: center; width: 60px;">Recap<br>hebdo</td>
                        <td rowspan="2" style="text-align: center;" width="15%">Observations</td>
                    </tr>
                    <tr>
                        <td align="center">1</td>
                        <td align="center">2</td>
                        <td align="center">3</td>
                        <td align="center">4</td>
                        <td align="center">5</td>
                    </tr>

                    <?php
                    $semaine = null;
                    $totalHebdo = 0; /* En minutes */
                    $totalMois = 0; /* En minutes */

                    $nbJourDansLeMois = date('t', mktime(0, 0, 0, $mois, 1, $annee));
                    for ($i = 1; $i <= $nbJourDansLeMois; $i++):
                        /* Initialisation Var Jour */
                        $timeJour = mktime(0, 0, 0, $mois, $i, $annee);
                        //$timeIndispo = mktime(0, 0, 0, $mois, $i, $annee);

                        if (date('w', $timeJour) == 1):
                            $totalHebdo = 0; /* on repasse le total hebdo à 0 le lundi */
                        endif;
                        if (date('w', $timeJour) == 0 || date('w', $timeJour) == 6):
                            $isWe = true;
                        else:
                            $isWe = false;
                        endif;
                        $nbMinutesJour = 0;
                        $chantiersJour = '';
                        $nbChantiersJour = 0;
                        $zone = '';
                        $distanceVolOiseauMax = 0;
                        /* Analyse des donnees */
                        /* Si l'on fonctionne à la grille horaire */
                        if ($personnel->getPersonnelPointages() == 2):
                            $totalHebdo += $personnel->getPersonnelHoraire()->getHoraireMinutes(date('w', $timeJour));
                            $totalMois += $personnel->getPersonnelHoraire()->getHoraireMinutes(date('w', $timeJour));
                        endif;
                        if (!empty($heures)):
                            foreach ($heures as $h):
                                if (mktime(0, 0, 0, date('m', $h->getHeureDate()), date('d', $h->getHeureDate()), date('Y', $h->getHeureDate())) != $timeJour): continue;
                                else:
                                    $nbMinutesJour += $h->getHeureDuree();
                                    $nbChantiersJour++;
                                    if ($chantiersJour != '')
                                        $chantiersJour .= ', ';
                                    $chantiersJour .= ($h->getHeureAffectation()->getAffectationPlace() ? $h->getHeureAffectation()->getAffectationPlace()->getPlaceVille() : 'NR');

                                    if ($personnel->getPersonnelPointages() == 1):
                                        $totalHebdo += $h->getHeureDuree(); /* en minutes */
                                        $totalMois += $h->getHeureDuree(); /* en minutes */
                                    endif;
                                    /* calcul de la zone */
                                    if ($h->getHeureAffectation()->getAffectationPlace() && $h->getHeureAffectation()->getAffectationPlace()->getPlaceVolOiseau() > $distanceVolOiseauMax):
                                        $distanceVolOiseauMax = $h->getHeureAffectation()->getAffectationPlace()->getPlaceVolOiseau();
                                    endif;
                                endif;
                            endforeach;
                        endif;
                        /* on recherche la zone de déplacement de cette journée */
                        if ($distanceVolOiseauMax > 0):
                            if (floor($distanceVolOiseauMax / 1000) >= 40): $totalZ5++;
                                $zone = 5;
                            elseif (floor($distanceVolOiseauMax / 1000) >= 30): $totalZ4++;
                                $zone = 4;
                            elseif (floor($distanceVolOiseauMax / 1000) >= 20): $totalZ3++;
                                $zone = 3;
                            elseif (floor($distanceVolOiseauMax / 1000) >= 10): $totalZ2++;
                                $zone = 2;
                            else: $totalZ1++;
                                $zone = 1;
                            endif;
                        endif;

                        /* Analyse des indisponibilités */
                        $absMatin = false;
                        $motifMatin = '';
                        $absAprem = false;
                        $motifAprem = '';
                        $isFormation = false; /* il y a un formation ce jour */
                        if (!empty($indisponibilites)):
                            foreach ($indisponibilites as $indispo):

                                if ($timeJour >= $indispo->getIndispoDebutDate() && $timeJour <= $indispo->getIndispoFinDate()):
                                    if ($indispo->getIndispoMotifId() == 14):
                                        $isFormation = true;
                                    endif;
                                    if ($timeJour == $indispo->getIndispoDebutDate() && $indispo->getIndispoDebutMoment() == 2):
                                        $absAprem = true;
                                        if ($personnel->getPersonnelPointages() == 2): /* on retire les heures d'absence des compteurs hebdo et mensuels si on travaille avec une grille horaire */
                                            $totalHebdo -= $personnel->getPersonnelHoraire()->getHoraireMinutes(date('w', $timeJour), 2);
                                            $totalMois -= $personnel->getPersonnelHoraire()->getHoraireMinutes(date('w', $timeJour), 2);
                                        endif;
                                        $motifAprem = $indispo->getIndispoMotif()->getMotifNom(); /* Après-midi */
                                        continue;
                                    endif;
                                    if ($timeJour == $indispo->getIndispoFinDate() && $indispo->getIndispoFinMoment() == 1):
                                        $absMatin = true;
                                        if ($personnel->getPersonnelPointages() == 2): /* on retire les heures d'absence des compteurs hebdo et mensuels */
                                            $totalHebdo -= $personnel->getPersonnelHoraire()->getHoraireMinutes(date('w', $timeJour), 1);
                                            $totalMois -= $personnel->getPersonnelHoraire()->getHoraireMinutes(date('w', $timeJour), 1);
                                        endif;
                                        $motifMatin = $indispo->getIndispoMotif()->getMotifNom(); /* Après-midi */
                                        continue;
                                    endif;
                                    $absMatin = $absAprem = true;
                                    $motifMatin = $motifAprem = $indispo->getIndispoMotif()->getMotifNom();
                                    if ($personnel->getPersonnelPointages() == 2): /* on retire les heures d'absence des compteurs hebdo et mensuels */
                                        $totalHebdo -= $personnel->getPersonnelHoraire()->getHoraireMinutes(date('w', $timeJour), 3);
                                        $totalMois -= $personnel->getPersonnelHoraire()->getHoraireMinutes(date('w', $timeJour), 3);
                                    endif;
                                endif;
                            endforeach;
                        endif;
                        if (!$absAprem && !$absMatin && $nbMinutesJour > 0):
                            $totalPanier++;
                        endif;
                        ?>
                        <tr>
                            <td width="5" style="text-align: center; <?= $isWe ? 'background-color: black; color: white;' : ''; ?>" >
                                <?= $i; ?>
                            </td>
                            <?php
                            if ($personnel->getPersonnelPointages() == 2):
                                $baseJour = trim('getHoraire' . $this->cal->dateFrancais($timeJour, 'j'));
                                ?>
                                <td width="100">
                                    <input type="text" value="<?php
                                    if ($absMatin == false):
                                        $baseJour1 = $baseJour . '1';
                                        $baseJour2 = $baseJour . '2';
                                        if ($personnel->getPersonnelHoraire()->$baseJour1() != '00:00:00'):
                                            echo substr($personnel->getPersonnelHoraire()->$baseJour1(), 0, 5) . '-' . substr($personnel->getPersonnelHoraire()->$baseJour2(), 0, 5);
                                        endif;
                                    else:
                                        echo $motifMatin;
                                    endif;
                                    ?>" class="inputReleve" >
                                </td>
                                <td width="100">
                                    <input type="text" class="inputReleve" value="<?php
                                    if ($absAprem == false):
                                        $baseJour3 = $baseJour . '3';
                                        $baseJour4 = $baseJour . '4';
                                        if ($personnel->getPersonnelHoraire()->$baseJour3() != '00:00:00'):
                                            echo substr($personnel->getPersonnelHoraire()->$baseJour3(), 0, 5) . '-' . substr($personnel->getPersonnelHoraire()->$baseJour4(), 0, 5);
                                        endif;
                                    else:
                                        echo $motifAprem;
                                    endif;
                                    ?>" >
                                </td>
                            <?php else:
                                ?>
                                <td colspan="2" width="200">
                                    <input type="text" class="inputReleve" value="<?php
                                    if ($nbMinutesJour > 0):
                                        echo 'Pointages chantier : ' . round($nbMinutesJour / 60, 2) . ' H, ';
                                    endif;
                                    if ($absAprem || $absMatin):
                                        if ($motifMatin == $motifAprem && $motifMatin != ''):
                                            echo $motifMatin;
                                        else:
                                            if ($absMatin == true):
                                                echo ' 1/2 journée ' . $motifMatin . ',';
                                            endif;
                                            if ($absAprem == true):
                                                echo ' 1/2 journée ' . $motifAprem . ',';
                                            endif;
                                        endif;
                                    endif;
                                    ?>" >
                                </td>
                            <?php endif; ?>
                            <td width="50"><input type="text" class="inputReleve inputCenter" value="<?= ($this->session->userdata('parametres')['genererPaniers'] == 1 && !$absAprem && !$absMatin && $nbMinutesJour > 0) ? '1' : ''; ?>" ></td>
                            <td width="50"><input class="inputReleve inputCenter" type="text" value="<?= $isFormation ? 'X' : ''; ?>" ></td>
                            <td width="300">
                                <input class="inputReleve" type="text" value="<?= $chantiersJour; ?>" >
                            </td>
                            <td width="20">
                                <input type="text" class="inputReleve inputCenter" value="<?php if ($zone == 1) echo '1'; ?>" >
                            </td>
                            <td width="20">
                                <input type="text" class="inputReleve inputCenter" value="<?php if ($zone == 2) echo '1'; ?>" >
                            </td>
                            <td width="20">
                                <input type="text" class="inputReleve inputCenter" value="<?php if ($zone == 3) echo '1'; ?>" >
                            </td>
                            <td width="20">
                                <input type="text" class="inputReleve inputCenter" value="<?php if ($zone == 4) echo '1'; ?>" >
                            </td>
                            <td width="20">
                                <input type="text" class="inputReleve inputCenter" value="<?php if ($zone == 5) echo '1'; ?>" >
                            </td>
                            <td width="60">
                                <input type="text" class="inputReleve inputCenter" value="" >
                            </td>
                            <td width="50">
                                <input type="text" class="inputReleve inputCenter" value="<?php if (date('w', $timeJour) == 0 || $i == $nbJourDansLeMois) echo floor($totalHebdo / 60) . 'h' . ($totalHebdo % 60) . 'min'; ?>" >
                            </td>
                            <td width="100">
                                <input type="text" class="inputReleve" value="" >
                            </td>

                        </tr>
                    <?php endfor; ?>
                    <tr>
                        <td colspan="3" style="border:none;"></td>
                        <td style="text-align: center;"><input class="inputCenter inputReleve" value="<?= $totalPanier; ?>" ></td>
                        <td colspan="2" style="border:none;"></td>
                        <td>
                            <input type="text" class="inputReleve inputCenter" id="totalZ1" style="text-align: center;" value="<?= $totalZ1; ?>" >
                        </td>
                        <td>
                            <input type="text" class="inputReleve inputCenter" id="totalZ2" style="text-align: center;" value="<?= $totalZ2; ?>" >
                        </td>
                        <td>
                            <input type="text" class="inputReleve inputCenter" id="totalZ3" style="text-align: center;" value="<?= $totalZ3; ?>" >
                        </td>
                        <td>
                            <input type="text" class="inputReleve inputCenter" id="totalZ4" style="text-align: center;" value="<?= $totalZ4; ?>" >
                        </td>
                        <td>
                            <input type="text" class="inputReleve inputCenter" id="totalZ5" style="text-align: center;" value="<?= $totalZ5; ?>" >
                        </td>
                        <td><input class="inputReleve inputCenter" value="" ></td>
                        <td><input class="inputReleve inputCenter" value="<?= floor($totalMois / 60) . 'h' . ($totalMois % 60) . 'min'; ?>" ></td>
                        <td style="border:none;"></td>
                    </tr>
                    <tr style="border:none;">
                        <td colspan="5" style="border:none;"><span style="font-size:11px;">Prime Exceptionnelle : </span><input class="inputReleve" style="font-size:11px; width:200px;" value="" ></td>
                        <td colspan="3" rowspan="2" style="border:none; text-align: center;">Date et Signature du salarié </td>
                        <td colspan="6" rowspan="2" style="border:none;" align="right">Date et Signature  et cachet <?= $etablissement->getEtablissementNom(); ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="border:none;"><span style="font-size:11px;">Acompte salarié : </span><input class="inputReleve" style="font-size:11px; width:200px;" value="" ></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="border:none;">
                            <span style="font-size:11px;">
                                Observations :
                            </span>
                            <input class="inputReleve" style="font-size:12px; width:350px;" ><br>
                            <input class="inputReleve" style="font-size:12px; width:350px;" >
                        </td>
                    </tr>
                </table>
                <?php
            else:
                echo $sauvegarde->getPointageHTML();
            endif;
        else:
            echo 'Veuillez sélectionner un personnel et une période';
        endif;
        ?>
    </div>
</div>
