<div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 fond" style="padding-top: 20px;">

        <select class="form-control form-control-sm w-25" id="selectAffairesEtat" style="position: absolute; right:5px;">
            <option value="0" <?= $this->session->userdata('rechAffaireEtat') == '0' ? 'selected' : ''; ?> >Toutes</option>
            <option value="1" <?= $this->session->userdata('rechAffaireEtat') == '1' ? 'selected' : ''; ?> >Devis</option>
            <option value="2" <?= $this->session->userdata('rechAffaireEtat') == '2' ? 'selected' : ''; ?> >En cours</option>
            <option value="3" <?= $this->session->userdata('rechAffaireEtat') == '3' ? 'selected' : ''; ?> >Cloturées</option>
        </select>

        <h2>
            Affaires
            <button class="btn btn-link" id="btnAddAffaires">
                <i class="fas fa-plus-square"></i> Ajouter
            </button>
        </h2>
        <small><?= count($affaires) - 1; ?> affaires</small>
        <table class="table table-bordered table-sm style1" id="tableAffaires" style="font-size:13px;">
            <thead>
                <tr>
                    <td>Client</td>
                    <td>Objet</td>
                    <td>Catégorie</td>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un Affaire</h5>
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