<div class="container-fluid">
    <div class="row" style="margin-top : 15px;">
        <input type="hidden" id="caseHeight" value="<?= $this->hauteur; ?>">
        <input type="hidden" id="caseWidth" value="<?= $this->largeur; ?>">
        <?php
        include('listeChantiers.php');


        if (!empty($personnelsPlanning)) :
            ?>

            <div class="col-12">
                <div class="row" style="margin-top:1px;">
                    <div class="col-2"></div>
                    <div class="col-10" style="font-size:12px; color: steelblue;">
                        <?= $analyseRapide['nbAffairesEncours'] . ' affaires en cours - ' . $analyseRapide['nbAffairesCloses'] . ' affaires closes - <b>' . number_format($analyseRapide['nbHeuresPlannifiees'], 2, ',', ' ') . '</b> heures plannifiées soit une charge de <b>' . $analyseRapide['chargeSemaines'] . '</b> semaines'; ?>
                    </div>
                </div>
                <div class="row">
                    <!-- liste du personnel -->
                    <div class="col-2" align="right">
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
                                    <td align="right">
                                        <a href="<?= $link; ?>" target="_self" title="<?= !empty($personnel->getPersonnelEquipe()) ? 'Equipe : ' . $personnel->getPersonnelEquipe()->getEquipeNom() : ''; ?>">
                                            <span class="<?= $personnel->getPersonnelActif() == 1 ? 'badge-light' : 'badge-secondary'; ?>"
                                                  <?= !empty($personnel->getPersonnelEquipe()) ? 'style="color:' . $personnel->getPersonnelEquipe()->getEquipeCouleurSecondaire() . '; background-color:' . $personnel->getPersonnelEquipe()->getEquipeCouleur() . ';"' : ''; ?>>
                                                      <?= $personnel->getPersonnelNom() . ' ' . substr($personnel->getPersonnelPrenom(), 0, 1) . ' <i class="fas fa-play" style=""></i>'; ?>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr height="50">
                                <td align="right">
                                    <span class="badge-light">
                                        LIVRAISONS <i class="fas fa-play" style=""></i>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-10" id="divPlanning" today="<?= $today; ?>" style="overflow-x: scroll; padding-left: 0px;">
                        <div id="masquePlanning" style="width: <?= ($nbSemainesPlanning * 7 * ((($this->largeur + 1) * 2))) . 'px'; ?>;"></div>

                        <table cellspacing="0" border="0" id="tablePlanning">
                            <!-- semaines -->
                            <tr>
                                <?php
                                // génération des semaines du planning
                                $currentDate = $premierJourPlanning - 86400 * 7;
                                $heureEte = date('I', $premierJourPlanning); /* indique si le premier jour du planning est été ou hiver */
                                for ($i = 0; $i < $nbSemainesPlanning; $i++):
                                    $currentDate += 86400 * 7;
                                    //$listeDate[$i] = $currentDate;
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
                                        <?= $this->cal->dateFrancais($currentDate, 'Ma') . ' | Semaine ' . date('W', $currentDate) . ' ' . date('Y', $currentDate); ?>
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
                                    //$listeDate[$i] = $currentDate;
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
                                    $listeDate[$i] = $currentDate;
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
                                for ($i = 0; $i < $nbSemainesPlanning; $i++):
                                    $jourEncours = $premierJourPlanning + ($i * 7) * 86400;
                                    ?>
                                    <td class="cellSemaines" colspan="14" align="center" style="min-width: <?= 14 * ($this->largeur + 1.5); ?>px;">
                                        <button class="btn btn-sm btn-link btnListingLivraison" data-startweek="<?= $jourEncours; ?>">Livraisons</button>
                                        <?= $this->cal->dateFrancais($jourEncours, 'DMa') . ' | Semaine ' . date('W', $jourEncours) . ' ' . date('Y', $currentDate); ?>
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

        <?php else: ?>

            <div class="col-12 col-sm-8 offset-sm-2 alert alert-dark">
                Il n'y a aucun personnel de chantier créé ou tous les personnels de chantier sont inatifs.
                <br>Allez à la section <a href="<?= site_url('personnels/liste'); ?>"><i class="fas fa-user-ninja"></i> Personnels de chantier</a>.
            </div>

        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalAffichageLivraison">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<?php
