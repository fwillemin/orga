<div class="container-fluid">
    <div class="row" style="margin-top : 15px;">

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

                <div class="col-10" id="planningDiv" today="<?= $today; ?>" style="overflow-x: scroll; padding-left: 0px;">
                    <!-- planning -->

                    <table cellspacing="0" border="0">

                        <!-- semaines -->
                        <tr>
                            <?php
                            // génération des semaines du planning
                            for ($i = 0; $i < $nbSemainesPlanning; $i++):
                                $jourEncours = $premierJourPlanning + (8 + $i * 7) * 86400;
                                ?>
                                <td class="cellSemaines" colspan="14" align="center">
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
                            $currentDate = $premierJourPlanning - 86400;
                            $heureEte = date('I', $premierJourPlanning); /* indique si le premier jour du planning est été ou hiver */
                            for ($i = 0; $i < $nbSemainesPlanning * 7; $i++):
                                $currentDate += 86400;
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
                                if (date('dmy', $currentDate) == date('dmy')):
                                    ?>
                                    <td class="cellAujourdhui" align="center" colspan="2"><?= date('d', $currentDate); ?></td>
                                <?php else: ?>
                                    <td class="cellJours" align="center" colspan="2"><?= date('d', $currentDate); ?></td>
                                <?php
                                endif;
                            endfor;
                            ?>
                        </tr>


                        <!-- Personnel -->
                        <!-- une ligne par personnel actif dans l'établissement -->
                        <?php
                        $personnelListe = array(); /* Liste qui va contenir tous les ids des personnels affichés */
                        foreach ($personnelsPlanning as $personnel):
                            $personnelListe[] = $personnel->getPersonnelId();
                            ?>
                            <tr height="<?= $this->hauteur; ?>">
                                <?php
                                $heureEte = date('I', $premierJourPlanning); /* indique si le premier jour du planning est été ou hiver */
                                $currentDate = $premierJourPlanning - 86400;
                                for ($i = 0; $i < $nbSemainesPlanning * 7; $i++):

                                    $currentDate += 86400;
                                    /* Gestion des passages en heure été et heure hiver */
                                    if (date('I', $currentDate) != $heureEte):
                                        if ($heureEte == 1):
                                            /* on ajoute une heure */
                                            $currentDate += 3600;
                                        else:
                                            /* on retire une heure */
                                            $currentDate -= 3600;
                                        endif;
                                        $heureEte = abs($heureEte - 1);
                                    endif;
                                    // si on est un samedi ou un dimanche
                                    if ($i != 0 and ( ($i + 1) % 7 == 0) or ( $i + 2) % 7 == 0):
                                        ?>
                                        <td class="we matin" style="width:<?= $this->largeur; ?>;"></td>
                                        <td class="we aprem" style="width:<?= $this->largeur; ?>;"></td>
                                    <?php else: ?>
                                        <td class="<?= $personnel->getPersonnelActif() == 1 ? 'cell matin' : 'matinInactif'; ?>" jour="<?= date('Y-m-d', $currentDate); ?>" data-personnelid="<?= $personnel->getPersonnelId(); ?>" style="width:<?= $this->largeur; ?>;"></td>
                                        <td class="<?= $personnel->getPersonnelActif() == 1 ? 'cell aprem' : 'apremInactif'; ?>" jour="<?= date('Y-m-d', $currentDate); ?>" data-personnelid="<?= $personnel->getPersonnelId(); ?>" style="width:<?= $this->largeur; ?>;"></td>
                                    <?php
                                    endif;
                                endfor;
                                ?>
                            </tr>
                        <?php endforeach; ?>

                        <tr style="height : 50px;">
                            <?php
                            $heureEte = date('I', $premierJourPlanning); /* indique si le premier jour du planning est été ou hiver */
                            $currentDate = $premierJourPlanning - 86400;
                            for ($i = 0; $i < $nbSemainesPlanning * 7; $i++) {
                                $currentDate += 86400;
                                /* Gestion des passages en heure été et heure hiver */
                                if (date('I', $currentDate) != $heureEte):
                                    if ($heureEte == 1):
                                        /* on ajoute une heure */
                                        $currentDate += 3600;
                                    else:
                                        /* on retire une heure */
                                        $currentDate -= 3600;
                                    endif;
                                    $heureEte = abs($heureEte - 1);
                                endif;
                                // si on est un samedi ou un dimanche
                                if ($i != 0 and ( ($i + 1) % 7 == 0) or ( $i + 2) % 7 == 0) {
                                    ?>
                                    <td colspan="2" class="weLivraison"></td>
                                <?php } else { ?>
                                    <td colspan="2" class="cellLivraison" jour="<?= date('Y-m-d', $currentDate); ?>">
                                        <?php
                                        if (!empty($listeLivraison)):
                                            foreach ($listeLivraison as $l):
                                                if (date('Y-m-d', $l->getLivraisonDate()) == date('Y-m-d', $currentDate)):
                                                    ?>
                                                    <a style="border:1px solid <?= $this->organibat->SetBright($l->getLivraisonCouleur(), 110); ?>; color:<?= $this->organibat->SetBright($l->getLivraisonCouleur(), 110); ?>; background-color:<?= $l->getLivraisonCouleur(); ?>; cursor:pointer; position:relative;"
                                                       class="markerLivraison <?= 'lc' . $l->getLivraisonChantierId(); ?>"
                                                       id="<?= 'liv' . $l->getLivraisonId(); ?>"
                                                       data-toggle="popover"
                                                       data-trigger="manual"
                                                       data-title=" "
                                                       data-content=" "
                                                       tabindex="0"
                                                       data-placement="bottom">

                                                        <?php if ($l->getLivraisonNbContrainte() > 0): ?>
                                                            <i class="glyphicon glyphicon-link"></i>
                                                        <?php endif; ?>
                                                    </a>
                                                    <?php
                                                else:
                                                    if (date('Y-m-d', $l->getLivraisonDate()) > date('Y-m-d', $currentDate))
                                                        break;
                                                endif;
                                            endforeach;
                                        endif;
                                        ?>
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                        </tr>
                        <!-- jours -->
                        <tr>
                            <?php
                            $currentDate = $premierJourPlanning - 86400;
                            $heureEte = date('I', $premierJourPlanning); /* indique si le premier jour du planning est été ou hiver */
                            for ($i = 0; $i < $nbSemainesPlanning * 7; $i++) {
                                $currentDate += 86400;
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
                                if (date('dmy', $currentDate) == date('dmy')) {
                                    ?>
                                    <td class="cell_aujourdhui reverse" align="center" colspan="2"><?= date('d', $currentDate); ?></td>
                                <?php } else { ?>
                                    <td class="cell_jours reverse" align="center" colspan="2"><?= date('d', $currentDate); ?></td>
                                    <?php
                                }
                            }
                            ?>
                        </tr>
                        <!-- semaines -->
                        <tr>
                            <?php
                            // génération des semaines du planning
                            for ($i = 0; $i < $nbSemainesPlanning; $i++):
                                $jourEncours = $premierJourPlanning + (8 + $i * 7) * 86400;
                                ?>
                                <td class="cellSemaines" colspan="14" align="center">
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
                    // --------------------------------- affectation ------------------------------------
                    if (!empty($affectationsPlanning)):
                        foreach ($affectationsPlanning as $affectation):

                            /* si l'affectation ne rentre pas dans le planning (cas des divers) ou que le personnel n'existe pas (personnel ayant uniquement des divers) on passe */
//                            if ($a->getfin() < $premierJourPlanning || !in_array($a->getId_personnel(), $personnelListe)):
//                                continue;
//                            else:
                            //recherche de la ligne d'apposition
//                                $i = 0;
//                                foreach ($personnelsPlanning as $p):
//                                    if ($a->getId_personnel() == $p->getId()) {
//                                        $ligne = $i;
//                                        break;
//                                    }
//                                    $i++;
//                                endforeach;
//                                /* on ne peux plus déplacer une affectation qui a eu des heures saisies */
//                                if (intval($a->getAffectationHeuresSaisies()) == 0):
//                                    $drag = TRUE;
//                                else:
//                                    $drag = FALSE;
//                                endif;
//
//                                if ($a->getEtat() == 'Termine'): $type = 'termine';
//                                    $drag = FALSE;
//                                    $resize = FALSE;
//                                else: $type = 'active';
//                                    $resize = TRUE;
//                                endif;
//                                /* on restreint les options si l'utilisateur n'est pas au mins niveau 2 (direction ou administration) */
//                                if ($this->session->userdata('niveau') > 2): $drag = FALSE;
//                                    $resize = FALSE;
//                                endif;
//                                /* on lance l'apposition de l'affectation sur le planning */
//                                echo $this->organibat->affichageDiv($a, $type, $premierJourPlanning, $ligne, $drag, $resize, $hauteur, $largeur);
                            echo $affectation->getAffectationHTML();

//                            endif;
                        endforeach;
                    endif;

                    // ------------------------------ Indispo ------------------------------------------------
                    if (!empty($indisponibilite)):
                        foreach ($indisponibilite as $ind):
                            //recherche de la ligne d'apposition
                            $i = 0;
                            foreach ($personnelsPlanning as $p):
                                if ($ind->getId_personnel() == $p->getId()) {
                                    $ligne = $i;
                                    break;
                                }
                                $i++;
                            endforeach;
                            echo $this->organibat->affichageDiv($ind, 'indispo', $premierJourPlanning, $ligne, FALSE, FALSE, $hauteur, $largeur);
                        endforeach;
                    endif;

                    // ----------------------------- fin des div planning ----------------------------------------
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
/* on teste si le user connecté peut utiliser ces modals */
if ($this->session->userdata('niveau') <= 2):
    ?>
    <div class="modal fade" id="modal_add_affect" tabindex="-1" role="dialog" aria-labelledby="Ajouter une affectation" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #428bca; color:#FFF;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                    <h4 class="modal-title"><strong>Ajouter au planning</strong></h4>
                </div>
                <div class="modal-body">

                    <ul class="nav nav-tabs" role="tablist" id="affectTabList">
                        <li class="active"><a href="#affectTab" role="tab" data-toggle="tab">Affectation</a></li>
                        <li><a href="#indispoTab" role="tab" data-toggle="tab">Indisponibilité</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" style="padding:10px;" id="affectTab">
                            <?= form_open('planning/ajouter_affect', array('class' => 'form-horizontal', 'id' => 'formAddAffect')); ?>

                            <input type="hidden" id="add_affect_personnel" name="personnel" value="" />
                            <input type="hidden" id="add_affect_debut" name="debut" value="" />
                            <input type="hidden" id="add_affect_demi" name="demi" value="" />
                            <input type="hidden" id="add_affect_premier_jour" name="premier_jour" value="<?= $premierJourPlanning; ?>" />
                            <input type="hidden" name="etat" id="addAffectEtat" value="Encours" />
                            <input type="hidden" name="addAffectGps" id="addAffectGps" value="" />

                            <div class="form-group">
                                <label for="addAffectChantier" class="col-lg-3 col-md-3 col-sm-4 col-xs-12">Chantier</label>
                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                                    <select name="chantier" class="superselect" data-width="100%" id="addAffectChantier">
                                        <option value="" disabled="" selected>Choississez un chantier</option>
                                        <?php
                                        if (!empty($liste_chantier)):
                                            foreach ($liste_chantier as $chantier):
                                                if ($chantier->getEtat() <> 'Termine'):
                                                    echo '<option value="' . $chantier->getId() . '" data-content="<span style=\'font-size:12px;\'>' . $chantier->getClient() . '</span> - <span style=\'color:blue; font-size:10px;\'>' . $chantier->getChantierCategorie() . '</span><span style=\'font-weight:bold; color:grey; font-size:8px; position:relative; top:5px;\' class=\'pull-right\'>' . $chantier->getVille() . '</span><br/><span style=\'font-size:11px;\'>' . $chantier->getObjet() . '</span>">' . $chantier->getClient() . ' | ' . $chantier->getVille() . '</option>';
                                                endif;
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span id="addAffectLieu" style="font-size:10px;"></span>
                                    <input type="checkbox" name="addAffectModAdresse" id="addAffectModAdresse" value="1" /> <span style="color:#428bca; font-size:10px;">Autre adresse</span>
                                </div>
                            </div>

                            <div id="addAffectLocalisation" style="display:none;">
                                <div class="form-group">
                                    <label for="addAffectAdresse" class="col-lg-3 col-md-3 col-sm-3 col-xs-4">Adresse</label>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <input type="text" name="addAffectAdresse" id="addAffectAdresse" value="" placeholder="Adresse" class="form-control input-sm" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-3" for="addAffectCp">Code postal</label>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-9">
                                        <input type="text" class="form-control input-sm" name="addAffectCp" id="addAffectCp" placeholder="Code postal" value="" />
                                    </div>
                                    <label class="col-lg-1 col-md-1 col-sm-1 col-xs-3 control-label" for="addAffectVille">Ville</label>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-9">
                                        <select name="addAffectVille" id="addAffectVille" class="form-control input-sm">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="addAffectNbDemi" class="col-lg-3 col-md-3 col-sm-4 col-xs-12">Durée</label>
                                <div class="col-lg-3 col-md-3 col-sm-7 col-xs-12">
                                    <div class="input-group">
                                        <select name="nb_demi" id="addAffectNbDemi" class="form-control">
                                            <?php
                                            for ($i = 1; $i < 91; $i++):
                                                echo '<option>' . $i . '</option>';
                                            endfor;
                                            ?>
                                        </select>
                                        <span class="input-group-addon">&frac12;j</span>
                                    </div>
                                </div>

                                <label for="addAffectType" class="col-lg-1 col-md-1 col-sm-2 col-xs-12">Type</label>
                                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
                                    <select class="form-control" name="type" required id="addAffectType">
                                        <option value="0">Chantier</option>
                                        <option value="1">SAV - Dépannage</option>
                                        <option value="2">Atelier</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="addAffectCommentaire" class="col-lg-3 col-md-3 col-sm-4 col-xs-12">Commentaire</label>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                    <textarea class="form-control" rows="3" name="commentaire" id="addAffectCommentaire"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Ajouter</button>
                            <?= form_close(); ?>
                        </div>

                        <div class="tab-pane" id="indispoTab" style="padding:10px;">

                            <?= form_open('rh/addIndispo', array('class' => 'form-horizontal', 'id' => 'formAddIndispo')); ?>
                            <input type="hidden" name="addIndispoId" value="" id="addIndispoId" />
                            <input type="hidden" name="addIndispoPersonnelId" value="" id="addIndispoPersonnelId" />
                            <input type="hidden" name="addIndispoRedirect" value="planning" id="addIndispoRedirect" />

                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Débute le</label>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <input type="date" required name="addIndispoDebut" value="" class="form-control" id="addIndispoDebut" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                    <select class="form-control" name="addIndispoDebutDemi" id="addIndispoDebutDemi">
                                        <option value="0">Matin</option>
                                        <option value="1">Après-midi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Fini le</label>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <input type="date" required name="addIndispoFin" value="" class="form-control" id="addIndispoFin" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                    <select class="form-control" name="addIndispoFinDemi" id="addIndispoFinDemi">
                                        <option value="0">Matin</option>
                                        <option value="1">Après-midi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-6 col-md-6 col-sm-3 col-xs-4"><span style="color:green; padding-right:20px;"> OU </span>Nombre de 1/2j</label>
                                <div class="col-lg-3 col-ms-3 col-sm-3 col-xs-8">
                                    <select name="addIndispoNbDemi" class="form-control" id="addIndispoNbDemi" style="color:#fff;">

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 col-ms-3 col-sm-3 col-xs-4">Motif</label>
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-8">
                                    <select class="form-control" name="addIndispoType" id="addIndispoType">
                                        <option value="6">Intempéries</option>
                                        <option value="1">Maladie</option>
                                        <option value="2">RTT</option>
                                        <option value="3">Congés</option>
                                        <option value="4">AT</option>
                                        <option value="8">Sans solde</option>
                                        <option value="7">Injustifiée</option>
                                        <option value="5">Férié</option>
                                        <option value="0">Formation</option>
                                        <option value="9">CFA</option>
                                        <option value="10">Autorisé</option>
                                        <option value="11">Paternité/Maternité</option>
                                        <option value="12">Evenements familiaux</option>
                                        <option value="13">Mise à pied</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-4">Ajouter à tous</label>
                                <div class="col-lg-3 col-ms-3 col-sm-3 col-xs-8">
                                    <input type="checkbox" name="addIndispoGlobal" id="addIndispoGlobal" value="1" />
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info" id="formAddIndispoSubmit" style="display:none;"><i class="glyphicon glyphicon-plus"></i> Ajouter</button>

                            <?= form_close(); ?>
                            <button class="btn btn-xs btn-danger tooltipOk pull-right btnDelIndispo" cible="" data-placement="left" title="Double-click pour supprimer"><i class="glyphicon glyphicon-trash"></i> Supprimer</button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modifier une affect ------------------------------------------------------- -->
    <div class="modal fade" id="mod_affect" tabindex="-1" role="dialog" aria-labelledby="Modifier une affectation" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #428bca; color:#FFF;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                    <h4 class="modal-title"><strong>Modifier une affectation</strong></h4>
                </div>
                <div class="modal-body">

                    <?= form_open('planning/addAffectation/', array('class' => 'form-horizontal', 'id' => 'formModAffect')); ?>
                    <input type="hidden" id="mod_affect_id" name="id" value="" />
                    <input type="hidden" id="modAffectGps" name="addAffectGps" value="" />
                    <input type="hidden" id="mod_affect_premier_jour" name="premier_jour" value="<?= $premierJourPlanning; ?>" />
                    <input type="hidden" id="modAffectEtat" name="etat" value="" />
                    <div class="form-group">
                        <label for="mod_affect_personnel" class="col-lg-3 col-md-3 col-sm-4 col-xs-4">Personnel</label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-9">
                            <select name="personnel" id="mod_affect_personnel" class="form-control">
                                <?php
                                foreach ($personnelsPlanning as $personnel):
                                    echo '<option value="' . $personnel->getPersonnelId() . '">' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom() . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mod_affect_chantier" class="col-lg-3 col-md-3 col-sm-4 col-xs-4">Chantier</label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-9">
                            <select name="chantier" id="mod_affect_chantier" class="form-control" data-width="100%" data-show-subtext="true">
                                <?php
                                if (!empty($liste_chantier)):
                                    foreach ($liste_chantier as $chantier):
                                        if ($chantier->getEtat() <> 'Termine'):
                                            ?>
                                            <option value="<?= $chantier->getId(); ?>"><?= $chantier->getClient() . ' - ' . $chantier->getChantierCategorie(); ?></option>
                                            <?php
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="modAffectAdresse" class="col-lg-3 col-md-3 col-sm-3 col-xs-4">Adresse</label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <input type="text" name="addAffectAdresse" id="modAffectAdresse" value="" class="form-control input-sm" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-md-3 col-sm-3 col-xs-3" for="modAffectCp">Code postal</label>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-9">
                            <input type="text" class="form-control input-sm" name="addAffectCp" id="modAffectCp" placeholder="Code postal" value="" />
                        </div>
                        <label class="col-lg-1 col-md-1 col-sm-1 col-xs-3 control-label" for="modAffectVille">Ville</label>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-9">
                            <select name="addAffectVille" id="modAffectVille" class="form-control input-sm">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mod_affect_debut" class="col-lg-3 col-md-3 col-sm-4 col-xs-12">Débute le</label>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-7">
                            <input type="date" name="debut" id="mod_affect_debut" class="form-control" value="" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-5">
                            <select name="demi" id="mod_affect_demi" class="form-control">
                                <option value="0">Matin</option>
                                <option value="1">Après-midi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mod_affect_nb_demi" class="col-lg-3 col-md-3 col-sm-4 col-xs-4">Pour</label>
                        <div class="input-group col-lg-4 col-md-4 col-sm-4 col-xs-7">
                            <select name="nb_demi" id="mod_affect_nb_demi" class="form-control">
                                <?php
                                for ($i = 1; $i < 91; $i++): echo '<option value="' . $i . '">' . $i . '</option>';
                                endfor;
                                ?>
                            </select>
                            <span class="input-group-addon">&frac12;j</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mod_affect_type" class="col-lg-3 col-md-3 col-sm-4 col-xs-4">Type</label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-9">
                            <select class="form-control" name="type" required id="mod_affect_type">
                                <option value="0">Chantier</option>
                                <option value="1">SAV - Dépannage</option>
                                <option value="2">Atelier</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mod_affect_commentaire" class="col-lg-3 col-md-3 col-sm-4 col-xs-4">Commentaire</label>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-9">
                            <textarea name="commentaire" rows="2" id="mod_affect_commentaire" class="form-control"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary"><i class="glyphicon glyphicon-repeat"> </i> Modifier</button>
                    <?= form_close(); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- div d'action sur affectation ---------------------------------------------- -->
    <div class="modal fade" id="action_affect" cible="" tabindex="-1" role="dialog" aria-labelledby="Visualisation et actions sur une affectation" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal-lg">
                <div class="modal-header" style="background: #428bca; color:#FFF;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <p style="font-size:14px;">
                        <i class="" id="isVolOiseau"></i><br/><span id="adresse_affectation"></span><br/>
                        <span id="distanceAffectation"></span> (<i class="glyphicon glyphicon-plane"></i> <span id="distanceAffectationVO"></span>)<br/>
                        <strong>Objet du chantier : </strong><span id="objetChantier_affectation"></span><br/>
                        <strong>type : </strong><span id="type_affectation"></span><br/>
                        <strong>Heures prévues pour ce chantier : </strong><span id="previsionnel_affectation"></span><br/>
                        <strong>Commentaire : </strong><span id="commentaire_affectation"></span>
                    </p>
                    <hr/>
                    <div class="form-group btnGroupActionAffect">
                        <?php echo form_open('planning/diviserAffectation', array('class' => 'form-inline', 'id' => 'formDiviseAffect')); ?>
                        <input type="hidden" name="diviseAffectId" id="diviseAffectId" value="" />

                        <label width="20%">Diviser au </label>
                        <input type="date" name="diviseAffectDate" id="diviseAffectDate" value="" class="form-control input-sm"/>
                        <select name="diviseAffectMoment" id="diviseAffectMoment" class="form-control input-sm">
                            <option value="0">Fin de matinée</option>
                            <option value="1">Soir</option>
                        </select>
                        <button type="submit" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-scissors"></i></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="" class="btn btn-sm btn-default pull-left" id="btnLinkChantier"><i class="glyphicon glyphicon-link"> </i> Fiche chantier</a>
                    <div class="row" id="loadLocalisation" style="position:absolute; left:300px; display: none;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                            <div class="circle"></div>
                            <div class="circle1"></div>
                        </div>
                    </div>
                    <div class="btn-group btnGroupActionAffect">
                        <button class="btn btn-sm btn-default" id="btnRelocaliserAffectation" cible=""><i class="glyphicon glyphicon-screenshot"> </i> Relocaliser</button>
                        <button class="btn btn-sm btn-info" id="btnModAffect" cible=""><i class="glyphicon glyphicon-pencil"> </i> Modifier</button>
                        <button class="btn btn-sm btn-danger pull-right" id="btn_remove_affect" cible=""><i class="glyphicon glyphicon-fire"> </i> Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour l'ajout d'une livraison fournisseur ----------------- -->
    <div class="modal fade" id="modalLivraison" cible="" tabindex="-1" role="dialog" aria-labelledby="Ajouter une livraison fournisseur" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #428bca; color:#FFF;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                    <?= form_open('planning/addLivraison/', array('class' => 'form-horizontal', 'id' => 'formAddLivraison')); ?>
                    <input type="hidden" name="addLivraisonId" id="addLivraisonId" value="" />
                    <div class="form-group">
                        <label for="addLivraisonDate" class="col-lg-3 col-md-3 col-sm-3 col-xs-4">Le</label>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                            <input type="date" name="addLivraisonDate" id="addLivraisonDate" value="" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addLivraisonChantier" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">Chantier</label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6">
                            <select name="addLivraisonChantier" id="addLivraisonChantier" class="superselect" data-width="100%" data-show-subtext="true">
                                <option value="0">Selectionnez un chantier...</option>
                                <?php
                                if (!empty($liste_chantier)):
                                    foreach ($liste_chantier as $chantier):
                                        if ($chantier->getEtat() <> 'Termine'):
                                            echo '<option value="' . $chantier->getId() . '" data-content="<span style=\'font-size:12px;\'>' . $chantier->getClient() . '</span> - <span style=\'color:blue;\'>' . $chantier->getChantierCategorie() . '</span><span style=\'font-weight:bold; color:grey; font-size:8px; position:relative; top:5px;\' class=\'pull-right\'>' . $chantier->getVille() . '</span>">' . $chantier->getClient() . ' | ' . $chantier->getVille() . '</option>';
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addLivraisonFournisseur" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">Founisseur</label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6">
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
                    <div class="form-group">
                        <label for="addLivraisonRemarque" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">Remarque</label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6">
                            <textarea class="form-control" name="addLivraisonRemarque" id="addLivraisonRemarque"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addLivraisonEtat" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">Avancement</label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6">
                            <select name="addLivraisonEtat" id="addLivraisonEtat" class="form-control" data-width="100%">
                                <option value="0">Attente</option>
                                <option value="1">Confirmée</option>
                                <option value="2">Reçue</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addLivraisonContrainte" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">Nécessaire pour</label>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6" id="addLivraisonListeContrainte">

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
                                        <td><?= $l->getLivraisonFournisseur() . '<br/>' . $l->getLivraisonFournisseurTelephone(); ?></td>
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
