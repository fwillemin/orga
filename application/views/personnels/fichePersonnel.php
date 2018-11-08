<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond">
        <div class="row">
            <div class="col-12">
                <br>
                <h2>
                    <a href="<?= site_url('personnels/liste'); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?= $personnel->getPersonnelPrenom() . ' ' . $personnel->getPersonnelNom(); ?>
                    <button class="btn btn-link" id="btnModPersonnel">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-5">
                <div id="containerModPersonnel" class="inPageForm" style="display: none; padding: 10px; margin-bottom:10px;">
                    <?php include('formPersonnel.php'); ?>
                    <i class="formClose fas fa-times"></i>
                </div>
                <?= $personnel->getPersonnelActif() ? '<label class="badge badge-info">Actif</label>' : '<label class="badge badge-secondary">Inactif</label>'; ?> <strong><?= $personnel->getPersonnelQualif(); ?></strong>
                <br>Taux horaire actuel : <strong><?= ($personnel->getPersonnelTauxHoraire() ?: '<span class="badge badge-warning">NR</span>') . ' <small>€/h</small>'; ?></strong>
                <br><span style="font-size:14px;">Horaire d'entreprise : <?= $personnel->getPersonnelHoraireId() ? $personnel->getPersonnelHoraire()->getHoraireNom() : 'Aucun horaire appliqué'; ?>
                    <br>Feuilles de pointages : <?= $personnel->getPersonnelPointages() == 1 ? 'Au réél des heures pointées' : 'Selon l\'horaire attribué'; ?></span>
                <br>
                <br>
                <h5>Taux horaires</h5>
                <?= form_open('personnels/addTauxHoraire', array('id' => 'formAddTauxHoraire')); ?>
                <input type="hidden" name="addTauxHoraireId" id="addTauxHoraireId" value="<?= !empty($tauxHoraire) ? $tauxHoraire->getTauxHoraireId() : ''; ?>">
                <input type="hidden" name="addTauxHorairePersonnelId" id="addTauxHorairePersonnelId" value="<?= $personnel->getpersonnelId(); ?>">
                <div class="input-group mb-3 input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="">A compter du</span>
                    </div>
                    <input type="date" class="form-control col-6" name="addTauxHoraireDate" id="addTauxHoraireDate" value="<?= !empty($tauxHoraire) ? date('Y-m-d', $tauxHoraire->getTauxHoraireDate()) : ''; ?>">
                    <input type="text" class="form-control col-3" placeholder="Taux horaire" name="addTauxHoraire" id="addTauxHoraire" value="<?= !empty($tauxHoraire) ? $tauxHoraire->getTauxHoraire() : ''; ?>" style="text-align: right;">

                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="btnSubmitFormEquipe"><?= !empty($tauxHoraire) ? '<i class="fas fa-edit"></i>' : '<i class="fas fa-plus-square"></i>'; ?></button>
                        <?php if (!empty($tauxHoraire)): ?>
                            <a title="Quitter la fiche de ce taux" href="<?= site_url('personnels/fichePersonnel/' . $personnel->getPersonnelId()); ?>" class="btn btn-outline-dark" type="button"><i class="fas fa-times"></i></a>
                            <button class="btn btn-outline-danger" type="button" id="btnDelTauxHoraire"><i class="fas fa-trash"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
                <?= form_close(); ?>
                <table class="table table-sm style1" id="tableTauxHoraires">
                    <thead>
                        <tr>
                            <td>Date</td>
                            <td style="text-align: right;">Taux horaire</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($personnel->getPersonnelTauxHoraireHistorique())):
                            foreach ($personnel->getPersonnelTauxHoraireHistorique() as $th):
                                if ($th->getTauxHoraireId() == $this->uri->segment(4)):
                                    $style = 'class="ligneClikable ligneSelectionnee"';
                                else:
                                    $style = 'class="ligneClikable"';
                                endif;
                                echo '<tr data-tauxhoraireid="' . $th->getTauxHoraireId() . '"' . $style . '><td>' . $this->cal->dateFrancais($th->getTauxHoraireDate()) . '</td>'
                                . '<td style="text-align: right;">' . $th->getTauxHoraire() . '</td></tr>';
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-sm-6">

            </div>
        </div>
    </div>
</div>