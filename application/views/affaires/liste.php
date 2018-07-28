<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond" style="padding-top: 20px;">

        <select class="form-control form-control-sm w-25" id="selectAffairesEtat" style="position: absolute; right:5px;">
            <option value="0" <?= $this->session->userdata('rechAffaireEtat') == '0' ? 'selected' : ''; ?> >Toutes</option>
            <option value="1" <?= $this->session->userdata('rechAffaireEtat') == '1' ? 'selected' : ''; ?> >Devis</option>
            <option value="2" <?= $this->session->userdata('rechAffaireEtat') == '2' ? 'selected' : ''; ?> >En cours</option>
            <option value="3" <?= $this->session->userdata('rechAffaireEtat') == '3' ? 'selected' : ''; ?> >Cloturées</option>
        </select>

        <h2>
            Affaires
            <button class="btn btn-link" id="btnAddAffaire">
                <i class="fas fa-plus-square"></i> Ajouter
            </button>
        </h2>
        <small><?= count($affaires) - 1; ?> affaires</small>
        <table class="table table-bordered table-responsive-sm table-sm style1" id="tableAffaires" style="font-size:13px; display: none; width:100%;">
            <thead>
                <tr>
                    <td>Client</td>
                    <td>Objet</td>
                    <td>Catégorie</td>
                    <td>Date</td>
                    <td>Etat</td>
                    <td style="text-align: right; width:60px;">Tarif</td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($affaires)):
                    foreach ($affaires as $affaire):
                        echo '<tr class="ligneClikable" data-affaireid="' . $affaire->getAffaireId() . '">'
                        . '<td>' . character_limiter($affaire->getAffaireClient()->getClientNom(), 30) . '</td>'
                        . '<td>' . character_limiter($affaire->getAffaireObjet(), 40) . '</td>'
                        . '<td style="color: slategray;">' . $affaire->getAffaireCategorie() . '</td>'
                        . '<td style="text-align: center;">' . $this->own->dateFrancais($affaire->getAffaireCreation(), 'Ma') . '</td>'
                        . '<td style="text-align: center;">' . $affaire->getAffaireEtatHtml() . '</td>'
                        . '<td style="text-align: right;">' . number_format($affaire->getAffairePrix(), 2, ',', ' ') . '</td>'
                        . '</tr>';
                    endforeach;
                    unset($affaire);
                endif;
                ?>
            </tbody>
        </table>
        <br>
    </div>
</div>

<div class="modal fade" id="modalAddAffaire" data-show="<?= $this->uri->segment(3) == 'ajouter' ? 'true' : 'false'; ?>">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter une Affaire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php include('formAffaire.php'); ?>
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
