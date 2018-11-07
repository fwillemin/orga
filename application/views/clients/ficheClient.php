<div class="row">
    <div class="col-12 col-lg-10 offset-lg-1 fond">
        <div class="row">
            <div class="col-12">
                <br>
                <h2>
                    <a href="<?= site_url('clients/liste'); ?>" style="text-decoration: none;">
                        <i class="fas fa-chevron-circle-left" style="color: grey;"></i>
                    </a>
                    <?= $client->getClientNom(); ?>
                    <button class="btn btn-link" id="btnModClient">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                    <?php if (empty($client->getClientAffaires())): ?>
                        <button class="btn btn-link" id="btnDelClient" style="color: lightgray;">
                            <i class="fas fa-trash"></i>
                        </button>
                    <?php endif;
                    ?>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div id="map" style="width:100%; height:200px; margin-bottom:10px; border:1px solid steelblue;"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div id="containerModClient" class="inPageForm" style="display: none; padding: 10px; margin-bottom:10px;">
                    <?php include('formClient.php'); ?>
                    <i class="formClose fas fa-times"></i>
                </div>
                <?php
                echo $client->getClientAdresse() . '<br>' . $client->getClientCp() . ' ' . $client->getClientVille();
                echo '<div class="row" style="margin-top:8px;"><div class="col-6 col-sm-3"><i class="fas fa-phone"></i> ' . ($client->getClientFixe() ?: '-') . '</div><div class="col-6 col-sm-3"><i class="fas fa-mobile-alt"></i> ' . ($client->getClientPortable() ?: '-') . '</div><div class="col-12 col-sm-6"><i class="fas fa-envelope"></i> <a href="mailto: ' . $client->getClientEmail() . '">' . $client->getClientEmail() . '</a></div></div>';
                ?>
                <br>
                <br>
                <h5>Places</h5>
                <table class="table table-sm style1" id="tablePlaces">
                    <thead>
                        <tr>
                            <td></td>
                            <td>Places</td>
                            <td style="text-align: right;">Distance</td>
                            <td style="text-align: right;">Durée</td>
                            <td style="text-align: center; width: 30px;"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        echo '<tr class="js-marker" data-latitude="' . explode(',', $this->session->userdata('etablissementGPS'))[0] . '" data-longitude="' . explode(',', $this->session->userdata('etablissementGPS'))[1] . '" data-text="BASE">'
                        . '<td><img src="' . base_url('assets/leaflet/images/marker-icon-red.png') . '" height="15"></td>'
                        . '<td colspan="3">Emplacement de votre établissement</td>'
                        . '<td style="text-align: right;"></td></tr>';

                        if (!empty($client->getClientPlaces())):
                            foreach ($client->getClientPlaces() as $pl):
                                $i++;
                                if ($pl->getPlaceId() == $this->uri->segment(4)):
                                    $style = 'class="ligneClikable ligneSelectionnee"';
                                else:
                                    $style = 'class="ligneClikable"';
                                endif;
                                echo '<tr class="js-marker" data-latitude="' . $pl->getPlaceLat() . '" data-longitude="' . $pl->getPlaceLon() . '" data-text="' . $i . '" data-placeid="' . $pl->getPlaceId() . '"' . $style . '>'
                                . '<td>' . $i . '</td>'
                                . '<td>' . $pl->getPlaceAdresse() . '</td>'
                                . '<td style="text-align: right;">' . round($pl->getPlaceDistance() / 1000, 2) . 'Km</td>'
                                . '<td style="text-align: right;">' . floor($pl->getPlaceDuree() / 60) . 'min</td>'
                                . '<td style="text-align: center;"><i class="fas fa-trash btnDelPlace" style="color: grey; cursor: pointer;"></i></td></tr>';
                            endforeach;

                        endif;
                        ?>
                    </tbody>
                </table>
                <?= form_open('clients/addPlace', array('id' => 'formAddPlace')); ?>
                <input type="hidden" name="addPlaceId" id="addPlaceId" value="<?= !empty($place) ? $place->getPlaceId() : ''; ?>">
                <input type="hidden" name="addPlaceClientId" id="addPlaceClientId" value="<?= $client->getclientId(); ?>">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Adresse" name="addPlaceAdresse" id="addPlaceAdresse" value="<?= !empty($place) ? $place->getPlaceAdresse() : ''; ?>">

                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="btnSubmitFormEquipe"><?= !empty($place) ? '<i class="fas fa-edit"></i>' : '<i class="fas fa-plus-square"></i>'; ?></button>
                        <?php if (!empty($place)): ?>
                            <a title="Quitter la fiche de ce taux" href="<?= site_url('clients/ficheClient/' . $client->getClientId()); ?>" class="btn btn-outline-dark" type="button"><i class="fas fa-times"></i></a>
                            <button class="btn btn-outline-danger" type="button" id="btnDelPlace"><i class="fas fa-trash"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
            <div class="col-12 col-sm-6">
                <h4>Affaires</h4>
                <table class="table table-sm style1" id="tableAffaires">
                    <thead>
                        <tr>
                            <td style="width: 160px;">Signé le</td>
                            <td>Objet</td>
                            <td style="text-align: right;">Prix</td>
                            <td style="text-align: center;">Etat</td>
                            <td style="text-align: center; width: 30px;"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($client->getClientAffaires())):
                            foreach ($client->getClientAffaires() as $affaire):
                                echo '<tr data-affaireid="' . $affaire->getAffaireId() . '">'
                                . '<td>' . $this->cal->dateFrancais($affaire->getAffaireDateSignature(), 'DMA') . '</td>'
                                . '<td>' . $affaire->getAffaireObjet() . '</td>'
                                . '<td style="text-align: right;">' . $affaire->getAffairePrix() . '€</td>'
                                . '<td style="text-align: center;">' . $affaire->getAffaireEtatHtml() . '</td>'
                                . '<td style="text-align: center;"><a href="' . site_url('affaires/ficheAffaire/' . $affaire->getAffaireId()) . '"><i class="fas fa-link" style="color: steelblue; cursor: pointer;" title="' . $affaire->getAffaireId() . '"></i></td>'
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