<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond">
        <div class="row">
            <div class="col-12" style="margin-bottom: 30px;">
                <br>
                <h2>
                    Paramètres
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-8">
                Les modifications des paramètres ne sont pas rétroactives.
                <table class="table table-bordered table-sm style1" id="tableHoraires">
                    <thead>
                        <tr>
                            <td style="width:">Paramètres</td>
                            <td style="width:160px; text-align: center;"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?= form_open('', array('id' => 'formModParametres')); ?>
                        <tr>
                            <td>
                                <strong>Tranche pointage</strong>
                                <br>Défini en minutes le temps minimum lors d'un pointage et le mutliple de ce temps.
                                <br>Ex: 5 minutes donnera un pointage possible de 5/10/15/20.... minutes
                                <br>Ex: 15 minutes donnera un pointage possible de 15/30/45/1h/....
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <select name="tranchePointage" id="tranchePointage" class="form-control form-control-sm" style="text-align: right;">
                                        <option value="5" <?= $this->session->userdata('parametres')['tranchePointage'] == 5 ? 'selected' : ''; ?> >5</option>
                                        <option value="10" <?= $this->session->userdata('parametres')['tranchePointage'] == 10 ? 'selected' : ''; ?>>10</option>
                                        <option value="15" <?= $this->session->userdata('parametres')['tranchePointage'] == 15 ? 'selected' : ''; ?>>15</option>
                                        <option value="20" <?= $this->session->userdata('parametres')['tranchePointage'] == 20 ? 'selected' : ''; ?>>20</option>
                                        <option value="30" <?= $this->session->userdata('parametres')['tranchePointage'] == 30 ? 'selected' : ''; ?>>30</option>
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text">minutes</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Planning | Semaines avant</strong>
                                <br>Permet de choisir le nombre de semaines passées visibles au planning
                                <br>Plus le nombre de semaine est important, plus le temps de chargement peut être long
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <select name="nbSemainesAvant" id="nbSemainesAvant" class="form-control form-control-sm" style="text-align: right;">
                                        <?php
                                        for ($i = 1; $i <= 25; $i++):
                                            echo '<option value="' . $i . '"' . ($this->session->userdata('parametres')['nbSemainesAvant'] == $i ? 'selected' : '') . '>' . $i . '</option>';
                                        endfor;
                                        ?>
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text">semaines</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Planning | Semaines après</strong>
                                <br>Permet de choisir le nombre de semaines visibles après la dernière affectation.
                                <br>Plus le nombre de semaine est important, plus le temps de chargement peut être long
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <select name="nbSemainesApres" id="nbSemainesApres" class="form-control form-control-sm" style="text-align: right;">
                                        <?php
                                        for ($i = 1; $i <= 10; $i++):
                                            echo '<option value="' . $i . '"' . ($this->session->userdata('parametres')['nbSemainesApres'] == $i ? 'selected' : '') . '>' . $i . '</option>';
                                        endfor;
                                        ?>
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text">semaines</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Taille des affectations</strong>
                                <br>Les affectations peuvent être plus ou moins grandes sur le planning.
                                <br>A essayer...
                            </td>
                            <td>
                                <select name="tailleAffectations" id="tailleAffectations" class="form-control form-control-sm" style="text-align: right;">
                                    <option value="1" <?= ($this->session->userdata('parametres')['tailleAffectations'] == 1) ? 'selected' : ''; ?>>Petites</option>
                                    <option value="2" <?= ($this->session->userdata('parametres')['tailleAffectations'] == 2) ? 'selected' : ''; ?>>Standards</option>
                                    <option value="3" <?= ($this->session->userdata('parametres')['tailleAffectations'] == 3) ? 'selected' : ''; ?>>Grandes</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Taux de frais généraux</strong>
                                <br>Ce taux de frais généraux sera appliqué par défaut lors de la création d'un chantier.
                                <br>Il reste modifiable au moment de la création du chantier.
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input style="text-align: right;" type="text" class="form-control form-control-sm" name="TFG" value="<?= $this->session->userdata('etablissementTFG'); ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Taux horaire moyen</strong>
                                <br>Ce taux est utilisé pour calculé les marges et la rentabilité estimée d'une affaire et de ses chantiers avant leur réalisation
                                <br>Il reste modifiable au moment de la création d'un chantier.
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input style="text-align: right;" type="text" class="form-control form-control-sm" name="THM" value="<?= $this->session->userdata('etablissementTHM'); ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">€ /heure</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Générer les paniers dans les feuilles de pointages</strong>
                                <br>Calcul du déclenchement des paniers. Si la valeur est à "NON", les cases "PANIER" resteront vides lors de la génération des feuilles de pointage.
                            </td>
                            <td>
                                <select name="genererPaniers" id="genererPaniers" class="form-control form-control-sm" style="text-align: right;">
                                    <option value="1" <?= $this->session->userdata('parametres')['genererPaniers'] == 1 ? 'selected' : ''; ?> >Oui</option>
                                    <option value="0" <?= $this->session->userdata('parametres')['genererPaniers'] == 0 ? 'selected' : ''; ?> >Non</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                            </td>
                        </tr>
                        <?= form_close(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
