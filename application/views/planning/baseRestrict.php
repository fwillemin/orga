<div class="container-fluid">
    <div class="row" style="margin-top : 15px;">
        <input type="hidden" id="caseHeight" value="<?= $this->hauteur; ?>">
        <input type="hidden" id="caseWidth" value="<?= $this->largeur; ?>">

        <div class="col-12">
            <div class="row">
                <!-- liste du personnel -->
                <div class="col-3" align="right">
                    <table id="tablePlanningPersonnel">
                        <tr height="43">
                            <td></td>
                        </tr>
                        <?php
                        foreach ($personnelsPlanning as $personnel):
                            if ($this->ion_auth->in_group(array(25))):
                                $link = site_url('personnels/fichePersonnel/' . $personnel->getPersonnelId());
                            else:
                                $link = '#';
                            endif;
                            ?>
                            <tr height="<?= $this->hauteur; ?>">
                                <td align="right" style="font-size:10px;">
                                    <span class="<?= $personnel->getPersonnelActif() == 1 ? 'badge-light' : 'badge-secondary'; ?> connectPersonnel" data-personnelnom="<?= $personnel->getPersonnelPrenom() . ' ' . $personnel->getPersonnelNom(); ?>" data-personnelid="<?= $personnel->getPersonnelId(); ?>">
                                        <?= substr($personnel->getPersonnelPrenom(), 0, 1) . '. ' . substr($personnel->getPersonnelNom(), 0, 7) . ' <i class="fas fa-play" style=""></i>'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr height="50">
                            <td align="right">
                                Livraisons
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-9" id="divPlanning" today="<?= $today; ?>" style=" padding-left: 0px;">
                    <div id="masquePlanning" style="width: <?= ($nbSemainesPlanning * 7 * ((($this->largeur + 1) * 2))) . 'px'; ?>;"></div>

                    <table cellspacing="0" border="0" id="tablePlanning" style="width: <?= ($nbSemainesPlanning * 7 * ((($this->largeur + 1) * 2))) . 'px'; ?>;">
                        <!-- semaines -->
                        <tr>
                            <?php
                            // génération des semaines du planning
                            $currentDate = $premierJourPlanning - 86400 * 7;
                            $heureEte = date('I', $premierJourPlanning); /* indique si le premier jour du planning est été ou hiver */
                            for ($i = 0; $i < $nbSemainesPlanning; $i++):
                                $currentDate += 86400 * 7;
                                /* Gestion des passages en heure été et heure hiver */
                                if (date('I', $currentDate) != $heureEte):
                                    if ($heureEte == 1):
                                        /* on ajoute une heure */
                                        $currentDate += 3600;
                                    else:
                                        /* on retire une heure */
                                        $currentDate -= 3600;
                                    endif;
                                    $heureEte = date('I', $currentDate);
                                endif;
                                ?>
                                <td class="cellSemaines" colspan="14" align="center" style="width: <?= 14 * ($this->largeur + 1.5); ?>px;">
                                    <?= $this->cal->dateFrancais($currentDate, 'Ma') . ' | Semaine ' . date('W', $currentDate); ?>
                                </td>
                                <?php
                            endfor;
                            unset($currentDate);
                            ?>
                        </tr>

                        <!-- jours -->
                        <tr>
                            <?php
                            /**
                             * array avec tous les jours au format timestamp corrigés avecv l'heure d'été
                             */
                            $listeDate = array();
                            $currentDate = $premierJourPlanning - 86400;
                            $heureEte = date('I', $premierJourPlanning); /* indique si le premier jour du planning est été ou hiver */
                            for ($i = 0; $i < $nbSemainesPlanning * 7; $i++):
                                $currentDate += 86400;
                                $listeDate[$i] = $currentDate;
                                /* Gestion des passages en heure été et heure hiver */
                                if (date('I', $currentDate) != $heureEte):
                                    if ($heureEte == 1):
                                        /* on ajoute une heure */
                                        $currentDate += 3600;
                                    else:
                                        /* on retire une heure */
                                        $currentDate -= 3600;
                                    endif;
                                    $heureEte = date('I', $currentDate);
                                endif;
                                echo '<td class="' . (date('dmy', $currentDate) == date('dmy') ? 'cellAujourdhui' : 'cellJours') . '" data-jour="' . date('Y-m-d', $currentDate) . '" colspan="2" style="width:' . (2 * ($this->largeur + 1.5)) . 'px;">' . date('d', $currentDate) . '</td>';
                            endfor;
                            ?>
                        </tr>


                        <!-- Personnels -->
                        <?php
                        $personnelListe = array(); /* Liste qui va contenir tous les ids des personnels affichés */
                        foreach ($personnelsPlanning as $personnel):
                            $personnelListe[] = $personnel->getPersonnelId();
                            ?>
                            <tr height="<?= $this->hauteur; ?>" data-personnelid="<?= $personnel->getPersonnelId(); ?>">
                                <?php
                                for ($i = 0; $i < $nbSemainesPlanning * 7; $i++):
                                    // si on est un samedi ou un dimanche
                                    if ($i != 0 and ( ($i + 1) % 7 == 0) or ( $i + 2) % 7 == 0):
                                        ?>
                                        <td class="we matin"></td>
                                        <td class="we aprem">
                                            <?php
                                            if ($i + 1 == ($nbSemainesPlanning * 7)):
                                                if ($this->session->userdata('dateFocus') >= date('Y-m-d')):
                                                    echo '<i class="fas fa-infinity"></i>';
                                                else:
                                                    echo '<i class="fas fa-lock"></i>';
                                                endif;
                                            endif;
                                            ?>
                                        </td>
                                    <?php else: ?>
                                        <td class="<?= $personnel->getPersonnelActif() == 1 ? 'cell matin' : 'matinInactif'; ?>"></td>
                                        <td class="<?= $personnel->getPersonnelActif() == 1 ? 'cell aprem' : 'apremInactif'; ?>"></td>
                                    <?php
                                    endif;
                                endfor;
                                ?>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Livraisons -->
                        <tr style="height : 50px;">
                            <?php
                            for ($i = 0; $i < $nbSemainesPlanning * 7; $i++):
                                // si on est un samedi ou un dimanche
                                if ($i != 0 and ( ($i + 1) % 7 == 0) or ( $i + 2) % 7 == 0):
                                    echo '<td colspan="2" class="weLivraison">';
                                else:
                                    echo '<td colspan="2" class="cellLivraison">';
                                endif;
                                if (!empty($achatsPlanning)):
                                    foreach ($achatsPlanning as $achat):
                                        if ($achat->getAchatLivraisonDate() == $listeDate[$i]):
                                            echo $achat->getAchatHTML();
                                        else:
                                            if ($achat->getAchatLivraisonDate() > $listeDate[$i]):
                                                break;
                                            endif;
                                        endif;
                                    endforeach;
                                    unset($achat);
                                endif;
                                echo '</td>';
                            endfor;
                            ?>
                        </tr>

                        <!-- jours -->
                        <tr>
                            <?php
                            for ($i = 0; $i < $nbSemainesPlanning * 7; $i++):
                                echo '<td class="' . (date('dmy', $listeDate[$i]) == date('dmy') ? 'cellAujourdhui' : 'cellJours') . '" colspan="2" style="width:' . (2 * ($this->largeur + 1.5)) . 'px;">' . date('d', $listeDate[$i]) . '</td>';
                            endfor;
                            ?>
                        </tr>
                        <!-- semaines -->
                        <tr>
                            <?php
