<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond" style="padding-top: 20px;">
        <h2>
            Liste de vos fournisseurs
            <button class="btn btn-link" id="btnAddFournisseur">
                <i class="fas fa-plus-square"></i> Ajouter
            </button>
        </h2>
        <hr>
        <table class="table table-bordered table-sm style1" id="tableFournisseurs" style="font-size:13px;">
            <thead>
                <tr>
                    <td>Nom</td>
                    <td>Adresse</td>
                    <td style="text-align: center;">Téléphone</td>
                    <td style="text-align: center;">Email</td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($fournisseurs)):
                    foreach ($fournisseurs as $fournisseur):
                        echo '<tr class="ligneClikable" data-fournisseurid="' . $fournisseur->getFournisseurId() . '">'
                        . '<td>' . $fournisseur->getFournisseurNom() . '</td>'
                        . '<td>' . $fournisseur->getFournisseurAdresse() . ' ' . $fournisseur->getFournisseurVille() . '</td>'
                        . '<td>' . $fournisseur->getFournisseurTelephone() . '</td>'
                        . '<td>' . $fournisseur->getFournisseurEmail() . '</td>'
                        . '</tr>';
                    endforeach;
                    unset($fournisseur);
                endif;
                ?>
            </tbody>
        </table>
        <br>
    </div>
</div>

<?php if ($this->ion_auth->in_group(array(70))): ?>
    <div class="modal fade" id="modalAddFournisseur" data-show="<?= $this->uri->segment(3) == 'ajouter' ? 'true' : 'false'; ?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un fournisseur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <?php include('formFournisseur.php'); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>