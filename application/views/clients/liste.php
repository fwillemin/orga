<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond" style="padding-top: 20px;">
        <h2>
            Liste de vos clients
            <button class="btn btn-link" id="btnAddClient">
                <i class="fas fa-plus-square"></i> Ajouter
            </button>
        </h2>
        <small><?= sizeof($clients); ?> clients</small>
        <table class="table table-bordered table-sm style1" id="tableClients" style="font-size:13px;">
            <thead>
                <tr>

                    <td>Nom</td>
                    <td>Adresse</td>
                    <td>Derniere</td>
                    <td style="text-align: center;">Fixe</td>
                    <td style="text-align: center;">Portable</td>
                    <td style="text-align: center; width:25px;">
                        <button class="btn btn-light btn-sm">
                            <i class="fas fa-link"></i>
                        </button>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($clients)):
                    foreach ($clients as $client):
                        echo '<tr class="ligneClikable" data-clientid="' . $client->getClientId() . '">'
                        . '<td>' . $client->getClientNom() . '</td>'
                        . '<td>' . $client->getClientVille() . '</td>'
                        . '<td style="text-align:center;">' . ($client->getClientLastAffaire() ? $this->cal->dateFrancais($client->getClientLastAffaire()->getAffaireCreation(), 'Am') : 'NC') . '</td>'
                        . '<td>' . $client->getClientFixe() . '</td>'
                        . '<td>' . $client->getClientPortable() . '</td>'
                        . '<td style="text-align: center;"><input type="checkbox" class="js-fusion"></td>'
                        . '</tr>';
                    endforeach;
                    unset($client);
                endif;
                ?>
            </tbody>
        </table>
        <br>
    </div>
</div>

<div class="modal fade" id="modalAddClient" data-show="<?= $this->uri->segment(3) == 'ajouter' ? 'true' : 'false'; ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php include('formClient.php'); ?>
            </div>
        </div>
    </div>
</div>