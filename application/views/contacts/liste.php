<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond" style="padding-top: 20px;">

        <select class="form-control form-control-sm w-25" id="selectContactsEtat" style="position: absolute; right:5px;">
            <option value="0" <?= $this->session->userdata('rechContactEtat') == '0' ? 'selected' : ''; ?> >Tous</option>
            <option value="1" <?= $this->session->userdata('rechContactEtat') == '1' ? 'selected' : ''; ?> >Non traité</option>
            <option value="2" <?= $this->session->userdata('rechContactEtat') == '2' ? 'selected' : ''; ?> >Sans suite</option>
            <option value="3" <?= $this->session->userdata('rechContactEtat') == '3' ? 'selected' : ''; ?> >Devis fait</option>
            <option value="4" <?= $this->session->userdata('rechContactEtat') == '4' ? 'selected' : ''; ?> >Conclu</option>
            <option value="5" <?= $this->session->userdata('rechContactEtat') == '5' ? 'selected' : ''; ?> >Perdu</option>
        </select>

        <h2>
            Contacts entrants
            <button class="btn btn-link" id="btnAddContact">
                <i class="fas fa-plus-square"></i> Ajouter
            </button>
        </h2>
        <small><?= count($contacts) - 1; ?> contacts</small>
        <table class="table table-bordered table-responsive-sm table-sm style1" id="tableContacts" style="font-size:13px; width:100%;">
            <thead>
                <tr>
                    <td>Date</td>
                    <td>Coordonnées</td>
                    <td>Objet</td>
                    <td style="width:150px;">Etat</td>
                    <td style="width: 60px;"></td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($contacts)):
                    foreach ($contacts as $contact):
                        echo '<tr class="ligneClikable" data-contactid="' . $contact->getContactId() . '">'
                        . '<td><b>' . $this->cal->dateFrancais($contact->getContactDate(), 'jDma') . '</b><br>' . $contact->getContactModeText() . '<br>' . $contact->getContactSourceText() . '</td>'
                        . '<td>' . $contact->getContactNom() . '<br>' . $contact->getContactAdresse() . ', ' . $contact->getContactVille() . '<br>Téléphone : ' . $contact->getContactTelephone() . '<br>' . $contact->getContactEmail() . '</td>'
                        . '<td><em>' . $contact->getContactCategorie()->getCategorieNom() . '</em><br>' . $contact->getContactObjet() . '</td>'
                        . '<td style="text-align: center;"><select class="selectpicker changeContactEtat">'
                        . '<option value="1" ' . ($contact->getContactEtat() == 1 ? 'selected' : '') . ' data-content="<span class=\'badge badge-light\'>Non traité</span>">Non traité</option>'
                        . '<option value="2" ' . ($contact->getContactEtat() == 2 ? 'selected' : '') . ' data-content="<span class=\'badge badge-warning\'>Sans suite</span>">Sans suite</option>'
                        . '<option value="3" ' . ($contact->getContactEtat() == 3 ? 'selected' : '') . ' data-content="<span class=\'badge badge-info\'>Devis</span>">Devis</option>'
                        . '<option value="4" ' . ($contact->getContactEtat() == 4 ? 'selected' : '') . ' data-content="<span class=\'badge badge-success\'>Conclu</span>">Conclu</option>'
                        . '<option value="5" ' . ($contact->getContactEtat() == 5 ? 'selected' : '') . ' data-content="<span class=\'badge badge-danger\'>Perdu</span>">Perdu</option>'
                        . '</select></td>'
                        . '<td>'
                        . '<button type="button" class="btn btn-xs btn-link btnEditContact" style="float: left;"><i class="fas fa-edit"></i></button>'
                        . '<button type="button" class="btn btn-xs btn-link btnDelContact" style="colorhdkfred"><i class="fas fa-trash"></i></button>'
                        . '</td>'
                        . '</tr>';
                    endforeach;
                    unset($contact);
                endif;
                ?>
            </tbody>
        </table>
        <br>
    </div>
</div>

