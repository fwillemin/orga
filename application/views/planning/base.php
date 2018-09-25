<div class="container-fluid">
    <div class="row" style="margin-top : 15px;">
        <input type="hidden" id="caseHeight" value="<?= $this->hauteur; ?>">
        <input type="hidden" id="caseWidth" value="<?= $this->largeur; ?>">
        <?php
        include('listeChantiers.php');
        ?>

        <div class="col-12">
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
                                    <a href="<?= $link; ?>" target="_self">
                                        <span class="<?= $personnel->getPersonnelActif() == 1 ? 'badge-light' : 'badge-secondary'; ?>" style="">
                                            <?= $personnel->getPersonnelNom() . ' ' . substr($personnel->getPersonnelPrenom(), 0, 1) . ' <i class="fas fa-play" style=""></i>'; ?>
                                        </span>
                                    </a>
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

                <div class="col-10" id="divPlanning" today="<?= $today; ?>" style="overflow-x: scroll; padding-left: 0px;">
                    <div id="masquePlanning" style="width: <?= ($nbSemainesPlanning * 7 * ((($this->largeur + 1) * 2))) . 'px'; ?>;"></div>

                    <table cellspacing="0" border="0" id="tablePlanning">
                        <!-- semaines -->
                        <tr>
                            <?php
                            // génération des semaines du planning
                            for ($i = 0; $i < $nbSemainesPlanning; $i++):
                                $jourEncours = $premierJourPlanning + (8 + $i * 7) * 86400;
                                ?>
                                <td class="cellSemaines" colspan="14" align="center" style="width: <?= 14 * ($this->largeur + 1.5); ?>px;">
                                    <?= $this->cal->dateFrancais($jourEncours, 'Ma') . ' | Semaine ' . date('W', $jourEncours); ?>
                                </td>
                                <?php
                            endfor;
                            unset($jourEncours);
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
                                        <td class="we aprem"></td>
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
                                    echo '<td colspan="2" class="weLivraison"></td>';
                                else:
                                    ?>
                                    <td colspan="2" class="cellLivraison">
                                        <?php
                                        if (!empty($livraisonsPlanning)):
                                            foreach ($livraisonsPlanning as $livraison):
                                                if ($livraison->getLivraisonDate() == $listeDate[$i]):
                                                    ?>
                                                    <div style="border:1px solid <?= $livraison->getLivraisonChantier()->getChantierCouleurSecondaire(); ?>; color: <?= $livraison->getLivraisonChantier()->getChantierCouleurSecondaire(); ?>; background-color:<?= $livraison->getLivraisonChantier()->getChantierCouleur(); ?>;"
                                                         class="livraison"
                                                         data-livraisonid="<?= $livraison->getLivraisonId(); ?>"
                                                         data-chantierid="<?= $livraison->getLivraisonChantierId(); ?>"
                                                         data-toggle="popover"
                                                         data-placement="bottom"
                                                         data-contraintes = "<?= implode(',', $livraison->getLivraisonContraintesIds()); ?>"
                                                         title="<?= '<small class=\'medium\'>Chantier : </small><span style=\'font-size:13px;\'>' . $livraison->getLivraisonChantier()->getChantierObjet() . '</span>'; ?>"
                                                         data-content="<?=
                                                         ($livraison->getLivraisonFournisseurId() ? 'Fournisseur : ' . $livraison->getLivraisonFournisseur()->getFournisseurNom() . '<br>' : '')
                                                         . '<small class=\'medium\'>' . $livraison->getLivraisonEtatText() . ' pour <b>' . sizeof($livraison->getLivraisonContraintesIds()) . ' affectation(s)</b></small>'
                                                         . '<br>' . nl2br($livraison->getLivraisonRemarque())
                                                         . '<button class=\'btn btn-outline-dark btn-sm\' style=\'position:absolute; right:3px; bottom: 3px;\'><i class=\'fas fa-edit\'></i> Modifier</button>';
                                                         ?>"
                                                         >
                                                             <?php if (sizeof($livraison->getLivraisonContraintesIds()) > 0): ?>
                                                            <i class="fas fa-link"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php
                                                else:
                                                    if ($livraison->getLivraisonDate() > $listeDate[$i]):
                                                        break;
                                                    endif;
                                                endif;
                                            endforeach;
                                        endif;
                                        ?>
                                    </td>
                                <?php
                                endif;
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
                                    <button class="btn btn-sm btn-outline-info btnListingLivraison" semaine="<?= date('W', $jourEncours); ?>" annee ="<?= date('Y', $jourEncours); ?>">Livraisons</button>
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
                    if (!empty($indisponibilitesPlanning)):
                        foreach ($indisponibilitesPlanning as $indisponibilite):
                            echo $indisponibilite->getIndisponibiliteHTML();
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


