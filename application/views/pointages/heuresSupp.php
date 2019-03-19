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
        </div>
    </div>

    <div class="col-12 col-xl-7" style="padding:24px;">

        <table class="table table-bordered style1" id="tableHeuresSupp">
            <thead>
            <th width="20%"><i class="fas fa-user-ninja"></i></th>
            <th width="20%">Heures pointées</th>
            <th width="15%">Heures RTT prises</th>
            <th width="15%">Heures Supp.</th>
            <th width="30%">Action</th>
            </thead>
            <tbody>

                <?php
                if (!empty($personnels)):
                    foreach ($personnels as $personnel):

                        $heuresSuppCalculees = $results[$personnel->getPersonnelId()]['rtt'] + $results[$personnel->getPersonnelId()]['nbHeuresPointees'] - $this->session->userdata('parametres')['limiteHeuresSupp'];

                        echo '<tr>';
                        echo '<td><a href="' . site_url('personnels/fichePersonnel/' . $personnel->getPersonnelId()) . '">' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom() . '</a></td>';
                        echo '<td class="text-center" style="font-size:20px;">' . round($results[$personnel->getPersonnelId()]['nbHeuresPointees'], 2) . '</td>';
                        echo '<td class="text-center" style="font-size:20px;">' . $results[$personnel->getPersonnelId()]['rtt'] . '</td>';
                        echo '<td><input type="text" class="form-control form-control-sm text-right" value="' . $heuresSuppCalculees . '"></td>';
                        echo '<td class="text-center">' . '<button class="btn btn-sm btn-default hsOK">Prises en compte</button><button class="btn btn-sm btn-default hsIgnored" style="margin-left:5px;">Ignorées</button>' . '</td>';
                        echo '</tr>';

                    endforeach;
                endif;
                ?>

            </tbody>

        </table>

    </div>
</div>