<div class="modal fade" id="modalAddContact" data-show="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un contact entrant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open('contacts/addContact', array('id' => 'formAddContact')); ?>
                <input type="hidden" name="addContactId" id="addPlaceIdContact" value="">
                <div class="form-row" style="margin-top: 4px;">
                    <div class="col">
                        <label for="addContactDate">Date*</label>
                        <input type="date" class="form-control form-control-sm" id="addContactDate" name="addContactDate" value="<?= date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="form-row" style="margin-top: 4px;">
                    <div class="col">
                        <label for="addContactMode">Mode</label>
                        <select class="form-control form-control-sm" name="addContactMode" id="addContactMode">
                            <option value="1" selected>Appel</option>
                            <option value="2">Email</option>
                            <option value="3">Visite showroom</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="addContactSource">Source</label>
                        <select class="form-control form-control-sm" name="addContactSource" id="addContactSource">
                            <option value="1" selected>Spontané</option>
                            <option value="2">Publicité boîte aux lettres</option>
                            <option value="3">Publicité voie publique</option>
                            <option value="4">Site internet/Google</option>
                            <option value="5">Amis/Connaissances</option>
                            <option value="6">Pages jaunes</option>
                        </select>
                    </div>
                </div>
                <div class="form-row" style="margin-top: 4px;">
                    <div class="col">
                        <label for="addContactNom">Nom*</label>
                        <input type="text" class="form-control form-control-sm" id="addContactNom" name="addContactNom" value="" placeholder="Nom">
                    </div>
                </div>
                <div class="form-row" style="margin-top: 4px;">
                    <div class="col">
                        <label for="addContactAdresse">Adresse</label>
                        <input type="text" class="form-control form-control-sm" id="addContactAdresse" name="addContactAdresse" value="" placeholder="Adresse">
                    </div>
                </div>
                <div class="form-row" style="margin-top: 4px;">
                    <div class="col-5">
                        <label for="addContactCp">Code postal</label>
                        <input type="text" class="form-control form-control-sm" id="addContactCp" name="addContactCp" value="" placeholder="Code postal">
                    </div>
                    <div class="col-7">
                        <label for="addContactVille">Ville*</label>
                        <input type="text" class="form-control form-control-sm" id="addContactVille" name="addContactVille" value="" placeholder="Ville">
                    </div>
                </div>
                <div class="form-row" style="margin-top: 8px;">
                    <div class="col-5">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="addContactTelephone" name="addContactTelephone" value="" placeholder="Téléphone">
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control form-control-sm" id="v" name="addContactEmail" value="" placeholder="Email">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <label for="addContactCategorieId">Catégorie</label><br>
                        <select name="addContactCategorieId" id="addContactCategorieId" class="selectpicker show-tick" data-width="auto" data-live-search="true" title="Sélectionnez une catégorie...">
                            <option value="0">Non classée</option>
                            <?php
                            if (!empty($categories)):
                                foreach ($categories as $categorie):
                                    $isCategorieSelect = '';
                                    if (!empty($affaire) && $categorie->getCategorieId() == $affaire->getAffaireCategorieId()):
                                        $isCategorieSelect = 'selected';
                                    endif;
                                    echo '<option value="' . $categorie->getCategorieId() . '" ' . $isCategorieSelect . '>' . $categorie->getCategorieNom() . '</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="addContactCommercialId">Commercial</label><br>
                        <select name="addContactCommercialId" id="addContactCommercialId" class="selectpicker show-tick" data-width="auto">
                            <option value="0">Non attribué</option>
                            <?php
                            if (!empty($commerciaux)):
                                foreach ($commerciaux as $commercial):
                                    $isCommercialSelect = '';
                                    if (!empty($affaire) && $commercial->getId() == $affaire->getAffaireCommercialId()):
                                        $isCommercialSelect = 'selected';
                                    endif;
                                    echo '<option value="' . $commercial->getId() . '"' . $isCommercialSelect . '>' . $commercial->getuserPrenom() . ' ' . $commercial->getUserNom() . '</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row" style="margin-top: 4px;">
                    <div class="col">
                        <label for="addContactObjet">Objet*</label>
                        <textarea class="form-control form-control-sm" id="addContactObjet" name="addContactObjet" rows="3"></textarea>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-primary btn-sm" id="btnSubmitFormContact">
                    <i class="fas fa-plus-square"></i> Ajouter
                </button>
                <div id="loaderAddContact" class="formloader">
                    <i class="fas <?= $this->session->userdata('loaderIcon'); ?> fa-spin"></i>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>