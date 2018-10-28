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
        contactRAZ();
        $('#btnSubmitFormContact').html('<i class="fas fa-plus-square"></i> Ajouter');
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

    $('.changeContactEtat').on('change', function (e) {
        e.stopPropagation();
        $.post(chemin + 'contacts/avancementContact', {contactId: $(this).closest('tr').attr('data-contactid'), contactEtat: $(this).val()}, function (retour) {
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

    $('.btnDelContact').on('click', function () {
        ligne = $(this).closest('tr');
    }).confirm({
        title: 'Suppression du contact entrant ?',
        content: 'Action irréversible...',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {
                    $.post(chemin + 'contacts/delContact', {contactId: ligne.attr('data-contactid')}, function (retour) {
                        switch (retour.type) {
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                break;
                            case 'success':
                                ligne.fadeOut();
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
    });

    function contactRAZ() {
        $('#addContactId').val('');
        $('#addContactDate').val('');
        $('#addContactNom').val('');
        $('#addContactAdresse').val('');
        $('#addContactCp').val('');
        $('#addContactVille').val('');
        $('#addContactTelephone').val('');
        $('#addContactEmail').val('');
        $('#addContactObjet').val('');
        $('#addContactMode option[value="1"]').prop('selected', true);
        $('#addContactSource option[value="1"]').prop('selected', true);
        $('#addContactCategorieId option[value="0"]').prop('selected', true);
        $('#addContactCommercialId option[value="0"]').prop('selected', true);
        $('#addContactCategorieId').selectpicker('refresh');
        $('#addContactCommercialId').selectpicker('refresh');
    }

    $('.btnModContact').on('click', function () {
        $.post(chemin + 'contacts/getContact', {contactId: $(this).closest('tr').attr('data-contactid')}, function (retour) {
            contactRAZ();
            /* Hydrate form */
            $('#addContactId').val(retour.contact.contactId);
            $('#addContactDate').val(refactorDate(retour.contact.contactDate));
            $('#addContactNom').val(retour.contact.contactNom);
            $('#addContactAdresse').val(retour.contact.contactAdresse);
            $('#addContactCp').val(retour.contact.contactCp);
            $('#addContactVille').val(retour.contact.contactVille);
            $('#addContactTelephone').val(retour.contact.contactTelephone);
            $('#addContactEmail').val(retour.contact.contactEmail);
            $('#addContactObjet').val(retour.contact.contactObjet);
            $('#addContactMode option[value="' + retour.contact.contactMode + '"]').prop('selected', true);
            $('#addContactSource option[value="' + retour.contact.contactSource + '"]').prop('selected', true);
            $('#addContactCategorieId option[value="' + retour.contact.contactCategorieId + '"]').prop('selected', true);
            $('#addContactCategorieId').selectpicker('refresh');
            $('#addContactCommercialId option[value="' + retour.contact.contactCommercialId + '"]').prop('selected', true);
            $('#addContactCommercialId').selectpicker('refresh');
        }, 'json').done(function () {
            $('#btnSubmitFormContact').html('<i class="fas fa-edit"></i> Modifier');
            $('#modalAddContact').modal('show');
        });
    });

});

