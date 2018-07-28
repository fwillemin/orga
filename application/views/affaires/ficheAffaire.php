<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond">
        <div class="row">
            <div class="col-12" style="margin-bottom: 30px;">
                <br>
                <h2>
                    <a href="<?= site_url('affaires/liste'); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?php
                    $client = $affaire->getAffaireClient();
                    echo $client->getClientNom() . '<span style="color:grey; font-size:12px; position: absolute; top:60px; left: 55px;">[Affaire ' . $affaire->getAffaireId() . ']</span> ';
                    ?>
                    <h4 style="margin-top: 15px;"><?= $affaire->getAffaireObjet(); ?></h4>
                </h2>
                <div style="position : absolute; bottom: 5px; right: 10px; text-align: right; font-size:14px;">
                    <?= $affaire->getAffairePlace() ? $affaire->getAffairePlace()->getPlaceAdresse() : '<small class="danger">Aucune place renseignée</small>'; ?>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#listeChantiersAffaire"><i class="fas fa-list"></i> Chantiers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#infosAffaire"><i class="far fa-question-circle"></i> Informations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#analyseAffaire"><i class="fas fa-chart-pie"></i> Analyse</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#modAffaire"><i class="fas fa-edit"></i> Modifier</a>
            </li>

        </ul>

        <div class="tab-content">

            <div class="inPageForm tab-pane" id="modAffaire" style="padding: 10px; margin-bottom:10px;">
                <?php include('formAffaire.php'); ?>
                <i class="formClose fas fa-times"></i>
            </div>

            <div class="tab-pane cadre2" id="infosAffaire">
                <div class="row">
                    <div class="col-12 col-md-8">
                        Commercial de l'affaire : <?= $affaire->getAffaireCommercial() ? $affaire->getAffaireCommercial()->getUserPrenom() . ' ' . $affaire->getAffaireCommercial()->getUserNom() : '<small class="light">Non attribué</small>'; ?>
                        <br>Tarif : <?= number_format($affaire->getAffairePrix(), 2, ',', '') . '€ HT'; ?>
                        <br>Liée au devis : <?= $affaire->getAffaireDevis(); ?>
                        <hr>
                        Signée le <?= $affaire->getAffaireDateSignature() ? $this->own->dateFrancais($affaire->getAffaireDateSignature()) : '<small class="light">--</small>'; ?>
                        <br>Etat : <?= $affaire->getAffaireEtatHtml(); ?>
                        <br>Clôturée le <?= $affaire->getAffaireDateCloture() ? $this->own->dateFrancais($affaire->getAffaireDateCloture()) : '<small class="light">--</small>'; ?>
                        <hr>
                        <?= nl2br($affaire->getAffaireRemarque()); ?>
                    </div>
                    <div class = "col-12 col-md-4" style = "padding-right:5px;">
                        <?php if ($affaire->getAffairePlace()):
                            ?>
                            <place class="js-marker" data-latitude="<?= $affaire->getAffairePlace()->getPlaceLat(); ?>" data-longitude="<?= $affaire->getAffairePlace()->getPlaceLon(); ?>" data-text="Affaire"></place>
                            <place class="js-marker" data-latitude="<?= explode(',', $this->session->userdata('etablissementGPS'))[0]; ?>" data-longitude="<?= explode(',', $this->session->userdata('etablissementGPS'))[1]; ?>" data-text="BASE"></place>
                            <div id="map" style="width:100%; height:250px; border:1px solid white;"></div>
                            <small>
                                Distance : <?= ceil($affaire->getAffairePlace()->getPlaceDistance() / 1000) . 'km'; ?>
                                <br>Temps de trajet : <?= ceil($affaire->getAffairePlace()->getPlaceDuree() / 60) . 'min'; ?>
                                <br>Zone de déplacement : <?= $affaire->getAffairePlace()->getPlaceZone(); ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane show active" id="listeChantiersAffaire" style="padding:10px;">
                <div class="col">
                    <?php if ($affaire->getAffaireEtat() < 3 && $this->ion_auth->in_group(54)): ?>
                        <button class="btn btn-outline-primary btn-sm" id="btnAddChantier" style="position: absolute; right: 5px;">
                            <i class="fas fa-plus-square"></i> Ajouter un chantier
                        </button>
                    <?php endif; ?>
                    <h3>Chantiers</h3>
                    <div class="row">
                        <?php
                        if (!empty($affaire->getAffaireChantiers())):
                            foreach ($affaire->getAffaireChantiers() as $chantier):
                                ?>
                                <div class="col-12 col-md-6" style="padding: 5px 10px;">
                                    <div class="cadre js-linkChantier" data-chantierid="<?= $chantier->getChantierId(); ?>">
                                        <div class="affectation" style="margin: 0px 0px 5px 0px; color: <?= $chantier->getChantierCouleurSecondaire(); ?>; border-color: <?= $chantier->getChantierCouleurSecondaire(); ?>; background-color: <?= $chantier->getChantierCouleur(); ?>;">
                                            <strong><?= $chantier->getChantierObjet(); ?></strong>
                                            <br><small style="position: relative; top:-6px;"><?= $chantier->getChantierCategorie(); ?></small>
                                        </div>
                                        <div class="row" style="border-top:1px dashed grey; font-size:12px;">
                                            <div class="col">
                                                <h5>Temps<small> (heures)</small></h5>
                                                Prévues : <?= $chantier->getChantierHeuresPrevues(); ?>
                                                <br>Réalisées :
                                                <br>Planifiées :
                                            </div>
                                            <div class="col">
                                                <h5>Achats</h5>
                                                Budget : <?= $chantier->getChantierBudgetAchats(); ?>
                                                <br>Consommé :
                                                <br>Prévisionnel :
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                            unset($chantier);
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="analyseAffaire" style="padding: 10px; margin-bottom:10px;">
                <h3>Analyse</h3>
                <?php
                if (!$this->ion_auth->in_group(array(52))) :
                    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i> Vous n\'avez pas les droits nécéssaires pour consulter cette analyse.</divs>';
                endif;
                ?>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalAddChantier" data-show="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Chantier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php include('formChantier.php'); ?>
            </div>
        </div>
    </div>
</div>

<!--Ajout d'un client directement dans le formulaire d'ajout d'une affaire-->
<div class="modal fade" id="modalAddClient" data-show="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php include('application/views/clients/formClient.php'); ?>
            </div>
        </div>
    </div>
</div>


<!--Un formulaire similaire d'ajout de place cohabite pour l'ajout de place dans le formulaire d'ajout de chantier
Ajout de "Affaire" à la fin des id des champs pour les différencier-->
<div class="modal fade" id="modalAddPlaceAffaire" data-show="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une place (depuis une affaire)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open('clients/addPlace', array('id' => 'formAddPlaceAffaire')); ?>
                <input type="hidden" name="addPlaceId" id="addPlaceIdAffaire" value="">
                <input type="hidden" name="addPlaceClientId" id="addPlaceClientIdAffaire" value="">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Adresse" name="addPlaceAdresse" id="addPlaceAdresseAffaire" value="">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="btnSubmitFormPlaceAffaire">
                            <i class="fas fa-plus-square"></i>
                        </button>
                        <i class="fas fa-circle-notch fa-spin formloader" id="loaderAddPlaceAffaire"></i>
                    </div>
                </div>
                <?= form_close(); ?>
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
                <input type="hidden" name="addPlaceClientId" id="addPlaceClientIdChantier" value="<?= $affaire->getAffaireClient()->getClientId(); ?>">
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