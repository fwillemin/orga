<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond">
        <div class="row">
            <div class="col-12" style="margin-bottom: 30px;">
                <br>
                <h2>
                    <a href="<?= site_url('affaires/ficheAffaire/' . $affaire->getAffaireId()); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?php
                    $client = $chantier->getChantierClient();
                    echo '<small class=light>Chantier : </small>' . $chantier->getChantierObjet() . '<span style="color:grey; font-size:12px; position: absolute; top:60px; left: 55px;">[Affaire ' . $affaire->getAffaireId() . '] [client : ' . $client->getClientNom() . ']</span> ';
                    ?>
                </h2>
                <div style="position : absolute; bottom: 5px; right: 10px; text-align: right; font-size:14px;">
                    <?= $chantier->getChantierPlace() ? $chantier->getChantierPlace()->getPlaceAdresse() : '<small class="danger">Aucune place renseignée</small>'; ?>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs" id="tabsChantier">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#resumeChantier"><i class="fas fa-file"></i> Board</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#heuresChantier"><i class="fas fa-clock"></i> Heures</a>
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
                <span style="font-size:13px;">
                    Catégorie : <?= $chantier->getChantierCategorie(); ?>
                    <br>Chiffrage : <?= number_format($chantier->getChantierPrix(), 2, ',', ' ') . '€ HT'; ?>
                    <br>Frais généraux : <?= $chantier->getChantierFraisGeneraux() . '%'; ?>
                    <br>Taux horaire moyen : <?= $chantier->getChantierTauxHoraireMoyen() . '€/h'; ?>
                </span>
            </div>

            <div class="tab-pane" id="heuresChantier">

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

            <div class = "tab-pane" id = "achatsChantier" style = "padding:10px;">

                Budget achats de ce chantier : <?= number_format($chantier->getChantierBudgetAchats(), 2, ',', ' ') . '€'; ?>
                <br>Budget prévu : <?= number_format($chantier->getChantierBudgetPrevu(), 2, ',', ' ') . '€'; ?>
                <br>Budget consommé : <?= number_format($chantier->getChantierBudgetConsomme(), 2, ',', ' ') . '€'; ?>
                <hr>

                <?php if ($this->ion_auth->in_group(55)): ?>

                    <button class = "btn btn-link" id = "btnAddAchat">
                        <i class = "fas fa-edit"></i> Ajouter un achat
                    </button>

                    <div id = "containerAddAchat" class = "inPageForm" style = "padding:3px; <?= !empty($achat) ? '' : 'display: none;' ?>">
                        <?= form_open('chantiers/addAchat', array('id' => 'formAddAchat'));
                        ?>
                        <input type="hidden" name="addAchatId" id="addAchatId" value="<?= !empty($achat) ? $achat->getAchatId() : ''; ?>">
                        <input type="hidden" name="addAchatChantierId" id="addAchatChantierId" value="<?= $chantier->getChantierId(); ?>">
                        <span class="badge badge-danger js-onAchatMod" style="<?= !$this->uri->segment(4) ? 'display:none;' : ''; ?>">
                            Modification d'un achat en cours...
                        </span>
                        <div class="form-row" style="margin-top: 4px;">
                            <div class="col-3">
                                <label for="addAchatDate">Date</label>
                                <input type="date" class="form-control form-control-sm" id="addAchatDate" name="addAchatDate" value="<?= !empty($achat) ? date('Y-m-d', $achat->getAchatDate()) : date('Y-m-d'); ?>">
                            </div>
                            <div class="col-3">
                                <label for="addAchatType">Type</label>
                                <select name="addAchatType" id="addAchatType" class="form-control form-control-sm">
                                    <option value="1" <?= !empty($achat) && $achat->getAchatType() == 1 ? 'selected' : ''; ?>>Matière première</option>
                                    <option value="2" <?= !empty($achat) && $achat->getAchatType() == 2 ? 'selected' : ''; ?>>Matériel</option>
                                    <option value="3" <?= !empty($achat) && $achat->getAchatType() == 3 ? 'selected' : ''; ?>>Outillage</option>
                                    <option value="4" <?= !empty($achat) && $achat->getAchatType() == 4 ? 'selected' : ''; ?>>Sous-traitance</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="addAchatDescription">Description</label>
                                <input type="text" class="form-control form-control-sm" id="addAchatDescription" name="addAchatDescription" placeholder="Description de l'achat" value="<?= !empty($achat) ? $achat->getAchatDescription() : ''; ?>">
                            </div>
                        </div>
                        <div class="form-row" style="margin-top: 4px;">
                            <div class="col">
                                <label for="addAchatQtePrevisionnel">Prévisionnel</label>
                                <div class="input-group input-group-sm">
                                    <input type="numeric" class="form-control form-control-sm text-right" id="addAchatQtePrevisionnel" name="addAchatQtePrevisionnel" placeholder="Qté" value="<?= !empty($achat) ? $achat->getAchatQtePrevisionnel() : ''; ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">[QTE] x [PRIX]</span>
                                    </div>
                                    <input type="numeric" class="form-control form-control-sm text-right" id="addAchatPrixPrevisionnel" name="addAchatPrixPrevisionnel" placeholder="Prix HT" value="<?= !empty($achat) ? $achat->getAchatPrixPrevisionnel() : ''; ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">=</span>
                                    </div>
                                    <input type="numeric" class="form-control form-control-sm text-right" id="addAchatTotalPrevisionnel" placeholder="Total prévisionnel" value="<?= !empty($achat) ? $achat->getAchatTotalPrevisionnel() : ''; ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-row" style="margin-top: 4px;">
                            <div class="col">
                                <label for="addAchatQte">Réel</label>
                                <div class="input-group input-group-sm">
                                    <input type="numeric" class="form-control form-control-sm text-right" id="addAchatQte" name="addAchatQte" placeholder="Qté" value="<?= !empty($achat) ? $achat->getAchatQte() : ''; ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">[QTE] x [PRIX]</span>
                                    </div>
                                    <input type="numeric" class="form-control form-control-sm text-right" id="addAchatPrix" name="addAchatPrix" placeholder="Prix HT" value="<?= !empty($achat) ? $achat->getAchatPrix() : ''; ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">=</span>
                                    </div>
                                    <input type="numeric" class="form-control form-control-sm text-right" id="addAchatTotal" placeholder="Total réel" value="<?= !empty($achat) ? $achat->getAchatTotal() : ''; ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-link js-onAchatMod" id="btnDelAchat" style="<?= !$this->uri->segment(4) ? 'display:none;' : ''; ?> color: red; position: absolute; bottom: 0px;">
                            <i class="fas fa-trash"></i>
                        </button>
                        <center>
                            <button type="submit" class="btn btn-outline-primary btn-sm" style="margin:5px;" id="btnSubmitFormAchat">
                                <?= !empty($achat) ? '<i class="fas fa-edit"></i> Modifier' : '<i class="fas fa-plus-square"></i> Ajouter'; ?>
                            </button
                            <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddAchat"></i>
                        </center>

                        <?= form_close(); ?>
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
                        </tr>
                        <tr>
                            <td style="width:80px; border-left:1px solid lightgrey; text-align: right;">Qte</td>
                            <td style="width:80px; text-align: right;">Prix</td>
                            <td style="width:80px; text-align: right;">Total</td>
                            <td style="width:80px; border-left:1px solid lightgrey; text-align: right;">Qte</td>
                            <td style="width:80px; text-align: right;">Prix</td>
                            <td style="width:80px; text-align: right;">Total</td>
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
                                . '<td>' . $this->cal->dateFrancais($a->getAchatDate(), 'DMA') . '</td>'
                                . '<td>' . $a->getAchatDescription() . '</td>'
                                . '<td style=" text-align: right; border-left: 1px solid black;">' . $a->getAchatQtePrevisionnel() . '</td>'
                                . '<td style=" text-align: right;">' . $a->getAchatPrixPrevisionnel() . '</td>'
                                . '<td style=" text-align: right;">' . $a->getAchatTotalPrevisionnel() . '</td>'
                                . '<td style=" text-align: right; border-left: 1px solid black;">' . $a->getAchatQte() . '</td>'
                                . '<td style=" text-align: right;">' . $a->getAchatPrix() . '</td>'
                                . '<td style=" text-align: right; border-right: 1px solid black;">' . $a->getAchatTotal() . '</td>'
                                . '<td style="text-align: right; color:' . $statColor . ';">' . ($a->getAchatTotalPrevisionnel() > 0 ? floor($a->getAchatTotal() / $a->getAchatTotalPrevisionnel() * 100) : '-' ) . '%</td>'
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

