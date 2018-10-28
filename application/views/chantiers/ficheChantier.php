<div class="row">
    <div class="col-12 col-lg-10 offset-md-1 fond">
        <div class="row">
            <div class="col-12" style="margin-bottom: 30px;">
                <br>
                <h2>
                    <a href="<?= site_url('affaires/ficheAffaire/' . $affaire->getAffaireId()); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?php
                    $client = $chantier->getChantierClient();
                    echo '<small class="danger">Chantier : </small>' . $chantier->getChantierObjet() . '<span style="color:grey; font-size:12px; position: absolute; top:60px; left: 55px;">[Affaire ' . $affaire->getAffaireId() . '] [client : ' . $client->getClientNom() . ']</span> ';
                    ?>
                </h2>
                <div style="position : absolute; bottom: 5px; right: 10px; text-align: right; font-size:14px;">
                    <?= $chantier->getChantierPlace() ? $chantier->getChantierPlace()->getPlaceAdresse() : '<small class="danger">Aucune place renseignée</small>'; ?>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs" id="tabsChantier">
            <li class="nav-item">
                <a class="nav-link active" id="taBoard" data-toggle="tab" href="#resumeChantier"><i class="fas fa-file"></i> Board</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabHeures" data-toggle="tab" href="#heuresChantier"><i class="fas fa-clock"></i> Heures</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabAchats" data-toggle="tab" href="#achatsChantier"><i class="fas fa-piggy-bank"></i> Achats</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#analyseChantier"><i class="fas fa-chart-pie"></i> Analyse</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#modChantier"><i class="fas fa-edit"></i> Modifier</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#deleteChantier"><i class="fas fa-trash"></i></a>
            </li>
        </ul>

        <div class="tab-content">

            <div class="inPageForm tab-pane" id="modChantier" style="padding: 10px; margin-bottom:10px;">
                <?php
                if ($this->ion_auth->in_group(54)):
                    include('formChantier.php');
                else:
                    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i> Vous n\'avez pas les droits nécéssaires pour modifier un chantier.</div>';
                endif;
                ?>
            </div>

            <div class="tab-pane show active" id="resumeChantier">
                <br>
                <?php if ($chantier->getChantierEtat() == 1): ?>
                    <button class="btn btn-info" id="btnClotureChantier" <?= $this->ion_auth->in_group(array(54)) ? '' : 'disabled'; ?> >
                        <i class="fas fa-lock"></i> Clôturer ce chantier
                    </button>
                    <?php
                else:
                    echo '<h4>Chantier clôturé le ' . $this->cal->dateFrancais($chantier->getChantierDateCloture()) . '</h4>';
                    echo '<button class="btn btn-warning" id="btnReouvertureChantier" ' . ($this->ion_auth->in_group(array(54)) ? '' : 'disabled') . '>'
                    . '<i class="fas fa-key"></i> Réouvrir ce chantier'
                    . '</button>';
                endif;
                ?>

                <br>
                <br>
                <br>
                <span style="font-size:13px;">
                    Catégorie : <?= $chantier->getChantierCategorie(); ?>
                    <br>Chiffrage : <?= number_format($chantier->getChantierPrix(), 2, ',', ' ') . '€ HT'; ?>
                </span>
            </div>

            <div class="tab-pane" id="heuresChantier">

                <div class="row">
                    <div class="col-3">

                        <table class="table table-sm">
                            <tr>
                                <td>Heures prévues</td>
                                <td><?= $chantier->getChantierHeuresPrevues(); ?></td>
                            </tr>
                            <tr>
                                <td>Heures planifiées</td>
                                <td><?= $chantier->getChantierHeuresPlanifiees(); ?></td>
                            </tr>
                            <tr>
                                <td>Heures pointées</td>
                                <td><?= $chantier->getChantierHeuresPointees(); ?></td>
                            </tr>
                        </table>

                    </div>
                    <div class="col-7">
                        <table class="table table-sm style1">
                            <?php
                            if (!empty($chantier->getChantierAffectations())):
                                foreach ($chantier->getChantierAffectations() as $affectation):

                                endforeach;
                            endif;
                            ?>
                        </table>
                    </div>
                </div>

            </div>

            <div class="tab-pane" id="deleteChantier" style="border-left: 4px solid red; font-size:14px; padding:10px;">

                <?php if ($this->ion_auth->in_group(58)): ?>

                    <i class="fas fa-sad-tear" style="font-size:40px; color: #900b22;"></i>
                    <br><br><span style="color: #900b22; font-size: 15px; font-weight: bold;">Supprimer un chantier est irréversible. Cela affecte toute l'affaire.</span>
                    <br>Voici les actions qui se produiront à la suppression du chantier :
                    <ul>
                        <li>Suppression de toutes les affectations et des documents liés</li>
                        <li>Suppression de toutes les heures saisies et donc des fiches de pointages</li>
                        <li>Suppression de tous les achats</li>
                        <li>Recalcul des analyses de l'affaire (marges, rentabilités, ... )</li>
                        <li>Suppression des livraisons fournisseurs</li>
                    </ul>
                    <button type="button" id="btnDelChantier" class="btn btn-secondary">
                        <i class="fas fa-trash"></i> Supprimer définitivement le chantier
                    </button>
                    <?php
                else:
                    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i> Vous n\'avez pas les droits nécéssaires pour supprimer un chantier.</div>';
                endif;
                ?>
            </div>

            <div class="tab-pane" id="achatsChantier" style="padding:10px;">

                Budget achats de ce chantier : <?= number_format($chantier->getChantierBudgetAchats(), 2, ',', ' ') . '€'; ?>
                <br>Budget prévu : <?= number_format($chantier->getChantierBudgetPrevu(), 2, ',', ' ') . '€'; ?>
                <br>Budget consommé : <?= number_format($chantier->getChantierBudgetConsomme(), 2, ',', ' ') . '€'; ?>
                <hr>

                <?php if ($this->ion_auth->in_group(55)): ?>

                    <button class = "btn btn-link" id = "btnAddAchat">
                        <i class = "fas fa-edit"></i> Ajouter un achat
                    </button>

                    <div id="containerAddAchat" class ="inPageForm col-md-12 col-lg-7" style = "padding:3px; <?= !empty($achat) ? '' : 'display: none;' ?>">
                        <?php include('formAchat.php'); ?>
                        <i class="formClose fas fa-times"></i>
                    </div>
                    <br>
                <?php endif; ?>
                <table class="table table-sm style1" id="tableAchats">
                    <thead>
                        <tr>
                            <td rowspan="2" style="width: 120px;">Date</td>
                            <td rowspan="2" style="width: 350px;">Description</td>
                            <td colspan="3" style="border-left:1px solid lightgrey; text-align: center;">Prévisionnel</td>
                            <td colspan="3" style="border-left:1px solid lightgrey; border-right:1px solid lightgrey; text-align: center;">Réel</td>
                            <td rowspan="2" style="width: 50px;"></td>
                            <td colspan="3" style="border-left:1px solid lightgrey; text-align: center;">Livraison</td>
                        </tr>
                        <tr>
                            <td style="width:60px; border-left:1px solid lightgrey; text-align: right;">Qte</td>
                            <td style="width:80px; text-align: right;">Prix</td>
                            <td style="width:80px; text-align: right;">Total</td>
                            <td style="width:60px; border-left:1px solid lightgrey; text-align: right;">Qte</td>
                            <td style="width:80px; text-align: right;">Prix</td>
                            <td style="width:80px; text-align: right;">Total</td>
                            <td style="width:120px; border-left:1px solid lightgrey;">Fournisseur</td>
                            <td style="width:80px;">Date</td>
                            <td style="width:90px;">Avancement</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($chantier->getChantierAchats())):
                            foreach ($chantier->getChantierAchats() as $a):
                                if (!empty($achat) && $a->getAchatId() == $achat->getAchatId()):
                                    $style = 'class="ligneClikable ligneSelectionnee"';
                                elseif ($this->ion_auth->in_group(55)):
                                    $style = 'class="ligneClikable"';
                                else:
                                    $style = '';
                                endif;
                                if ($a->getAchatTotal() > $a->getAchatTotalPrevisionnel()):
                                    $statColor = 'red';
                                else:
                                    $statColor = 'green';
                                endif;

                                echo '<tr data-achatid="' . $a->getAchatId() . '"' . $style . '>'
                                . '<td>' . $this->cal->dateFrancais($a->getAchatDate(), 'DmA') . '</td>'
                                . '<td>' . $a->getAchatDescription() . '</td>'
                                . '<td style="text-align: right; border-left: 1px solid black;">' . $a->getAchatQtePrevisionnel() . '</td>'
                                . '<td style="text-align: right;">' . $a->getAchatPrixPrevisionnel() . '</td>'
                                . '<td style="text-align: right;">' . $a->getAchatTotalPrevisionnel() . '</td>'
                                . '<td style="text-align: right; border-left: 1px solid black;">' . $a->getAchatQte() . '</td>'
                                . '<td style="text-align: right;">' . $a->getAchatPrix() . '</td>'
                                . '<td style="text-align: right; border-right: 1px solid black;">' . $a->getAchatTotal() . '</td>'
                                . '<td style="text-align: right; color:' . $statColor . ';">' . ($a->getAchatTotalPrevisionnel() > 0 ? floor($a->getAchatTotal() / $a->getAchatTotalPrevisionnel() * 100) : '-' ) . '%</td>'
                                . '<td style="border-left: 1px solid black;">' . (!is_null($a->getAchatFournisseur()) ? $a->getAchatFournisseur()->getFournisseurNom() : '-') . '</td>'
                                . '<td>' . $this->cal->dateFrancais($a->getAchatLivraisonDate(), 'Dma') . '</td>'
                                . '<td style="border-right: 1px solid black;">' . $a->getAchatLivraisonAvancementText() . '</td>'
                                . '</tr>';

                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane" id="analyseChantier" style="padding: 10px; margin-bottom:10px;">
                <h3>Analyse</h3>
                <?php
                if (!$this->ion_auth->in_group(array(56))) :
                    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i> Vous n\'avez pas les droits nécéssaires pour consulter cette analyse.</div>';
                endif;
                ?>
            </div>

        </div>
    </div>
</div>


<!--Un formulaire similaire d'ajout de place cohabite pour l'ajout de place dans le formulaire de modification d'affaire
Ajout de "Chantier" à la fin des id des champs pour les différencier-->
<div class="modal fade" id="modalAddPlaceChantier" data-show="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une place (depuis un chantier)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open('clients/addPlace', array('id' => 'formAddPlaceChantier')); ?>
                <input type="hidden" name="addPlaceId" id="addPlaceIdChantier" value="">
                <input type="hidden" name="addPlaceClientId" id="addPlaceClientIdChantier" value="<?= $chantier->getChantierClient()->getClientId(); ?>">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Adresse" name="addPlaceAdresse" id="addPlaceAdresseChantier" value="">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="btnSubmitFormPlaceChantier">
                            <i class="fas fa-plus-square"></i>
                        </button>
                        <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddPlaceChantier"></i>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

