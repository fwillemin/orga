<div class="row">
    <<div class="col-12 col-lg-8 offset-lg-2 fond">
        <div class="row">
            <div class="col-12">
                <br>
                <h2>
                    <a href="<?= site_url('horaires/liste'); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?= $horaire->getHoraireNom(); ?>
                    <button class="btn btn-link" id="btnModHoraire">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                </h2>
                Total hebdomdaire : <strong><?= $horaire->getHoraireTotal() . ' heures'; ?></strong>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-8">

                <div id="containerModHoraire" class="inPageForm" style="display: none; padding: 10px;">
                    <?php include('formHoraire.php'); ?>
                    <i class="formClose fas fa-times"></i>
                </div>


                <table class="table table-bordered table-sm style1">
                    <thead>
                        <tr style="text-align: center;">
                            <td width="14%">Lundi</td>
                            <td width="14%">Mardi</td>
                            <td width="14%">Mercredi</td>
                            <td width="14%">Jeudi</td>
                            <td width="14%">Vendredi</td>
                            <td width="14%">Samedi</td>
                            <td width="14%">Dimanche</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        echo '<tr>'
                        . '<td style="text-align:center;">'
                        . substr($horaire->getHoraireLun1(), 0, 5) . ' à ' . substr($horaire->getHoraireLun2(), 0, 5) . '<br>'
                        . substr($horaire->getHoraireLun3(), 0, 5) . ' à ' . substr($horaire->getHoraireLun4(), 0, 5)
                        . '</td>'
                        . '<td style="text-align:center;">'
                        . substr($horaire->getHoraireMar1(), 0, 5) . ' à ' . substr($horaire->getHoraireMar2(), 0, 5) . '<br>'
                        . substr($horaire->getHoraireMar3(), 0, 5) . ' à ' . substr($horaire->getHoraireMar4(), 0, 5)
                        . '</td>'
                        . '<td style="text-align:center;">'
                        . substr($horaire->getHoraireMer1(), 0, 5) . ' à ' . substr($horaire->getHoraireMer2(), 0, 5) . '<br>'
                        . substr($horaire->getHoraireMer3(), 0, 5) . ' à ' . substr($horaire->getHoraireMer4(), 0, 5)
                        . '</td>'
                        . '<td style="text-align:center;">'
                        . substr($horaire->getHoraireJeu1(), 0, 5) . ' à ' . substr($horaire->getHoraireJeu2(), 0, 5) . '<br>'
                        . substr($horaire->getHoraireJeu3(), 0, 5) . ' à ' . substr($horaire->getHoraireJeu4(), 0, 5)
                        . '</td>'
                        . '<td style="text-align:center;">'
                        . substr($horaire->getHoraireVen1(), 0, 5) . ' à ' . substr($horaire->getHoraireVen2(), 0, 5) . '<br>'
                        . substr($horaire->getHoraireVen3(), 0, 5) . ' à ' . substr($horaire->getHoraireVen4(), 0, 5)
                        . '</td>'
                        . '<td style="text-align:center;">'
                        . substr($horaire->getHoraireSam1(), 0, 5) . ' à ' . substr($horaire->getHoraireSam2(), 0, 5) . '<br>'
                        . substr($horaire->getHoraireSam3(), 0, 5) . ' à ' . substr($horaire->getHoraireSam4(), 0, 5)
                        . '</td>'
                        . '<td style="text-align:center;">'
                        . substr($horaire->getHoraireDim1(), 0, 5) . ' à ' . substr($horaire->getHoraireDim2(), 0, 5) . '<br>'
                        . substr($horaire->getHoraireDim3(), 0, 5) . ' à ' . substr($horaire->getHoraireDim4(), 0, 5)
                        . '</td>';
                        echo '</tr>';
                        echo '<tr>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireLun() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireMar() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireMer() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireJeu() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireVen() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireSam() . 'h</td>'
                        . '<td style="text-align:center;">' . $horaire->getHoraireDim() . 'h</td>';
                        echo '</tr>';
                        ?>

                    </tbody>
                </table>


            </div>
            <div class = "col-12 col-sm-4">
                <h5>Ils sont sur cet horaire :</h5>
                <table class = "table table-sm ">
                    <?php
                    if (!empty($personnels)):
                        foreach ($personnels as $personnel):
                            echo '<tr><td>' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom() . '</td><td>' . $personnel->getPersonnelQualif() . '</td></tr>';
                        endforeach;
                    endif;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