// génération des semaines du planning
                            for ($i = 0; $i < $nbSemainesPlanning; $i++):
                                $jourEncours = $premierJourPlanning + (8 + $i * 7) * 86400;
                                ?>
                                <td class="cellSemaines" colspan="14" align="center" style="min-width: <?= 14 * ($this->largeur + 1.5); ?>px;">
                                    <?= $this->cal->dateFrancais($jourEncours, 'Ma') . ' | Semaine ' . date('W', $jourEncours); ?>
                                </td>
                                <?php
                            endfor;
                            unset($jourEncours);
                            ?>
                        </tr>
                    </table>

                    <?php
// --------------------------------- affectations ------------------------------------
                    if (!empty($affectationsPlanning)):
                        foreach ($affectationsPlanning as $affectation):
                            echo $affectation->getAffectationHTML();
                        endforeach;
                    endif;

// ------------------------------ Indisponibilites ------------------------------------------------
                    if (!empty($indisposPlanning)):
                        foreach ($indisposPlanning as $indisponibilite):
                            echo $indisponibilite->getIndispoHTML();
                        endforeach;
                    endif;
                    ?>

                </div>

                <?php
                $y = 1800;
                ?>
            </div>
        </div>

    </div>
</div>

<!--Modal d'actions sur une affectation-->
<div class="modal fade" id="modalAffectation">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="font-size:14px;">
            <div class="modal-header">
                <h5 class="modal-title" id="headerModalAffectation"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <small class="medium">Client : </small><span id="textAffectationClient"></span>
                <small class="medium">Affaire : </small><span id="textAffectationAffaire"></span>
                <div class="row">
                    <div class="col-1" style="text-align: right;margin: 0px; padding:0px;">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="col-11">
                        <small class="medium">Personnel : </small><span id="textAffectationPersonnel"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1" style="text-align: right;margin: 0px; padding:0px;">
                        <i class="fas fa-arrow-right" style=""></i>
                    </div>
                    <div class="col-11">
                        <small class="medium">Chantier : </small><span id="textAffectationChantier"></span>
                        <div class="progress">
                            <div role="progressbar" style="" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" id="textAffectationAvancementHeures"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <small class="medium">Affectation planifiée</small> <span id="textAffectationPeriode"></span>
                <br><small class="medium">Heures planifiées :</small> <span id="textAffectationHeuresPlanifiees"></span>
                <br><small class="medium">Type :</small> <span id="textAffectationType"></span>
                <br><small class="medium">Commentaire :</small> <span id="textAffectationCommentaire"></span>
                <br><small class="medium">Adresse d'intervention :</small> <span id="textAffectationAdresse"></span>

            </div>
            <div class="modal-footer">

                <table class="table table-sm style1" id="tableAffectationHeures">
                    <thead>
                        <tr>
                            <th width="70%">Date</th>
                            <th>Heures</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="2">Aucune heure saisie</td></tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConnect">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="font-size:14px;">
            <div class="modal-header">
                <h5 class="modal-title">Connection personnelle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="connectPersonnelId" value="">
                <div class="row">
                    <div class="col-6">
                        <table id="clavier" class="table table-borderless table-sm">
                            <tr>
                                <td><button class="btn btn-sm btn-outline-dark" id="digit1">1</button></td>
                                <td><button class="btn btn-sm btn-outline-dark" id="digit2">2</button></td>
                                <td><button class="btn btn-sm btn-outline-dark" id="digit3">3</button></td>
                            </tr>
                            <tr>
                                <td><button class="btn btn-sm btn-outline-dark" id="digit4">4</button></td>
                                <td><button class="btn btn-sm btn-outline-dark" id="digit5">5</button></td>
                                <td><button class="btn btn-sm btn-outline-dark" id="digit6">6</button></td>
                            </tr>
                            <tr>
                                <td><button class="btn btn-sm btn-outline-dark" id="digit7">7</button></td>
                                <td><button class="btn btn-sm btn-outline-dark" id="digit8">8</button></td>
                                <td><button class="btn btn-sm btn-outline-dark" id="digit9">9</button></td>
                            </tr>
                            <tr>
                                <td colspan="2"><button class="btn btn-sm btn-outline-dark" id="digit0">0</button></td>
                                <td><button class="btn btn-sm btn-outline-danger" id="digitReset"><i class="fas fa-times"></i></button></td>
                            </tr>

                        </table>
                    </div>
                    <div class="col-6" style="padding:20px; text-align: center;">
                        <span id="spanNomPersonnelConnect"></span>
                        <input type="password" id="connectCode" value="" class="form-control" disabled style="text-align: center;">
                        <br><button type="button" class="btn btn-sm btn-outline-primary">
                            Connection
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div id="changeLandscape">
    <img src="<?= base_url('assets/img/logoClair.png'); ?>" height="70" style="font-size:80px;">
    <br><br><i class="fas fa-mobile-alt fa-spin"></i>
    <br>Passer en paysage !
</div>