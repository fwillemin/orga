<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond" style="padding-top: 20px;">
        <a class="btn btn-outline-info" href="<?= site_url('personnels/equipes'); ?>" style="position: absolute; right: 5px;">
            <i class="fas fa-sitemap"></i> Gérer les équipes
        </a>
        <h2>
            Liste de votre personnel d'intervention
            <button class="btn btn-link" id="btnAddPersonnel">
                <i class="fas fa-plus-square"></i> Ajouter
            </button>
        </h2>
        <hr>
        <table class="table table-bordered table-sm style1" id="tablePersonnels" style="font-size:13px;">
            <thead>
                <tr>
                    <td style="width: 30px;"></td>
                    <td>Nom</td>
                    <td style="width:50px;">Type</td>
                    <td>Qualification</td>
                    <td>Horaire</td>
                    <td style="text-align: center;">T. Horaire</td>
                    <td style="text-align: center;">Equipe</td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($personnels)):
                    foreach ($personnels as $personnel):
                        switch ($personnel->getPersonnelType()):
                            case 1:
                                $type = '<label style="font-size:11px;" class="badge badge-primary">Salarié</label> ';
                                break;
                            case 2:
                                $type = '<label style="font-size:11px;" class="badge badge-warning">Apprenti</label> ';
                                break;
                            case 3:
                                $type = '<label style="font-size:11px;" class="badge badge-dark">Intérimaire</label> ';
                                break;
                        endswitch;

                        echo '<tr class="ligneClikable" data-personnelid="' . $personnel->getPersonnelId() . '">'
                        . '<td style="text-align:center; font-size:15px;">' . ($personnel->getPersonnelActif() ? '<label class="badge badge-info">Actif</label>' : '<label class="badge badge-secondary">Inactif</label>') . '</td>'
                        . '<td>' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom() . '</td>'
                        . '<td>' . $type . '</td>'
                        . '<td>' . $personnel->getPersonnelQualif() . '</td>'
                        . '<td style="text-align: center;">' . (!empty($personnel->getPersonnelHoraire()) ? $personnel->getPersonnelHoraire()->getHoraireNom() : '<span class="badge badge-warning">NR</span>') . '</td>'
                        . '<td style="text-align: center;">' . ($personnel->getPersonnelTauxHoraire() ?: '<span class="badge badge-warning">NR</span>') . '</td>'
                        . '<td style="text-align: center;">' . ($personnel->getPersonnelEquipeId() ? $personnel->getPersonnelEquipe()->getEquipeNom() : '') . '</td>'
                        . '</tr>';
                    endforeach;
                    unset($personnel);
                endif;
                ?>
            </tbody>
        </table>
        <br>
    </div>
</div>

<div class="modal fade" id="modalAddPersonnel" data-show="<?= $this->uri->segment(3) == 'ajouter' ? 'true' : 'false'; ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un personnel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php include('formPersonnel.php'); ?>
            </div>
        </div>
    </div>
</div>