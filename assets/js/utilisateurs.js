$(document).ready(function () {

    $('#tableUtilisateurs').DataTable({
        language: {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            },
            "select": {
                "rows": {
                    _: "%d lignes séléctionnées",
                    0: "Aucune ligne séléctionnée",
                    1: "1 ligne séléctionnée"
                }
            }
        }
    });

    $('#tableUtilisateurs').on('click', 'tbody tr', function () {
        window.location.assign(chemin + 'utilisateurs/ficheUtilisateur/' + $(this).attr('data-userid'));
    });

    $('#btnModUtilisateur').on('click', function () {
        $('#containerModUtilisateur').slideDown(700);
    });

    $('.formClose').on('click', function () {
        $(this).closest('.inPageForm').slideUp(300);
    })

    $('#formAddUtilisateur').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'utilisateurs/addUtilisateur', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json');
    });

    $('.changeAcces, .typeCompte').on('change', function () {
        if ($(this).prop('checked') === true) {
            var acces = 1;
        } else {
            var acces = 0;
        }
        
        if( $(this).val() == '4' || $(this).val() == '9'){
            $('.changeAcces').prop('checked', false);
            $('.changeAcces').prop('disabled', true);
        } else{
            if($(this).val() == '1' || $(this).val() == '2') {
            $('.changeAcces').prop('disabled', false);
        }}
        
        $.post(chemin + 'utilisateurs/modifierAcces', {userId: $('#addUserId').val(), groupeId: $(this).val(), acces: acces}, function(retour){
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="fas fa-check"></i> OK</strong>', message: '<br>' + 'Accès modifié'});                    
                    break;
            }
        }, 'json');
    });
    
    $('#btnAddUtilisateur').on('click', function(){
        $('#modalAddUser').modal('show');
    });

});

