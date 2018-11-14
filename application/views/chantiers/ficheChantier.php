<div class="row">
    <div class="col-12 col-lg-10 offset-md-1 fond">
        <div class="row" id="enteteAffaire">
            <div class="col-12" style="margin-bottom: 5px; border-bottom: 1px dashed white; opacity: 0.7;">
                <div class="row" style="font-weight: bold; font-size: 14px;">
                    <div class="col-1">
                        <span style="position: relative; left: -20px; top: -3px;">
                            <?= $affaire->getAffaireEtatHtml(); ?>
                        </span>
                    </div>
                    <div class="col">
                        <?php
                        $client = $chantier->getChantierClient();
                        echo $client->getClientNom() . ' - ' . $affaire->getAffaireObjet();
                        ?>
                    </div>
                    <div class="col-1" style="text-align: right; font-weight: bold; color: goldenrod;">
                        AFFAIRE
                    </div>
                </div>
            </div>

            <div class="col-12" style="margin-bottom: 5px;">
                <div class="row">
                    <div class="col" style="font-weight: bold; font-size: 18px;">
                        <span style="position: relative; left: -20px; top: -3px;">
                            <?= $chantier->getChantierEtatHtml(); ?>
                        </span>
                    </div>
                    <div class="col" style="text-align: right; font-weight: bold; font-size: 18px; color: gold;">
                        CHANTIER
                    </div>
                </div>
                <h2>
                    <a href="<?= site_url('affaires/ficheAffaire/' . $affaire->getAffaireId()); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?= $chantier->getChantierObjet(); ?>
                </h2>
                <div style="position : absolute; bottom: 0px; right: 10px; text-align: right; font-size:14px;">
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
                <a class="nav-link" id="tabAnalyse" data-toggle="tab" href="#analyseChantier"><i class="fas fa-chart-pie"></i> Analyse</a>
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
                if (!$this->ion_auth->in_group(54)):
                    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i> Vous n\'avez pas les droits nécéssaires pour modifier un chantier.</div>';
                elseif ($chantier->getChantierEtat() == 2):
                    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i> Impossible de modifier un chantier clôturé.</div>';
                else:
                    include('formChantier.php');
                endif;
                ?>
            </div>

            <div class="tab-pane active show" id="resumeChantier">
                <?php include('tabResume.php'); ?>
            </div>

            <div class="tab-pane" id="heuresChantier">
                <?php include('tabHeures.php'); ?>
            </div>

            <div class="tab-pane" id="deleteChantier" style="border-left: 4px solid red; font-size:14px; padding:10px;">

                <?php
                if (!$this->ion_auth->in_group(58)):
                    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i> Vous n\'avez pas les droits nécéssaires pour supprimer un chantier.</div>';
                elseif ($chantier->getChantierEtat() == 2):
                    echo '<div class="alert alert-danger"><i class="fas fa-ban"></i> Impossible de supprimer un chantier clôturé.</div>';
                else:
                    ?>

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
                endif;
                ?>
            </div>

            <div class="tab-pane" id="achatsChantier" style="padding:10px;">
                <?php include('tabAchats.php'); ?>
            </div>

            <div class="tab-pane" id="analyseChantier" style="padding: 10px; margin-bottom:10px;">
                <?php include('tabAnalyse.php'); ?>
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

