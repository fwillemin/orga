$(document).ready(function () {
    
    /* Selection de l'onglet à activer */    
    if( window.location.href.substr(window.location.href.lastIndexOf('/') + 1).charAt(0) === 'a' ){
        $('#tabAchats').tab('show');
    }    

    $('#selectCouleurChantier').colorpicker({
        color: $('#addChantierCouleur').val(),
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

    $('#addAchatQte, #addAchatPrix').on('change', function () {
        $('#addAchatTotal').val($('#addAchatQte').val() * $('#addAchatPrix').val())
    });
    $('#addAchatQtePrevisionnel, #addAchatPrixPrevisionnel').on('change', function () {
        $('#addAchatTotalPrevisionnel').val($('#addAchatQtePrevisionnel').val() * $('#addAchatPrixPrevisionnel').val())
    });

    function achatRAZ() {
        $('#addAchatId').val('');
        $('#addAchatDescription').val('');
        $('#addAchatQte').val('');
        $('#addAchatPrix').val('');
        $('#addAchatTotal').val('');
        $('#addAchatQtePrevisionnel').val('');
        $('#addAchatPrixPrevisionnel').val('');
        $('#addAchatTotalPrevisionnel').val('');
        $('#tableAchats tr').removeClass('ligneSelectionnee');
        $('.js-onAchatMod').hide();
        $('#btnSubmitFormAchat').html('<i class="fas fa-plus-square"></i> Ajouter');
    }

    $('#btnAddAchat').on('click', function () {
        achatRAZ();
        $('#containerAddAchat').slideDown(700);
    });
    $('.formClose').on('click', function () {
        $(this).closest('.inPageForm').slideUp(300).done(achatRAZ());
    });

    $('#tableAchats tr.ligneClikable').on('click', function () {       
        window.location.assign(chemin + 'chantiers/ficheChantier/' + $('#addAchatChantierId').val() + '/a' + $(this).attr('data-achatid'));
    });

    $('#formAddAchat').on('submit', function (e) {
        $('#loaderAddAchat').show();
        $('#btnSubmitFormAchat').hide();
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'chantiers/addAchat', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $('#loaderAddAchat').hide();
                    $('#btnSubmitFormAchat').show();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'chantiers/ficheChantier/' + $('#addAchatChantierId').val() + '/a');
                    break;
            }
        }, 'json');
    });

    $('#btnDelAchat').confirm({
        title: 'Suppression de cet achat ?',
        content: 'Êtes-vous sûr de vouloir supprimer cet achat ?',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {
                    $.post(chemin + 'chantiers/delAchat', {achatId: $('#addAchatId').val()}, function (retour) {
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
    });
    
    $('#btnDelChantier').confirm({
        title: 'Suppression du chantier ?',
        content: 'Êtes-vous sûr de vouloir supprimer ce chantier ?',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {
                    $.post(chemin + 'chantiers/delChantier', {chantierId: $('#addChantierId').val()}, function (retour) {
                        switch (retour.type) {
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                break;
                            case 'success':
                                window.location.assign(chemin + 'affaires/ficheAffaire/' + $('#addChantierAffaireId').val());
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

