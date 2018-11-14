<div class="row">
    <div class="col-12 col-lg-10 offset-lg-1 fond">
        <div class="row" id="enteteAffaire">
            <div class="col-12" style="margin-bottom: 5px;">
                <div class="row">
                    <div class="col" style="font-weight: bold; font-size: 18px;">
                        <span style="position: relative; left: -20px; top: -3px;">
                            <?= $affaire->getAffaireEtatHtml(); ?>
                        </span>
                    </div>
                    <div class="col" style="text-align: right; font-weight: bold; font-size: 18px; color: goldenrod;">
                        AFFAIRE
                    </div>
                </div>
                <h2>
                    <a href="<?= site_url('affaires/liste'); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?php
                    $client = $affaire->getAffaireClient();
                    echo $client->getClientNom();
                    ?>
                </h2>
                <h4 style="margin-top: 15px;"><?= $affaire->getAffaireObjet(); ?></h4>
                <div style="position : absolute; bottom: 0px; right: 10px; text-align: right; font-size:14px;">
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
            <?php if ($affaire->getAffaireId() != $this->session->userdata('affaireDiversId')) : ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#deleteAffaire"><i class="fas fa-trash"></i></a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="tab-content">

            <div class="inPageForm tab-pane" id="modAffaire" style="padding: 10px; margin-bottom:10px;">
                <?php
                if ($affaire->getAffaireId() == $this->session->userdata('affaireDiversId')):
                    include('formAffaireDivers.php');
                else:
                    include('formAffaire.php');
                endif;
                ?>
                <i class="formClose fas fa-times"></i>
            </div>

            <div class="tab-pane" id="deleteAffaire" style="border-left: 4px solid red; font-size:14px; padding:10px;">
                <?php if ($this->ion_auth->in_group(57)): ?>
                    <i class="fas fa-sad-tear" style="font-size:40px; color: #900b22;"></i>
                    <br><br><span style="color: #900b22; font-size: 15px; font-weight: bold;">Supprimer une affaire est irréversible.</span>
                    <br>Voici les actions qui se produiront à la suppression de l'affaire :
                    <ul>
                        <li>Suppression de tous les chantiers</li>
                        <li>Suppression de toutes les affectations et des documents liés aux chantiers</li>
                        <li>Suppression des livraisons fournisseurs</li>
                        <li>Suppression de toutes les heures saisies et donc des fiches de pointages</li>
                        <li>Suppression de tous les achats</li>
                        <li>Recalcul des analyses de votre activité</li>
                    </ul>
                    <button type="button" id="btnDelAffaire" class="btn btn-secondary">
                        <i class="fas fa-trash"></i> Supprimer définitivement l'affaire
                    </button>
                    <?php
                else:
                    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i> Vous n\'avez pas les droits nécéssaires pour supprimer une affaire</div>';
                endif;
                ?>
            </div>

            <div class="tab-pane" id="infosAffaire">
                <?php
                include('tabInfos.php');
                ?>
            </div>

            <div class="tab-pane show active" id="listeChantiersAffaire" style="padding:10px;">
                <?php
                include('tabChantiers.php');
                ?>
            </div>

            <div class="tab-pane" id="analyseAffaire" style="padding: 10px; margin-bottom:10px;">
                <?php
                include('tabAnalyse.php');
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
                <?php include('application/views/chantiers/formChantier.php'); ?>
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