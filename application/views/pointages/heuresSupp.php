<div class="row fond">
    <div class="col-12 col-xl-3 offset-xl-1" style="text-align:center; padding-left : 30px;">

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
        <div style="position: relative; text-align: center;">
            <br>
            <div class="btn-group">
                <a href="<?= site_url('pointages/heuresSupp/' . $prevSemaine . '/' . $prevAnnee); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-chevron-left"></i></a>
                <button class="btn btn-sm btn-dark"><?= 'Semaine ' . $semaine . ' - ' . $annee; ?></button>
                <a href="<?= site_url('pointages/heuresSupp/' . $nextSemaine . '/' . $nextAnnee); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-chevron-right"></i></a>
            </div>
            <br>
            <div class="col-12" id="datepicker-container" data-date="<?= date('Y-m-d', $premierJourSemaine); ?>" style="display: block; margin-top:5px;">
                <div data-cible="heuresSupp" data-date="<?= date('Y-m-d', $premierJourSemaine); ?>"></div>
            </div>
            <a href="<?= site_url('planning/base/' . date('Y-m-d', $premierJourSemaine)); ?>" target="_blank" class="btn btn-light btn-sm">
                <i class="fas fa-calendar"></i> Voir le planning
            </a>
            <a href="<?= site_url('pointages/heures/' . date('W/Y', $premierJourSemaine)); ?>" target="_blank" class="btn btn-light btn-sm">
                <i class="fas fa-eye"></i> Voir les pointages
            </a>
        </div>
    </div>

    <div class="col-12 col-xl-8" style="padding:24px;">
        <?= 'Base hebdomadaire : <b>' . $this->session->userdata('parametres')['limiteHeuresSupp'] . ' heures</b><br>'; ?>
        <table class="table table-bordered style1" id="tableHeuresSupp" data-semaine="<?= $semaine; ?>" data-annee="<?= $annee; ?>">
            <thead>
            <th width="25%">
                <i class="fas fa-user-ninja"></i>
            </th>
            <th width="30%">Heures pointées</th>
            <th width="15%">Heures RTT prises</th>
            <th width="15%">Delta</th>
            <th width="15%">Action</th>
            </thead>
            <tbody>

                <?php
                if (!empty($personnels)):
                    foreach ($personnels as $personnel):

                        /**
                         * Info légales:
                         * Définition heures supp : Heures effectuées au-delà de 35 heures dès lors qu’il ne s’agit pas d’heures récupérées. Les heures
                         * récupérées ne constituent pas des heures supplémentaires même si elles excèdent 35 heures.
                         * Editions-tissot : Page 471
                         */
                        $heuresSuppCalculees = $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'][7] - $this->session->userdata('parametres')['limiteHeuresSupp'];
                        if ($heuresSuppCalculees < 0):
                            $heuresSuppCalculees = 0;
                        endif;
                        $heuresSuppCalculees -= $results[$personnel->getPersonnelId()]['rtt'];

                        if ($results[$personnel->getPersonnelId()]['heuresSuppSaved']):
                            $buttonHsOK = 'btn-success';
                            $buttonHsIgnored = 'btn-default';
                        else:
                            $buttonHsOK = 'btn-default';
                            $buttonHsIgnored = 'btn-danger';
                        endif;

                        echo '<tr data-personnelid="' . $personnel->getPersonnelId() . '">';
                        echo '<td><a href="' . site_url('personnels/fichePersonnel/' . $personnel->getPersonnelId()) . '">' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom() . '</a></td>';
                        echo '<td class="text-center" style="font-size:20px; padding-top:1px; ' . ($heuresSuppCalculees > 0 ? 'color:orangered;' : '') . '">' . $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'][7]
                        . '<div class="row quadrillageHeures" style="font-size:11px; color:black; margin:0px 1px;">'
                        . '<div class="col-2">' . $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'][1] . '</div>'
                        . '<div class="col-2">' . $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'][2] . '</div>'
                        . '<div class="col-2">' . $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'][3] . '</div>'
                        . '<div class="col-2">' . $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'][4] . '</div>'
                        . '<div class="col-2">' . $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'][5] . '</div>'
                        . '<div class="col-1">' . $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'][6] . '</div>'
                        . '<div class="col-1">' . $results[$personnel->getPersonnelId()]['nbHeuresPointeesJours'][0] . '</div>'
                        . '</div></td>';
                        echo '<td class="text-center" style="font-size:20px;">' . $results[$personnel->getPersonnelId()]['rtt'] . '</td>';
                        echo '<td><input type="text" class="form-control form-control-sm text-right" value="' . ($results[$personnel->getPersonnelId()]['heuresSuppSaved'] ? $results[$personnel->getPersonnelId()]['heuresSuppSaved']->getHsNbHeuresSupp() : $heuresSuppCalculees) . '"></td>';
                        echo '<td class="text-center">'
                        . '<button class="btn btn-sm ' . $buttonHsOK . ' hsOK"><i class="fas fa-check-circle"></i></button>'
                        . '<button class="btn btn-sm ' . $buttonHsIgnored . ' hsIgnored" style="margin-left:5px;">Ignorées</button>'
                        . '</td>';
                        echo '</tr>';

                    endforeach;
                endif;
                ?>

            </tbody>

        </table>

    </div>
</div>
