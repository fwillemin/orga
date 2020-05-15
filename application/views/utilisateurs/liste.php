<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 fond" style="padding-top: 20px;">

        <h2>
            Liste de vos utilisateurs
            <button class="btn btn-link" id="btnAddUtilisateur">
                <i class="fas fa-plus-square"></i> Ajouter
            </button>
        </h2>
        <hr>
        <table class="table table-bordered table-sm style1" id="tableUtilisateurs">
            <thead>
                <tr>
                    <td style="width: 30px;"></td>
                    <td style="min-width:150px;">Nom</td>
                    <td>Login</td>
                    <td>Type</td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($utilisateurs)):
                    foreach ($utilisateurs as $user):
                        echo '<tr class="ligneClikable" data-userid="' . $user->getId() . '">'
                        . '<td style="text-align:center; font-size:15px;">' . ( ($user->getUserType() == 'Inactif') ? '<label class = "badge badge-secondary">Inactif</label>' : '<label class = "badge badge-info">Actif</label>') . '</td>'
                        . '<td>' . $user->getUserNom() . ' ' . $user->getUserPrenom() . ' </td>'
                        . ' <td>' . $user->getUsername() . ' </td>'
                        . ' <td>' . $user->getUserType() . ' </td></tr>';
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
        <br>
    </div>
</div>

<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un utilisateur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php include('formUtilisateur.php'); ?>
            </div>
        </div>
    </div>
</div>