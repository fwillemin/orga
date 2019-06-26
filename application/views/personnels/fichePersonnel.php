<div class="row">
    <div class="col-12 col-xl-10 offset-xl-1 fond">
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
        <div class="row" style="margin-bottom:60px;">
            <div class="col-12 col-md-5">
                <div id="containerModPersonnel" class="inPageForm" style="display: none; padding: 10px; margin-bottom:10px;">
                    <?php include('formPersonnel.php'); ?>
                    <i class="formClose fas fa-times"></i>
                </div>
                <?=
                $personnel->getPersonnelActif() ? '<label class="badge badge-info">Actif</label>' : '<label class="badge badge-secondary">Inactif</label>';
                switch ($personnel->getPersonnelType()):
                    case 1:
                        echo ' <label class="badge badge-primary">Salarié</label>';
                        break;
                    case 2:
                        echo ' <label class="badge badge-warning">Apprenti</label>';
                        break;
                    case 3:
                        echo ' <label class="badge badge-dark">Intérimaire</label>';
                        break;
                endswitch;
                ?>
                <strong><?= $personnel->getPersonnelQualif(); ?></strong>
                <br>Taux horaire actuel : <strong><?= ($personnel->getPersonnelTauxHoraire() ?: '<span class="badge badge-warning">NR</span>') . ' <small>€/h</small>'; ?></strong>
                <br><span style="font-size:14px;">Horaire d'entreprise : <?= $personnel->getPersonnelHoraireId() ? $personnel->getPersonnelHoraire()->getHoraireNom() : 'Aucun horaire appliqué'; ?>
                    <br>Feuilles de pointages : <?= $personnel->getPersonnelPointages() == 1 ? 'Au réél des heures pointées' : 'Selon l\'horaire attribué'; ?>
                    <br>Portable : <?= $personnel->getPersonnelPortable(); ?></span>
                <br><br>
                <h5>Heures supplémentaires</h5>
                <table class="table table-sm table-bordered style1">
                    <thead>
                        <tr>
                            <th>Solde (heures)</th>
                            <th>Générer un rapport</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" style="font-size:20px; font-weight:bold; color:steelblue;"> <?= ($personnel->getPersonnelSoldeRTT() ?: 0); ?></td>
                            <td>
                                <div class="input-group">
<!--                                    <select name="pointageMois" id="rttReportMois" class="form-control form-control-sm">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                                    <option value="<?= $i; ?>" <?php if ($i == date('m')) echo 'selected'; ?> ><?= $i; ?></option>
                                    <?php endfor; ?>
                                    </select>-->
                                    <select name="pointageAnnee" id="rttReportAnnee" class="form-control form-control-sm">
                                        <?php for ($i = date('Y'); $i >= 2018; $i--): ?>
                                            <option value="<?= $i; ?>" <?php if ($i == date('Y')) echo 'selected'; ?> ><?= $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-danger btn-sm" type="button" id="btnGenereRTTReport"><i class="far fa-file-pdf"></i></button>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>
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
            <div class="col-12 col-md-7" style=" padding:0px 30px 15px 30px;">
                <div class="row" style="background-color: #f4f4fb; border:2px solid #293042;">
                    <div class="col-12" style="background-color: #293042; color: white; padding-top:5px;">
                        <div class="input-group">
                            <label for="changeAnalysePersonnelAnnee" class="col-12 col-sm-7 text-right"><span style="font-size:20px; font-weight:bold;">Analyses de <?= $personnel->getPersonnelPrenom(); ?> pour l'année</span></label>
                            <select class="form-control form-control-sm col-12 col-sm-2" id="changeAnalysePersonnelAnnee">
                                <?php
                                for ($i = date('Y'); $i >= '2013'; $i--):
                                    echo '<option value="' . $i . '"' . ($this->session->userdata('analysePersonnelsAnnee') == $i ? 'selected' : '') . '>' . $i . '</option>';
                                endfor;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-3" style="">
                        <table class="table table-sm table-bordered style1" style="font-size:10px; margin-top:5px; background-color: #FFF;">
                            <thead>
                                <tr><th>Motifs</th><th style="text-align: right;">Jours</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                $dataIndispo = $dataLabels = '';
                                foreach ($indispos as $motif):
                                    echo '<tr><td>' . $motif->motifNom . '</td><td align="right">' . $motif->nbJours . '</td></tr>';
                                    if (in_array($motif->motifId, array(10, 11, 12, 4, 6, 9, 14, 1, 7, 8, 13))):
                                        if ($dataIndispo != ''):
                                            $dataIndispo .= ',';
                                            $dataLabels .= ',';
                                        endif;
                                        $dataIndispo .= $motif->nbJours > 0 ? $motif->nbJours : 'NaN';
                                        $dataLabels .= $motif->motifNom;
                                    endif;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class = "col-8" style = "margin-top:15px;">
                        <canvas id = "graphRepartitionIndispos" width = "400" height = "300" js-indispos = "<?= $dataIndispo; ?>" js-labels="<?= $dataLabels; ?>"></canvas>
                    </div>
                    <div class = "col-12" style = "margin-top:15px; border-top: 1px solid lightgray; padding-top:20px;">
                        <?php
                        $perfs = $perfsLabels = '';
                        foreach ($performances as $key => $value):
                            if ($perfs != ''):
                                $perfs .= ',';
                                $perfsLabels .= ',';
                            endif;
                            $perfsLabels .= $key;
                            $perfs .= $value;
                        endforeach;
                        ?>
                        <a href="<?= site_url('personnels/exportPerformancesPersonnel/' . $personnel->getPersonnelId()); ?>" target="_blank" class="btn btn-link btn-sm" style = "position: absolute; bottom:5px; right:5px;">
                            <i class = "far fa-file-excel"></i> Exporter les données
                        </a>
                        <canvas id = "graphPerformances" width = "400" height = "170" js-labels = "<?= $perfsLabels; ?>" js-performances = "<?= $perfs; ?>"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>