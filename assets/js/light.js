function  getCookie(name) {
    if (document.cookie.length == 0)
        return null;

    var regSepCookie = new RegExp('(; )', 'g');
    var cookies = document.cookie.split(regSepCookie);

    for (var i = 0; i < cookies.length; i++) {
        var regInfo = new RegExp('=', 'g');
        var infos = cookies[i].split(regInfo);
        if (infos[0] == name) {
            return unescape(infos[1]);
        }
    }
    return null;
}

function checkOrientation() {
    if (screen.height > screen.width) {
        $('#changeLandscape').fadeIn();
    } else {
        $('#changeLandscape').hide();
    }
}

$(document).ready(function () {
checkOrientation();

    window.addEventListener("orientationchange", function () {
        checkOrientation();
    }, false);

    function affectationRAZ() {
        $('#addAffectationId').val('');
        $('#addAffectationCommentaire').val('');
        $('#addAffectationType option[value="1"]').prop('selected', true);
        $('#addAffectationNbDemi option[value="1"]').prop('selected', true);
        $('#addAffectationNbHeures').val('0');
        $('#addAffectationChantierId option').prop('selected', false);
        $('#addAffectationPersonnelsIds option').prop('selected', false);
        $('#addAffectationChantierId').selectpicker('refresh');
        $('#addAffectationPersonnelsIds').selectpicker('refresh');
        $('#btnCutAffectation').prop('disabled', false);
        $('#btnDecaleAffectation').prop('disabled', false);
        $('#tableAffectationHeures tbody tr').remove();
        $('#tableAffectationHeures tbody').append('<tr><td colspan="2">Aucune heure saisie</td></tr>');
        $('#btnSubmitFormAffectation').html('<i class="fas fa-plus-square"></i> Ajouter');
        $('#modalAddAffectation .modal-title').text('Ajouter une affectation');
        $('#btnAddIndispo').show();
    }


    function dateToTimestamp(date) {
        myDate = date.split("-");
        var formattedDate = myDate[1] + "-" + myDate[2] + "-" + myDate[0];
        return Math.round(Date.parse(formattedDate) / 1000);
    }

    function timestampToDate(timestamp) {
        var datum = new Date(timestamp * 1000);
        return datum.getFullYear() + '-' + ("0" + (datum.getMonth() + 1)).slice(-2) + '-' + ("0" + datum.getDate()).slice(-2);
    }
    function numeroJour(timestamp) {
        var datum = new Date(timestamp * 1000);
        return datum.getDay();
    }

    $('#divPlanning').on('click', '.planningDivText', function () {
        var affectationId = $(this).parent('div').attr('data-affectationid');
        $.post(chemin + 'light/getAffectationDetails', {affectationId: affectationId}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    affectationRAZ();
                    /* Modif du formulaire affectation */
                    if ($('#addAffectationId')) {
                        $('#addAffectationId').val(retour.affectation.affectationId);
                        $('#addAffectationChantierId option[value="' + retour.affectation.affectationChantierId + '"]').prop('selected', true);
                        $('#addAffectationPersonnelsIds option[value="' + retour.affectation.affectationPersonnelId + '"]').prop('selected', true);
                        $('#addAffectationChantierId').selectpicker('refresh');
                        $('#addAffectationPersonnelsIds').selectpicker('refresh');
                        $('#addAffectationNbDemi option[value="' + retour.affectation.affectationNbDemi + '"]').prop('selected', true);
                        $('#addAffectationNbHeures').val(retour.affectation.affectationHeuresPlanifiees);
                        $('#addAffectationDebutDate').val(timestampToDate(retour.affectation.affectationDebutDate));
                        $('#addAffectationDebutMoment option[value="' + retour.affectation.affectationDebutMoment + '"]').prop('selected', true);
                        $('#addAffectationFinDate').val(timestampToDate(retour.affectation.affectationFinDate));
                        $('#addAffectationFinMoment option[value="' + retour.affectation.affectationFinMoment + '"]').prop('selected', true);
                        $('#addAffectationType option[value="' + retour.affectation.affectationType + '"]').prop('selected', true);
                        $('#addAffectationCommentaire').val(retour.affectation.affectationCommentaire);
                        $('#btnSubmitFormAffectation').html('<i class="fas fa-edit"></i> Modifier');
                        $('#modalAddAffectation .modal-title').text('Modifier une affectation');
                        $('#btnAddIndispo').hide();
                    }

                    /* Liste des heures */
                    if (retour.heures.length > 0) {
                        $('#tableAffectationHeures tbody tr').remove();
                        for (i = 0; i < retour.heures.length; i++) {
                            if(retour.heures[i].heureValide == '1'){
                                etat = '<i class="fas fa-check"></i>';
                            }else{
                                etat = '';
                            }
                            $('#tableAffectationHeures tbody').append('<tr><td>' + retour.heures[i].heureDate + '</td><td align="right">' + retour.heures[i].heureDuree + '</td><td>' + etat + '</td></tr>');
                        }
                    }

                    $('#headerModalAffectation').html('Consultation d\'une affectation');
                    $('#textAffectationClient').html('<a href="' + chemin + 'clients/ficheClient/' + retour.client.clientId + '">' + retour.client.clientNom + '</a> <span style="font-size:12px;">' + retour.client.clientFixe + ', ' + retour.client.clientPortable );
                    $('#textAffectationAffaire').html(retour.affaire.affaireObjet);
                    $('#textAffectationPersonnel').html(retour.personnel.personnelNom);
                    $('#textAffectationChantier').html(retour.chantier.chantierObjet);

                    if (retour.chantier.chantierEtat === '2') {
                        $('#btnCutAffectation').prop('disabled', true);
                        $('#btnDecaleAffectation').prop('disabled', true);
                    }
                    $('#textAffectationAvancementHeures').css('width', retour.chantier.chantierRatio + '%');
                    $('#textAffectationAvancementHeures').addClass(retour.chantier.chantierProgressBar);
                    $('#textAffectationPeriode').html(retour.affectation.affectationPeriode);
                    $('#textAffectationHeuresPlanifiees').html(retour.affectation.affectationHeuresPlanifiees);
                    $('#textAffectationType').html(retour.affectation.affectationTypeText);
                    $('#textAffectationAdresse').html(retour.chantier.chantierPlace);
                    $('#textAffectationCommentaire').html(retour.affectation.affectationCommentaire);
                    $('#couperDate').val(timestampToDate(retour.affectation.affectationDebutDate));
                    $('#couperMoment').val(retour.affectation.affectationDebutMoment);
                    $('#modalAffectation').modal('show');
                    break;
            }
        }, 'json');
    });

    $('.connectPersonnel').on('click', function () {
        $('#connectPersonnelId').val($(this).attr('data-personnelid'));
        $('#spanNomPersonnelConnect').html($(this).attr('data-personnelnom'));
        $('#modalConnect').modal('show');
    });

    $('#digit1').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '1');
    });
    $('#digit2').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '2');
    });
    $('#digit3').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '3');
    });
    $('#digit4').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '4');
    });
    $('#digit5').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '5');
    });
    $('#digit6').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '6');
    });
    $('#digit7').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '7');
    });
    $('#digit8').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '8');
    });
    $('#digit9').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '9');
    });
    $('#digit0').on('click', function () {
        $('#connectCode').val($('#connectCode').val() + '0');
    });
    $('#digitReset').on('click', function () {
        $('#connectCode').val('');
    });

    $('#connectStart').on('click', function () {
        $(this).hide();
        $('#loaderConnect').show();
        $.post(chemin + 'light/tryConnectOuvrier', {personnelId: $('#connectPersonnelId').val(), code: $('#connectCode').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $('#connectStart').show();
                    $('#loaderConnect').hide();
                    $('#connectCode').val('')
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'light/saisie');
                    break;
            }
        }, 'json');
    });
    
    $('.heureSelect').on('change', function () {
        info = $(this).closest('div');
        trou = info.siblings('.trou');
        $.post(chemin + 'pointages/addHeure', {heureId: info.attr('data-heureid'), affectationId: info.attr('data-affectationid'), jour: info.attr('data-date'), duree: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    info.attr('data-heureid', retour.heureId);
                    trou.removeClass();
                    trou.addClass('trou ' + retour.retourClass);
                    break;
            }
        }, 'json');
    });

});


