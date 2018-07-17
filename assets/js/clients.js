$(document).ready(function () {

    $('#modalAddClient').modal();

    $('#tableClients').DataTable({
        pageLength: 50,
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

    $('#tableClients').on('click', 'tbody tr', function () {
        window.location.assign(chemin + 'clients/ficheClient/' + $(this).attr('data-clientid'));
    });

    $('#btnModClient').on('click', function () {
        $('#containerModClient').slideDown(700);
    });

    $('.formClose').on('click', function () {
        $(this).closest('.inPageForm').slideUp(300);
    })

    $('#formAddClient').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'clients/addClient', donnees, function (retour) {
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

    $('#formAddPlace').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'clients/addPlace', donnees, function (retour) {
            console.log(retour);
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

    $('#btnAddClient').on('click', function () {
        $('#modalAddClient').modal('show');
    });

    $('.btnDelPlace').on('click', function () {
        button = $(this);
        $.confirm({
            title: 'On supprime cette place ?',
            content: 'Cette place ne pourra plus être utilisée pour les affectations de chantier de ce client.',
            type: 'blue',
            theme: 'material',
            buttons: {
                confirm: {
                    btnClass: 'btn-green',
                    text: 'Supprimer',
                    action: function () {
                        $.post(chemin + 'clients/delPlace', {placeId: button.closest('tr').attr('data-placeid')}, function (retour) {
                            switch (retour.type) {
                                case 'error':
                                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                    break;
                                case 'success':
                                    window.location.reload();
                                    break;
                            }
                        }, 'json');
                    }

                },
                cancel: {
                    btnClass: 'btn-red',
                    text: 'Annuler'
                }
            }
        })
    });
    
    $('.js-fusion').on('click', function(e){
        e.stopPropagation();
        if( $(this).prop('checked') === true ){
            $(this).closest('tr').addClass('ligneSelectionnee');
        } else {
            $(this).closest('tr').removeClass('ligneSelectionnee');
        }
    });

});