/* on teste si le user connecté peut Ajouter une affectation */
if ($this->ion_auth->in_group(array(60))):
    ?>
    <div class="modal fade" id="modalAddAffectation">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une affectation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <?php include('formAffectation.php'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;
/* on teste si le user connecté peut Ajouter une indisponibilite */
if ($this->ion_auth->in_group(array(26))):
    ?>
    <div class="modal fade" id="modalAddIndispo">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <?php include('application/views/personnels/formIndisponibilite.php'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;

/* on teste si le user connecté peut Ajouter un achat */
if ($this->ion_auth->in_group(array(55))):
    ?>
    <div class="modal fade" id="modalAddAchat">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un achat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <?php include('application/views/chantiers/formAchat.php'); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif;
?>
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
                <div class="row">
                    <div class="col-10">
                        <small class="medium">Affectation planifiée</small> <span id="textAffectationPeriode"></span>
                        <br><small class="medium">Type :</small> <span id="textAffectationType"></span>
                        , <small class="medium">Heures planifiées :</small> <span id="textAffectationHeuresPlanifiees"></span>
                        <br><small class="medium">Adresse d'intervention :</small> <span id="textAffectationAdresse"></span>
                        <br><small class="medium">Commentaire :</small> <span id="textAffectationCommentaire"></span>
                    </div>
                    <div class="col-2" style="text-align: center; display: none;">
                        <button class="btn btn-secondary" style="height: 100%;" id="btnSMS">
                            <i class="fas fa-envelope"></i>
                            <br>SMS
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row" style="width:100%;">
                    <div class="col-4">
                        <table class="table table-sm style1" id="tableAffectationHeures">
                            <thead>
                                <tr>
                                    <th width="70%">Date</th>
                                    <th>Heures</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="3">Aucune heure saisie</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col" style="vertical-align: top;">
                        <div id="operationsAffectation">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-dark" id="btnModAffectation">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <button class="btn btn-outline-danger" id="btnDelAffectation">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                            <div class="form-row" style="margin-top:6px;">
                                <div class="col">
                                    <label for="couperDate">Couper l'affectation le</label>
                                    <input type="date" class="form-control form-control-sm" id="couperDate" value="" style="text-align: right;">
                                </div>
                                <div class="col">
                                    <br>
                                    <select class="form-control form-control-sm" id="couperMoment">
                                        <option value="1">Fin de matinée</option>
                                        <option value="2">Fin de journée</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <br>
                                    <button class="btn btn-outline-dark btn-sm" type="button" id="btnCutAffectation">
                                        <i class="fas fa-cut"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-row" style="margin-top:6px;">
                                <div class="col">
                                    <label for="addAffectationDebutDate">Décaler cette affectation</label>
                                    <select id="decalageCible" class="form-control form-control-sm">
                                        <option value="1" selected>uniquement</option>
                                        <option value="2">et les suivantes</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="addAffectationDebutDate">Jours calendaires</label>
                                    <select class="form-control form-control-sm" id="decalageQte">
                                        <?php
                                        for ($i = 1; $i <= 30; $i++):
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        endfor;
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <br>
                                    <button class="btn btn-outline-dark btn-sm" type="button" id="btnDecaleAffectationPasse">
                                        <i class="fas fa-arrow-alt-circle-left"></i>
                                    </button>
                                    <button class="btn btn-outline-dark btn-sm" type="button" id="btnDecaleAffectationFutur">
                                        <i class="fas fa-arrow-alt-circle-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSMS">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="font-size:14px;">
            <div class="modal-header">
                <h5 class="modal-title">Envoyer un SMS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <small class="orange" style="position: absolute; top: 3px; right: 5px;">
                    Crédits : <?= $this->session->userdata('smsCredits') . ' SMS'; ?>
                </small>
                <div class="form-row" style="margin-top: 4px;">
                    <div class="col-8">
                        <label for="addPersonnelCode">Envoyer à</label>
                        <select class="form-control" id="envoisSmsDestinataire">

                        </select>
                    </div>
                    <div class="col-4">
                        <label for="addPersonnelPortable">Portable</label>
                        <input type="text" class="form-control" id="envoiSmsNumero" name="envoiSmsNumero" placeholder="N° Tel Portable" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if ($this->ion_auth->in_group(array(61))):
    ?>
    <!--Modal Livraisons-->
    <div class="modal fade" id="modalAddLivraison">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="font-size:14px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="headerModalLivraison">Ajouter une livraison</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <?= form_open('planning/addDateLivraison/', array('id' => 'formAddLivraison')); ?>
                    <div class="form-row" style="margin-top: 4px;">
                        <div class="col">
                            <label for="addLivraisonChantierId">Sélectionnez un chantier</label><br>
                            <select name="addLivraisonChantierId" id="addLivraisonChantierId" class="selectpicker" data-width="100%" data-live-search="true" required title="Selectionnez un chantier d'intervention">
                                <?php
                                if (!empty($affairesPlanning)):
                                    foreach ($affairesPlanning as $affaire):
                                        if ($affaire && !empty($affaire->getAffaireChantiers())):
                                            foreach ($affaire->getAffaireChantiers() as $chantier):
                                                echo '<option value="' . $chantier->getChantierId() . '"'
                                                . 'data-content="<span class=\'selectpickerClient\'>' . $affaire->getAffaireClient()->getClientNom() . '</span> <span class=\'selectpickerAnnotation\'>' . $affaire->getAffaireObjet() . ' > ' . $chantier->getChantierObjet() . '</span>">' . $affaire->getAffaireClient()->getClientNom() . ' ' . $chantier->getChantierObjet() . '</option>';
                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row" style="margin-top: 4px;">
                        <div class="col">
                            <label for="addLivraisonAchatId">Sélectionnez un achat</label><br>
                            <select name="addLivraisonAchatId" id="addLivraisonAchatId" class="selectpicker" data-width="100%" required title="Selectionnez un achat" disabled>
                                <option value="0" data-content="<span style='color:orange; font-size:13px;'><i class='fas fa-plus-square'></i> Ajouter un nouvel achat sur le chantier</span>">ADD</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row" style="margin-top: 4px;">
                        <div class="col-3">
                            <label for="addLivraisonDate">Date de livraison</label>
                            <input type="date" class="form-control form-control-sm text-right" id="addLivraisonDate" name="addLivraisonDate" value="">
                        </div>
                        <div class="col-5">
                            <label for="addLivraisonAvancement">Avancement</label>
                            <select name="addLivraisonAvancement" id="addLivraisonAvancement" class="form-control form-control-sm">
                                <option value="1">Attente</option>
                                <option value="2">Confirmée</option>
                                <option value="3">Réceptionnée</option>
                            </select>
                        </div>
                        <div class="col-4" style="text-align: right;">
                            <br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-calendar-alt"></i> Ajouter cette livraison</button>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Suivi des livraisons -->
    <div class="modal fade" id="modalSuiviLivraison">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="font-size:14px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="headerModalLivraison">Suivi des livraisons</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered style1" id="tableSuiviLivraisons">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th style="width: 120px;">Livraison</th>
                                <th style="width: 250px;">Description</th>
                                <th>Qte</th>
                                <th style="width: 150px;">Etat</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour l'affichage du listing des livraisons fournisseurs hebdomadaires -->
    <div class="modal fade" id="modalLivraisonsHebdomadaires" cible="" tabindex="-1" role="dialog" aria-labelledby="Livraisons fournisseurs de la semaine" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #428bca; color:#FFF;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" align="center">
                    <table class="table table-striped">
                        <thead>
                        <th style="width:20px;"></th>
                        <th>Date</th>
                        <th>Fournisseur</th>
                        <th>Client</th>
                        <th>Remarque</th>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($listeLivraison)):
                                foreach ($listeLivraison as $l):
                                    ?>
                                    <tr class="ligneLivraison" semaine="<?= date('W', $l->getLivraisonDate()); ?>" annee="<?= date('Y', $l->getLivraisonDate()); ?>">
                                        <td style="background-color:<?= $l->getLivraisonCouleur(); ?>;"></td>
                                        <td><?= date('d/m/Y', $l->getLivraisonDate()); ?></td>
                                        <td><?= $l->getLivraisonFournisseur() . '<br>' . $l->getLivraisonFournisseurTelephone(); ?></td>
                                        <td><?= $l->getLivraisonClient(); ?></td>
                                        <td><?= $l->getLivraisonRemarque(); ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<?php endif; ?>
