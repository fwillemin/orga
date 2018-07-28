<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond" style="padding-top: 20px;">
        <h2>
            Gestion de vos équipes
        </h2>
        <hr>
        <div class="row">
            <div class="col-5">
                <?= form_open('personnels/addEquipe', array('id' => 'formAddEquipe')); ?>
                <input type="hidden" name="addEquipeId" id="addEquipeId" value="<?= !empty($equipe) ? $equipe->getEquipeId() : ''; ?>">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Nom d'équipe" name="addEquipeNom" id="addEquipeNom"  value="<?= !empty($equipe) ? $equipe->getEquipeNom() : ''; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="btnSubmitFormEquipe"><?= !empty($equipe) ? '<i class="fas fa-edit"></i>' : '<i class="fas fa-plus-square"></i>'; ?></button>
                        <?php if (!empty($equipe)): ?>
                            <a title="Quitter la fiche de cette équipe" href="<?= site_url('personnels/equipes/'); ?>" class="btn btn-outline-dark" type="button"><i class="fas fa-times"></i></a>
                            <button class="btn btn-outline-danger" type="button" id="btnDelEquipe"><i class="fas fa-trash"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
                <?= form_close(); ?>
                <h4>Liste des équipes</h4>
                <table class="table table-sm style1" id="tableEquipes">
                    <thead>
                        <tr>
                            <td>Equipes</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($equipes)):
                            foreach ($equipes as $eq):
                                if ($eq->getEquipeId() == $this->uri->segment(3)):
                                    $style = 'class="ligneClikable ligneSelectionnee"';
                                else:
                                    $style = 'class="ligneClikable"';
                                endif;
                                echo '<tr data-equipeid="' . $eq->getEquipeId() . '"' . $style . '><td>' . $eq->getEquipeNom() . '</td></tr>';
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>

            <div class = "col-6 offset-1">
                <table class="table table-sm style1" id="tableEquipesPersonnels">
                    <thead>
                        <tr>
                            <td style="text-align: center; width: 80px;">Equipe</td>
                            <td style="text-align: center; width: 30px;"></td>
                            <td>Nom</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($personnels)):
                            foreach ($personnels as $personnel):
                                $action = $badge = '';
                                if ($personnel->getPersonnelEquipeId()):
                                    $badge = '<label class="badge badge-info">' . $personnel->getPersonnelEquipe()->getEquipeNom() . '</label>';
                                endif;
                                if ($equipe && ($personnel->getPersonnelEquipeId() == $equipe->getEquipeId() || !$personnel->getPersonnelEquipeId())):
                                    $action = '<input type="checkbox" class="affectEquipe"' . ($personnel->getPersonnelEquipeId() ? 'checked' : '') . '>';
                                endif;

                                echo '<tr data-personnelid="' . $personnel->getPersonnelId() . '">'
                                . '<td style="text-align: center;">' . $badge . '</td>'
                                . '<td style="text-align: center;">' . $action . '</td>'
                                . '<td>' . $personnel->getPersonnelNom() . ' ' . $personnel->getPersonnelPrenom() . '</td>'
                                . '</tr>';
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
