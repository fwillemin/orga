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

$(document).ready(function () {

    $('#rowPlanning').fadeIn();
    $('#rowLoading').fadeOut();
      
    $("#divPlanning").animate({scrollLeft: getCookie('positionPlanning')}, 800);
    if ($('#divPlanning').scrollLeft() == 0) {
        $('#divPlanning').scrollLeft($('#divPlanning').attr('today'));
    }
    
    $('#divPlanning').scroll(function () {        
        var trs = document.getElementById('divPlanning').scrollLeft;
        document.cookie = "positionPlanning" + "=" + escape(trs) + ';path=/';
    });

    var resizeOption = {
        grid: [(parseFloat($('#caseWidth').val()) + 1), parseFloat($('#caseHeight').val())],
        stop: function () {
            var nbCases = Math.ceil(($(this).width()) / (parseFloat($('#caseWidth').val()) + 1));
            var affect = $(this);
            $.post(chemin + 'planning/resizeAffectation/', {nbCases: nbCases, affectationId: affect.attr('data-affectationid')}, function (retour) {
                if (retour.type === 'error') {
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                }
                affect.remove();
                $('#divPlanning').append(retour.html);
                refreshPlanningUI();
            }, 'json');
        }
    };

    var start = [0, 0], stop = [0, 0];
    var dragOption = {
        delay: 200,
        cancel: "span",
        grid: [(parseFloat($('#caseWidth').val()) + 1), parseFloat($('#caseHeight').val())],
        cursor: "move",
        start: function () {
            start[0] = this.offsetLeft;
            start[1] = this.offsetTop;
        },
        drag: function () {
        },
        stop: function () {
            stop[0] = this.offsetLeft;
            stop[1] = this.offsetTop;
            var affect = $(this);
            $.post(chemin + 'planning/dragAffectation',
                    {
                        decalageX: stop[0] - start[0],
                        decalageY: stop[1] - start[1],
                        affectationId: affect.attr('data-affectationid'),
                        ligne: affect.attr('data-ligne')
                    },
                    function (retour) {
                        if (retour.type == 'error') {
                            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                        }
                        affect.remove();
                        $('#divPlanning').append(retour.html);
                        refreshPlanningUI();
                    }, 'json'
                    );
        }
    };

    function refreshPlanningUI() {
        $(".resizable").resizable(resizeOption);
        $(".draggable").draggable(dragOption);
    }
    refreshPlanningUI();

    $('#divPlanning').on('mousedown', function (e) {
        e.preventDefault();
    });

    $('#datepicker-container div').datepicker({
        weekStart: 1,
        maxViewMode: 2,
        todayBtn: "linked",
        language: "fr",
        daysOfWeekDisabled: "0,6",
        daysOfWeekHighlighted: "1",
        todayHighlight: true,
        format: "yyyy-mm-dd"
    });

    $('#datepicker-container div').on('changeDate', function () {
        window.location.assign(chemin + 'planning/base/' + $(this).datepicker('getFormattedDate'));
    });

    $('#toggleCalendar').on('click', function () {
        $('#datepicker-container').toggle();
    });
    $('#toogleTermines').on('change', function () {
        if ($(this).prop('checked') === true) {
            var etat = 1;
        } else {
            var etat = 0;
        }
        $.post(chemin + 'planning/modAffichageTermines', {etat: etat}, function (retour) {
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

    $('#resetSearchClientAffaire').on('click', function () {
        $('#searchClientAffaire').val('');
        $('#tableSlideChantiers tr').show();
    });
    $('#searchClientAffaire').on('keyup', function () {
        $(this).val($(this).val().toUpperCase());
        var exp = new RegExp($('#searchClientAffaire').val(), "g");
        $('.js-slideLigneAffaire').each(function () {
            affaire = $(this).attr('data-affaireid');
            if ($(this).text().match(exp)) {
                $(this).fadeIn();
                $('tr[data-affaireParent="' + affaire + '"]').fadeIn();
            } else {
                $(this).fadeOut();
                $('tr[data-affaireParent="' + affaire + '"]').fadeOut();
            }
        });
    });

    $('#divPlanning').on('dblclick', '.affectation', function () {
        var affect = $(this);
        $.post(chemin + 'planning/affectationToggleAffichage', {affectationId: affect.attr('data-affectationid'), ligne: affect.attr('data-ligne')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    affect.remove()
                    $('#divPlanning').append(retour.html);
                    refreshPlanningUI();
                    break;
            }
        }, 'json');
    });

    function affectationRAZ() {
        $('#addAffectationId').val('');
        $('#addAffectationCommentaire').val('');
        $('#addAffectationType option[value="1"]').prop('selected', true);
        $('#addAffectationNbDemi option[value="1"]').prop('selected', true);
        $('#addAffectationChantierId option').each(function () {
            $(this).prop('selected', false);
        });
        $('#addAffectationPersonnelsIds option').each(function () {
            $(this).prop('selected', false);
        });
        $('#addAffectationChantierId').selectpicker('refresh');
        $('#addAffectationPersonnelsIds').selectpicker('refresh');
    }

    $('.matin, .aprem').on('click', function () {
        affectationRAZ();
        var caseJour = $(this);

        $('#addAffectationPersonnelsIds option').prop('selected', false);
        $('#addAffectationPersonnelsIds option[value="' + caseJour.closest('tr').attr('data-personnelid') + '"]').prop('selected', true);
        $('#addAffectationPersonnelsIds').selectpicker('refresh');

        var jour = $('#tablePlanning tbody').children('tr').eq(1).children('td').eq(Math.floor(caseJour[0].cellIndex / 2)).attr('data-jour');
        $('#addAffectationDebutDate').val(jour);
        $('#addAffectationFinDate').val(jour);
        if (caseJour.hasClass('matin')) {
            $('#addAffectationDebutMoment option[value="1"]').prop('selected', true);
            $('#addAffectationFinMoment option[value="1"]').prop('selected', true);
        } else {
            $('#addAffectationDebutMoment option[value="2"]').prop('selected', true);
            $('#addAffectationFinMoment option[value="2"]').prop('selected', true);
        }
        $('#modalAddAffectation').modal('show');
    });

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

    $('#addAffectationDebutDate, #addAffectationDebutMoment, #addAffectationNbDemi').on('change', function () {
        var timeStart = dateToTimestamp($('#addAffectationDebutDate').val());

        /* Calcul du demi de fin */
        if (Math.floor($('#addAffectationNbDemi').val() % 2) === 1) {
            $('#addAffectationFinMoment').val($('#addAffectationDebutMoment').val());
        } else {
            if ($('#addAffectationDebutMoment').val() === '2') {
                var demiFin = 1;
            } else {
                var demiFin = 2;
            }
            $('#addAffectationFinMoment').val(demiFin);
        }
        /* Calcul du timestamp de fin */
        var timeEnd = timeStart;
        var nbDemiRestant = $('#addAffectationNbDemi').val();
        /* Fin du premier jour */
        if ($('#addAffectationDebutMoment').val() === '1') {
            nbDemiRestant -= 2;
        } else {
            nbDemiRestant--;
        }
        while (nbDemiRestant > 0) {
            timeEnd += 86400;
            if (numeroJour(timeEnd) > 0 && numeroJour(timeEnd) < 6) {
                nbDemiRestant -= 2;
            }
        }
        $('#addAffectationFinDate').val(timestampToDate(timeEnd));

    });

    $('#addAffectationFinDate, #addAffectationFinMoment').on('change', function () {
        /* On recalcule le nombre de demi journées ouvrées entre la nouvelle date de fin et la date de début */
        var timeStart = dateToTimestamp($('#addAffectationDebutDate').val());
        var timeEnd = dateToTimestamp($('#addAffectationFinDate').val());
        var nbDemiAffect = 0;

        if ($('#addAffectationDebutMoment').val() === '2') {
            var nbDemiAffect = 1;
        } else {
            var nbDemiAffect = 2;
        }
        while (timeStart < timeEnd) {
            timeStart += 86400;
            if (numeroJour(timeStart) > 0 && numeroJour(timeStart) < 6) {
                nbDemiAffect += 2;
            }
        }
        if ($('#addAffectationFinMoment').val() === '1' && numeroJour(timeStart) > 0 && numeroJour(timeStart) < 6) {
            nbDemiAffect--;
        }
        $('#addAffectationNbDemi').val(nbDemiAffect);
    });

    $('#formAddAffectation').on('submit', function (e) {
        e.preventDefault();

        if ($("#addAffectationPersonnelsIds option:selected").length < 1) {
            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'Vous devez sélectionner au moins 1 personnel'});
        } else {
            if (parseInt($('#addAffectationId').val()) > 0 && $("#addAffectationPersonnelsIds option:selected").length > 1) {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'Lors de la modification d\'une affectation, vous ne pouvez selectionner qu\'1 personnel unique'});
            } else {

                var donnees = $(this).serialize();
                $.post(chemin + 'planning/addAffectation', donnees, function (retour) {
                    switch (retour.type) {
                        case 'error':
                            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                            break;
                        case 'success':
                            if (parseInt($('#addAffectationId').val()) > 0) {
                                $('.affectation[data-affectationid="' + $('#addAffectationId').val() + '"]').remove();
                            }
                            $('#modalAddAffectation').modal('hide');
                            $('#modalAffectation').modal('hide');
                            affectationRAZ();

                            $('#divPlanning').append(retour.HTML);
                            refreshPlanningUI();
                            break;
                    }
                }, 'json');

            }
        }
    });

    $('#divPlanning').on('click', '.planningDivText', function () {
        var affectationId = $(this).parent('div').attr('data-affectationid');
        $.post(chemin + 'planning/getAffectationDetails', {affectationId: affectationId}, function (retour) {
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
                        $('#addAffectationDebutDate').val(timestampToDate(retour.affectation.affectationDebutDate));
                        $('#addAffectationDebutMoment option[value="' + retour.affectation.affectationDebutMoment + '"]').prop('selected', true);
                        $('#addAffectationFinDate').val(timestampToDate(retour.affectation.affectationFinDate));
                        $('#addAffectationFinMoment option[value="' + retour.affectation.affectationFinMoment + '"]').prop('selected', true);
                        $('#addAffectationType option[value="' + retour.affectation.affectationType + '"]').prop('selected', true);
                        $('#addAffectationCommentaire').val(retour.affectation.affectationCommentaire);
                    }

                    $('#headerModalAffectation').html('Consultation d\'une affectation');
                    $('#textAffectationClient').html('<a href="' + chemin + 'clients/ficheClient/' + retour.client.clientId + '">' + retour.client.clientNom + '</a>');
                    $('#textAffectationAffaire').html('<a href="' + chemin + 'affaires/ficheAffaire/' + retour.affaire.affaireId + '">' + retour.affaire.affaireObjet + '</a>');
                    $('#textAffectationPersonnel').html('<a href="' + chemin + 'personnels/fichePersonnel/' + retour.personnel.personnelId + '">' + retour.personnel.personnelNom + '</a>');
                    $('#textAffectationChantier').html('<a href="' + chemin + 'chantiers/ficheChantier/' + retour.chantier.chantierId + '">' + retour.chantier.chantierObjet + '</a>');
                    $('#textAffectationAvancementHeures').css('width', retour.chantier.chantierRatio + '%');
                    $('#textAffectationAvancementHeures').addClass(retour.chantier.chantierProgressBar);
                    $('#textAffectationPeriode').html(retour.affectation.affectationPeriode);
                    $('#textAffectationType').html(retour.affectation.affectationTypeText);
                    $('#textAffectationAdresse').html(retour.chantier.chantierPlace);
                    $('#textAffectationCommentaire').html(retour.affectation.affectationCommentaire);

                    $('#modalAffectation').modal('show');
                    break;
            }
        }, 'json');
    });

    $('#btnModAffectation').on('click', function () {
        $('#modalAddAffectation').modal('show');
    });

    $('#btnDelAffectation').confirm({
        columnClass: 'medium',
        title: 'Suppression de cette affectation ?',
        content: 'Toutes les heures saisies sur cette affectation seront perdues.<br>Cela modifiera <b>les feuilles de pointage</b> et <b>les statistiques du chantier et de l\'affaire</b>',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {
                    $.post(chemin + 'planning/delAffectation', {affectationId: $('#addAffectationId').val()}, function (retour) {
                        switch (retour.type) {
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                break;
                            case 'success':
                                $('.affectation[data-affectationid="' + $('#addAffectationId').val() + '"]').remove();
                                affectationRAZ();
                                $('#modalAffectation').modal('hide');
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

