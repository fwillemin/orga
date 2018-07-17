<div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 fond" style="padding-top: 20px;">
        <h2>
            Gestion de vos catégories de chantier
        </h2>
        <hr>
        <div class="row">
            <div class="col-5">
                <?= form_open('categories/addCategorie', array('id' => 'formAddCategorie')); ?>
                <input type="hidden" name="addCategorieId" id="addCategorieId" value="<?= !empty($categorie) ? $categorie->getCategorieId() : ''; ?>">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Catégorie" name="addCategorieNom" id="addCategorieNom"  value="<?= !empty($categorie) ? $categorie->getCategorieNom() : ''; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="btnSubmitFormCategorie"><?= !empty($categorie) ? '<i class="fas fa-edit"></i>' : '<i class="fas fa-plus-square"></i>'; ?></button>
                        <?php if (!empty($categorie)): ?>
                            <a title="Quitter cette catégorie" href="<?= site_url('categories/liste/'); ?>" class="btn btn-outline-dark" type="button"><i class="fas fa-times"></i></a>
                            <button class="btn btn-outline-danger" type="button" id="btnDelCategorie"><i class="fas fa-trash"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
                <?= form_close(); ?>
                <h4>Liste des Catégories</h4>
                <table class="table table-sm style1" id="tableCategories">
                    <thead>
                        <tr>
                            <td>Categories</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($categories)):
                            foreach ($categories as $cat):
                                if ($cat->getCategorieId() == $this->uri->segment(3)):
                                    $style = 'class="ligneClikable ligneSelectionnee"';
                                else:
                                    $style = 'class="ligneClikable"';
                                endif;
                                echo '<tr data-categorieid="' . $cat->getCategorieId() . '"' . $style . '><td>' . $cat->getCategorieNom() . '</td></tr>';
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>

            <div class = "col-6 offset-1">

            </div>
        </div>
    </div>
</div>
