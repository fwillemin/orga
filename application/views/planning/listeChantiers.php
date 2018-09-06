<div id="listeChantiers">
    <img src="<?= base_url('assets/img/chantiersHandler.png'); ?>" style="position:absolute; right:0px; top:80px;" >
    <div style="position: absolute; bottom:2px; right:30px; font-size:10px; color:grey; text-align: right;">
        <?= sizeof($affairesPlanning) . ' Affaires'; ?>
    </div>
    <div class="row">
        <button type="button" class="btn btn-default btn-sm btn-link" id="toggleCalendar">
            Calendrier
        </button>
        <div style="position: absolute; top:2px; right:30px; font-size:10px; color:grey; text-align: right;">
            <?= $this->session->userdata('parametres')['nbSemainesAvant'] . ' <i class="fas fa-chevron-left"></i> ' . $this->cal->dateFrancais($this->own->mktimeFromInputDate($dateFocus)) . '  <i class="fas fa-chevron-right"></i> ' . $this->session->userdata('parametres')['nbSemainesApres']; ?>
        </div>
        <div class="col-12" id="datepicker-container" data-date="<?= $dateFocus; ?>">
            <div data-date="<?= $dateFocus; ?>"></div>
        </div>
        <div class="col-12" style="text-align: center; position:relative; top:13px;">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" id="toogleTermines" <?= $this->session->userdata('inclureTermines') ? 'checked' : ''; ?> value="1" >
                    </div>
                </div>
                <input type="text" class="form-control" disabled value="Afficher les chantiers terminés" style="font-size:13px;">
            </div>
        </div>
        <div class="col-12" style="text-align: center; margin-bottom:10px;">
            <div class="input-group input-group-sm mb3">
                <input type="text" class="form-control input-sm" id="searchClientAffaire" value="" placeholder="Recherche client" >
                <div class="input-group-append">
                    <button class="btn btn-sm btn-default" id="resetSearchClientAffaire">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-12" style="padding:0px; overflow-y: scroll; max-height: 680px; overflow-x: hidden;">
            <table class="table table-sm" id="tableSlideChantiers" style="font-size:11px; overflow-y: scroll; max-height: 680px; overflow-x: hidden;">
                <?php
                if (!empty($affairesPlanning)):
                    foreach ($affairesPlanning as $affaire):
                        ?>
                        <tr class="js-slideLigneAffaire" data-affaireid="<?= $affaire->getAffaireId(); ?>">
                            <td colspan="2" style="background-color: <?= $affaire->getAffaireCouleur(); ?>; color: <?= $affaire->getAffaireCouleurSecondaire(); ?>; width: 20px;">
                                <?php
                                if ($affaire->getAffaireEtat() == 3):
                                    echo '<i class="fas fa-lock" style="color:' . $affaire->getAffaireCouleurSecondaire() . ';"></i>';
                                endif;
                                ?>
                            </td>
                            <td class="slideLigneAffaire">
                                <a href="<?= site_url('affaires/ficheAffaire/' . $affaire->getAffaireId()); ?>" style="">
                                    <span style="font-weight: bold;">
                                        <?= strtoupper($affaire->getAffaireClient()->getClientNom()); ?>
                                    </span>
                                    <br><?= $affaire->getAffaireObjet(); ?>
                                </a>
                                <small class="medium slide"><?= $affaire->getAffaireClient()->getClientVille(); ?></small><br>
                            </td>
                        </tr>

                        <?php
                        if (!empty($affaire->getAffaireChantiers())):
                            foreach ($affaire->getAffaireChantiers() as $chantier):
                                ?>
                                <tr data-chantierid="<?= $chantier->getChantierId(); ?>" data-affaireParent="<?= $affaire->getAffaireId(); ?>">
                                    <td style="background-color: <?= $affaire->getAffaireCouleur(); ?>; width: 20px;">
                                        <?php
                                        if ($chantier->getChantierEtat() == 2):
                                            echo '<i class="fas fa-lock" style="color:' . $affaire->getAffaireCouleurSecondaire() . ';"></i>';
                                        endif;
                                        ?>
                                    </td>
                                    <td style="background-color: <?= $chantier->getChantierCouleur(); ?>; color: <?= $affaire->getAffaireCouleurSecondaire(); ?>; width: 20px; text-align: right;">
                                        <i class="fas fa-chevron-circle-right" style="color: <?= $chantier->getChantierCouleurSecondaire(); ?>"></i>
                                    </td>
                                    <td class="slideLigneChantier">
                                        <a href="<?= site_url('chantiers/ficheChantier/' . $chantier->getChantierId()); ?>">
                                            <?= $chantier->getChantierObjet(); ?>
                                        </a>
                                        <?php
                                        $ratio = round($chantier->getChantierHeuresPlanifiees() * 100 / $chantier->getChantierHeuresPrevues());
                                        if ($ratio > 100):
                                            $bgClass = "progress-bar bg-danger";
                                            $ratio = 100;
                                        elseif ($ratio > 75):
                                            $bgClass = "progress-bar bg-warning";
                                        else:
                                            $bgClass = "progress-bar bg-info";
                                        endif;
                                        ?>
                                        <div class="progress">
                                            <div class="<?= $bgClass; ?>" role="progressbar" style="width: <?= $ratio; ?>%" aria-valuenow="<?= $ratio; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                    endforeach;
                endif;
                ?>
            </table>
        </div>
    </div>
</div>