<!-- add_affect ---------------------------------------------------------------- -->
<?php
/* on teste si le user connecté peut Ajouter une affectation */
if ($this->ion_auth->in_group(array(25))):
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
                <small class="medium">Affectation planifiée</small> <span id="textAffectationPeriode"></span>
                <br><small class="medium">Type :</small> <span id="textAffectationType"></span>
                <br><small class="medium">Commentaire :</small> <span id="textAffectationCommentaire"></span>
                <br><small class="medium">Adresse d'intervention :</small> <span id="textAffectationAdresse"></span>

            </div>
            <div class="modal-footer">

                <div class="col-4">
                    <button class="btn btn-outline-dark btn-sm" id="btnModAffectation">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                    <br>
                    <button class="btn btn-outline-danger btn-sm" id="btnDelAffectation" style="margin-top:10px;">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
                <div class="col-8">
                    Couper l'affectation le
                </div>

            </div>
        </div>
    </div>
</div>

<?php
if ($this->ion_auth->in_group(array(61))):
    ?>
    <!--Modal Livraisons-->
    <div class="modal fade" id="modalLivraison">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="font-size:14px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="headerModalLivraison"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <?= form_open('planning/addLivraison/', array('id' => 'formAddLivraison')); ?>
                    <input type="hidden" name="addLivraisonId" id="addLivraisonId" value="" >
                    <div class="form-row" style="margin-top: 4px;">
                        <div class="col">
                            <label for="addLivraisonDate" class="">Le</label>
                            <input type="date" name="addLivraisonDate" id="addLivraisonDate" value="" class="form-control" required >
                        </div>
                    </div>
                    <div class="form-row" style="margin-top: 4px;">
                        <div class="col">
                            <label for="addLivraisonChantier" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">Chantier</label>
                            <select name="addLivraisonChantier" id="addLivraisonChantier" class="superselect" data-width="100%" data-show-subtext="true">
                                <option value="0">Selectionnez un chantier...</option>
                                <?php
                                foreach ($liste_chantier as $chantier):
                                    if ($chantier->getEtat() <> 'Termine'):
                                        echo '<option value="' . $chantier->getId() . '" data-content="<span style=\'font-size:12px;\'>' . $chantier->getClient() . '</span> - <span style=\'color:blue;\'>' . $chantier->getChantierCategorie() . '</span><span style=\'font-weight:bold; color:grey; font-size:8px; position:relative; top:5px;\' class=\'pull-right\'>' . $chantier->getVille() . '</span>">' . $chantier->getClient() . ' | ' . $chantier->getVille() . '</option>';
                                    endif;
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row" style="margin-top: 4px;">
                        <div class="col">
                            <label for="addLivraisonFournisseur" class="col-6 col-sm-3">Founisseur</label>
                            <select name="addLivraisonFournisseur" id="addLivraisonFournisseur" class="form-control" data-width="100%">
                                <option value="0">Selectionnez un fournisseur...</option>
                                <?php
                                if (!empty($listeFournisseurs)):
                                    foreach ($listeFournisseurs as $f):
                                        echo '<option value="' . $f->getFournisseurId() . '" >' . $f->getFournisseurNom() . '</option>';
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row" style="margin-top: 4px;">
                        <div class="col">
                            <label for="addLivraisonRemarque" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">Remarque</label>
                            <textarea class="form-control" name="addLivraisonRemarque" id="addLivraisonRemarque"></textarea>
                        </div>
                    </div>
                    <div class="form-row" style="margin-top: 4px;">
                        <div class="col">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6">
                                <select name="addLivraisonEtat" id="addLivraisonEtat" class="form-control" data-width="100%">
                                    <option value="0">Attente</option>
                                    <option value="1">Confirmée</option>
                                    <option value="2">Reçue</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row" style="margin-top: 4px;">
                            <div class="col">
                                <label for="addLivraisonContrainte" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">Nécessaire pour</label>
                                <div id="addLivraisonListeContrainte">

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary pull-right" type="submit" id="livraisonSubmit"><i class="glyphicon glyphicon-plus"></i> Ajouter cette livraison</button>
                            <?= form_close(); ?>
                            <button class="btn btn-sm btn-danger tooltipOk pull-left" id="btnDeleteLivraison" data-placement="right" title="Double-click pour supprimer"><i class="glyphicon glyphicon-trash"></i> Supprimer</button>

                        </div>
                    </div>
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
