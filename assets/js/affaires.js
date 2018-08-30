$(document).ready(function () {

    $('#modalAddAffaire').modal();

    $('#selectCouleurAffaire').colorpicker({
        color: $('#addAffaireCouleur').val(),
        container: true,
        inline: true,
        useAlpha: false
    }).on('colorpickerChange colorpickerCreate', function (e) {
        $('#demoAffaire').css('background-color', e.color.toRgbString());
        $('#addAffaireCouleur').val('#' + e.color.toHex());
        $.post(chemin + 'affaires/getCouleurSecondaire/', {couleur: e.color.toHex()}, function (retour) {
            $('#demoAffaire').css('color', retour.couleur);
        }, 'json');
    });

    $('#selectCouleurChantier').colorpicker({
        color: $('#addAffaireCouleur').val(),
        container: true,
        inline: true,
        useAlpha: false
    }).on('colorpickerChange colorpickerCreate', function (e) {
        $('#demoChantier').css('background-color', e.color.toRgbString());
        $('#addChantierCouleur').val('#' + e.color.toHex());
        $.post(chemin + 'affaires/getCouleurSecondaire/', {couleur: e.color.toHex()}, function (retour) {
            $('#demoChantier').css('color', retour.couleur);
        }, 'json');
    });

    $('#btnResetCouleurChantier').on('click', function () {        
        $('#selectCouleurChantier').colorpicker('setValue', $('#demoChantier').attr('data-couleuraffaire'));
        $('#selectCouleurChantier').colorpicker('update');
    });

    $('#tableAffaires').DataTable({
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

    $('#tableAffaires').on('click', 'tbody tr', function () {
        window.location.assign(chemin + 'affaires/ficheAffaire/' + $(this).attr('data-affaireid'));
    });

    $('.formClose').on('click', function () {
        $(this).closest('.inPageForm').slideUp(300);
    });

    $('#addAffaireClientId').on('change', function () {
        $('#addAffairePlaceId').children('option').remove();
        $.post(chemin + 'clients/getPlacesClient', {clientId: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'success':
                    console.log(retour.places);
                    for (var i = 0; i < retour.places.length; i++) {
                        console.log('Insert')
                        $('#addAffairePlaceId').append('<option value="' + retour.places[i].placeId + '">' + retour.places[i].placeAdresse + '</option>');
                    }
                    ;
                    $('#addAffairePlaceId').selectpicker('refresh');
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });

    $('#formAddAffaire').on('submit', function (e) {
        $('#loaderAddAffaire').show();
        $('#btnSubmitFormAffaire').hide();
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'affaires/addAffaire', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $('#loaderAddAffaire').hide();
                    $('#btnSubmitFormAffaire').show();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'affaires/ficheAffaire/' + retour.affaireId);
                    break;
            }
        }, 'json');
    });

    $('#formAddChantier').on('submit', function (e) {
        $('#loaderAddChantier').show();
        $('#btnSubmitFormChantier').hide();
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'chantiers/addChantier', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $('#loaderAddChantier').hide();
                    $('#btnSubmitFormChantier').show();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'chantiers/ficheChantier/' + retour.chantierId);
                    break;
            }
        }, 'json');
    });

    $('#btnAddAffaire').on('click', function () {
        $('#modalAddAffaire').modal('show');
    });

    $('#selectAffairesEtat').on('change', function () {
        $.post(chemin + 'affaires/rechAffaireEtat', {etat: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'affaires/liste');
                    break;
            }
        }, 'json');
    });

    $('.js-linkChantier').on('click', function () {
        window.location.assign(chemin + 'chantiers/ficheChantier/' + $(this).attr('data-chantierid'));
    });

    $('#btnAddClient').on('click', function () {
        $('#modalAddClient').modal('show');
    });

    $('#formAddClient').on('submit', function (e) {
        e.preventDefault();
        $('#btnSubmitFormClient').hide();
        $('#loaderAddClient').show();
        var donnees = $(this).serialize();
        $.post(chemin + 'clients/addClient', donnees, function (retour) {
            $('#btnSubmitFormClient').show();
            $('#loaderAddClient').hide();
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#addAffaireClientId').append('<option value="' + retour.client.clientId + '" selected>' + retour.client.clientNom + '</option>');
                    $('#addAffairePlaceId').append('<option value="' + retour.place.placeId + '" selected>' + retour.place.placeAdresse + '</option>');
                    $('#addAffairePlaceId').selectpicker('refresh');
                    $('#addAffaireClientId').selectpicker('refresh');
                    $('#modalAddClient').modal('hide');
            }
        }, 'json');
    });


//    Ajouter une place depuis le formulaire d'une affaire
    $('#btnAddPlaceAffaire').on('click', function () {
        $('#addPlaceClientIdAffaire').val($('#addAffaireClientId').val());
        $('#addPlaceAdresseAffaire').val('');
        $('#modalAddPlaceAffaire').modal('show');
    });

    $('#formAddPlaceAffaire').on('submit', function (e) {
        e.preventDefault();
        $('#btnSubmitFormPlaceAffaire').hide();
        $('#loaderAddPlaceAffaire').show();
        var donnees = $(this).serialize();
        $.post(chemin + 'clients/addPlace', donnees, function (retour) {
            $('#btnSubmitFormPlaceAffaire').show();
            $('#loaderAddPlaceAffaire').hide();
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#addAffairePlaceId').append('<option value="' + retour.place.placeId + '" selected>' + retour.place.placeAdresse + '</option>');
                    $('#addPlaceAdresseAffaire').val('');
                    $('#addAffairePlaceId').selectpicker('refresh');
                    $('#modalAddPlaceAffaire').modal('hide');
                    break;
            }
        }, 'json');
    });

//    Ajouter une place depuis le formulaire d'un chantier
    $('#btnAddPlaceChantier').on('click', function () {
        $('#addPlaceAdresseChantier').val('');
        $('#modalAddPlaceChantier').modal('show');
    });

    $('#formAddPlaceChantier').on('submit', function (e) {
        e.preventDefault();
        $('#btnSubmitFormPlaceChantier').hide();
        $('#loaderAddPlaceChantier').show();
        var donnees = $(this).serialize();
        $.post(chemin + 'clients/addPlace', donnees, function (retour) {
            $('#btnSubmitFormPlaceChantier').show();
            $('#loaderAddPlaceChantier').hide();
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#addChantierPlaceId').append('<option value="' + retour.place.placeId + '" selected>' + retour.place.placeAdresse + '</option>');
                    $('#addPlaceAdresseChantier').val('');
                    $('#addChantierPlaceId').selectpicker('refresh');
                    $('#modalAddPlaceChantier').modal('hide');
                    break;
            }
        }, 'json');
    });

    $('#btnFicheClient').on('click', function () {
        if ($('#addAffaireClientId').val()) {
            window.location.assign(chemin + 'clients/ficheClient/' + $('#addAffaireClientId').val());
        } else {
            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'Pour accéder à la fiche client, vous devez d\'abord... sélectionner un client.'});
        }
    });

    $('#btnAddChantier').on('click', function () {
        $('#modalAddChantier').modal('show');
    });
    
    $('#btnDelAffaire').confirm({
        title: 'Suppression de cette affaire ?',
        content: 'Êtes-vous sûr de vouloir supprimer cette affaire ?',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {
                    $.post(chemin + 'affaires/delAffaire', {affaireId: $('#addAffaireId').val()}, function (retour) {
                        switch (retour.type) {
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                break;
                            case 'success':
                                window.location.assign(chemin + 'affaires/liste');
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

});

