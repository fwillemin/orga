<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond" style="padding-top: 20px;">

        <h2>
            Liste de vos horaires
            <button class="btn btn-link" id="btnAddHoraire">
                <i class="fas fa-plus-square"></i> Ajouter
            </button>
        </h2>
        <hr>
        <table class="table table-bordered table-sm style1" id="tableHoraires">
            <thead>
                <tr style="text-align: center;">
                    <td width="20%">Nom</td>
                    <td width="10%">Lundi</td>
                    <td width="10%">Mardi</td>
                    <td width="10%">Mercredi</td>
                    <td width="10%">Jeudi</td>
                    <td width="10%">Vendredi</td>
                    <td width="10%">Samedi</td>
                    <td width="10%">Dimanche</td>
                    <td width="10%">Total</td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($horaires)):
                    foreach ($horaires as $horaire):
                        echo '<tr class="ligneClikable" data-horaireid="' . $horaire->getHoraireId() . '">'
                        . '<td>' . $horaire->getHoraireNom() . '</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireLun() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireMar() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireMer() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireJeu() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireVen() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireSam() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireDim() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireTotal() . 'h</td>';
                        echo '</tr>';
                    endforeach;
                    unset($horaire);
                endif;
                ?>
            </tbody>
        </table>
        <br>
    </div>
</div>

<div class="modal fade" id="modalAddHoraire" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un horaire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php include('formHoraire.php'); ?>
            </div>
        </div>
    </div>
</div>