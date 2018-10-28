$(document).ready(function () {

    $('#modalAddContact').modal();    

    $('#tableContacts').DataTable({
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
        },
        "fnInitComplete": function () {
            $('#tableAffaires').show();
        }
    });

//    $('#tableContacts').on('click', 'tbody tr', function () {
//        window.location.assign(chemin + 'contacts/ficheContact/' + $(this).attr('data-affaireid'));
//    });

    $('#formAddContact').on('submit', function (e) {
        $('#loaderAddContact').show();
        $('#btnSubmitFormContact').hide();
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'contacts/addContact', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $('#loaderAddContact').hide();
                    $('#btnSubmitFormContact').show();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json');
    });

    $('#btnAddContact').on('click', function () {
        $('#modalAddContact').modal('show');
    });

    $('#selectContactsEtat').on('change', function () {
        $.post(chemin + 'contacts/rechContactEtat', {etat: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'contacts/liste');
                    break;
            }
        }, 'json');
    });
    
    $('.changeContactEtat').on('change', function(e){
        e.stopPropagation();
        $.post(chemin + 'contacts/avancementContact', {contactId: $(this).closest('tr').attr('data-contactid'), contactEtat: $(this).val()}, function(retour){
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="fas fa-check"></i> OK</strong>', message: '<br>' + 'Etat mis à jour.'});
                    break;
            }
        }, 'json');
    });
});

