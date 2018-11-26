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
    $('[data-toggle="popover"]').popover({
        html: true
    });
    
    $('#messageGlobal').on('change', function() {
        $.post(chemin + 'planning/changeMessageGlobal', {message:$(this).val()}, function(retour){
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="fas fa-check"></i> OK</strong>', message: '<br>' + 'Message global mis à jour'});
                    break;
            }
        }, 'json');
    });
    $('#modalAffichageLivraison').modal({
        show: false,
        backdrop: false
    }).draggable({
        handle: ".modal-header"
    });
    $('#modalAddAffectation, #modalAffectation').modal({
        show: false
    }).draggable({
        handle: ".modal-header"
    });
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
    var dragOptionLivraison = {
        delay: 200,
        cancel: "span",
        grid: [(parseFloat(($('#caseWidth').val())) + 1) * 2],
        cursor: "move",
        start: function () {
            start[0] = this.offsetLeft;
        },
        drag: function () {
        },
        stop: function () {
            stop[0] = this.offsetLeft;
            var achatId = $(this).attr('data-achatid');
            $.post(chemin + 'planning/dragLivraison',
                    {
                        decalageX: stop[0] - start[0],
                        achatId: achatId
                    },
                    function (retour) {
                        if (retour.type == 'error') {
                            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                        }
                    }, 'json'
                    );
        }
    };
    function refreshPlanningUI() {
        $(".resizable").resizable(resizeOption);
        $(".affectation.draggable").draggable(dragOption);
        $(".livraison.draggable").draggable(dragOptionLivraison);
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
    function indispoRAZ() {
        $('#addIndispoId').val('');
        $('#addIndispoPersonnelsIds option').prop('selected', false);
        $('#addIndispoPersonnelsIds').selectpicker('refresh');
        $('#addIndispoNbDemi option[value="1"]').prop('selected', true);
        $('#btnSubmitFormIndispo').html('<i class="fas fa-plus-square"></i> Ajouter');
        $('#modalAddIndispo .modal-title').text('Ajouter une indisponibilité');
        $('#btnDelIndispo').hide();
    }

    $('.cell.matin, .cell.aprem').on('click', function () {
        affectationRAZ();
        indispoRAZ();

        var caseJour = $(this);

        $('#addAffectationPersonnelsIds option[value="' + caseJour.closest('tr').attr('data-personnelid') + '"]').prop('selected', true);
        $('#addAffectationPersonnelsIds').selectpicker('refresh');
        $('#addIndispoPersonnelsIds option[value="' + caseJour.closest('tr').attr('data-personnelid') + '"]').prop('selected', true);
        $('#addIndispoPersonnelsIds').selectpicker('refresh');
        var jour = $('#tablePlanning tbody').children('tr').eq(1).children('td').eq(Math.floor(caseJour[0].cellIndex / 2)).attr('data-jour');
        $('#addAffectationDebutDate').val(jour);
        $('#addAffectationFinDate').val(jour);
        /* Initialisation en arriere plan de la form indispo */
        $('#addIndispoDebutDate').val(jour);
        $('#addIndispoFinDate').val(jour);

        if (caseJour.hasClass('matin')) {
            $('#addAffectationDebutMoment option[value="1"]').prop('selected', true);
            $('#addAffectationFinMoment option[value="1"]').prop('selected', true);
            $('#addIndispoDebutMoment option[value="1"]').prop('selected', true);
            $('#addIndispoFinMoment option[value="1"]').prop('selected', true);
        } else {
            $('#addAffectationDebutMoment option[value="2"]').prop('selected', true);
            $('#addAffectationFinMoment option[value="2"]').prop('selected', true);
            $('#addIndispoDebutMoment option[value="2"]').prop('selected', true);
            $('#addIndispoFinMoment option[value="2"]').prop('selected', true);
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

    function majNbHeuresAffectation() {
        $.post(chemin + 'planning/getNbHeuresFormAffectation', {personnelId: $('#addAffectationPersonnelsIds').val()[0], debutDate: $('#addAffectationDebutDate').val(), debutMoment: $('#addAffectationDebutMoment').val(), finDate: $('#addAffectationFinDate').val(), finMoment: $('#addAffectationFinMoment').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#addAffectationNbHeures').val(retour.nbHeures);
                    break;
            }
        }, 'json')
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
        majNbHeuresAffectation();
    });

    $('#addAffectationPersonnelsIds').on('change', function () {
        $(this).selectpicker('refresh');
        majNbHeuresAffectation();
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
        majNbHeuresAffectation();
    });

    $('#addAffectationNbHeures').on('change', function () {
        $.post(chemin + 'planning/getFinAffectationWithNbHeures', {personnelId: $('#addAffectationPersonnelsIds').val()[0], debutDate: dateToTimestamp($('#addAffectationDebutDate').val()), debutMoment: $('#addAffectationDebutMoment').val(), nbHeures: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#addAffectationNbDemi').val(retour.nbDemi);
                    $('#addAffectationFinDate').val(retour.finDate);
                    $('#addAffectationFinMoment').val(retour.finMoment);
                    break;
            }
        }, 'json')
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
                    $('#textAffectationClient').html('<a href="' + chemin + 'clients/ficheClient/' + retour.client.clientId + '">' + retour.client.clientNom + '</a>');
                    $('#textAffectationAffaire').html('<a href="' + chemin + 'affaires/ficheAffaire/' + retour.affaire.affaireId + '">' + retour.affaire.affaireObjet + '</a>');
                    $('#textAffectationPersonnel').html('<a href="' + chemin + 'personnels/fichePersonnel/' + retour.personnel.personnelId + '">' + retour.personnel.personnelNom + '</a>');
                    $('#textAffectationChantier').html('<a href="' + chemin + 'chantiers/ficheChantier/' + retour.chantier.chantierId + '">' + retour.chantier.chantierObjet + '</a>');

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

    $('.cellLivraison').on('click', '.livraison', function (e) {
        e.stopPropagation();
        $('#masquePlanning').fadeIn(300);
        $('.affectation, .livraison').css('z-index', 2);
        $(this).css('z-index', 15);
        /* Surbrillance des affectations contraintes */
        var affectations = $(this).attr('data-contraintes').split(",");
        for (i = 0; affectations.length > i; i++) {
            var affectation = $('div.affectation[data-affectationid="' + affectations[i] + '"]');
            affectation.css('z-index', 15);
        }
    });

    $('.cellLivraison').on('dblclick', '.livraison', function (e) {
        e.stopPropagation();
        /* Recupération des contraintes et affectations liées */
        $.post(chemin + 'planning/returnModalLivraion/', {achatId: $(this).attr('data-achatid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#modalAffichageLivraison').modal('show');
                    $('#modalAffichageLivraison .modal-body').html(retour.contraintes);
                    $('#modalAffichageLivraison .modal-title').html(retour.titre);
                    $('#selectionContraintes').selectpicker('refresh');
                    break;
            }
        }, 'json');
    });

    $('#masquePlanning').on('click', function () {
        $(this).fadeOut(200);
        $('.affectation, .livraison').css('z-index', 2);
        $('.livraison').popover('hide');
        $('#modalAffchageLivraison').modal('hide');
    });

    $('#modalAffichageLivraison').on('click', '#btnSaveContraintes', function () {
        elemAchat = $('.livraison[data-achatid="' + $(this).attr('data-achatid') + '"]');
        elemCaseLivraison = elemAchat.closest('td');
        $.post(chemin + 'planning/saveContraintes', {achatId: $(this).attr('data-achatid'), contraintes: $('#selectionContraintes').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    elemAchat.remove();
                    elemCaseLivraison.append(retour.achatHTML);
                    $('#modalAffichageLivraison .modal-body').html(retour.contraintes);
                    $('#selectionContraintes').selectpicker('refresh');
                    refreshPlanningUI();
                    break;
            }
        }, 'json');
    });

    function resetFormLivraison() {
        $('#addLivraisonAchatId option[value!="0"]').remove();
        $('#addLivraisonAchatId').prop('disabled', true);
        $('#addLivraisonAchatId').selectpicker('refresh');
        $('#addLivraisonChantierId option').prop('selected', false);
        $('#addLivraisonChantierId').selectpicker('refresh');
    }

    $('.cellLivraison').on('click', function () {
        resetFormLivraison();
        caseAddLivraison = $(this);
        var jour = $('#tablePlanning tbody').children('tr').eq(1).children('td').eq(caseAddLivraison[0].cellIndex).attr('data-jour');
        $('#addLivraisonDate').val(jour);
        $('#addAchatDate').val(jour);
        $('#modalAddLivraison').modal('show');
    });

    $('#addLivraisonChantierId').on('change', function () {
        /* Récupération des achats sans date de livraison pour ce chantier */
        $('#addLivraisonAchatId').prop('disabled', false)
        $.post(chemin + 'chantiers/listeAchatsChantier', {chantierId: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#addLivraisonAchatId option[value!="0"]').remove();
                    $('#addLivraisonAchatId option').prop('selected', false);
                    for (i = 0; i < retour.achats.length; i++) {
                        if (!retour.achats[i].achatLivraisonDate) {
                            $('#addLivraisonAchatId').prepend('<option value="' + retour.achats[i].achatId + '"data-content="<span class=\'selectpickerAchat\'>' + retour.achats[i].achatDescription + '</span>">' + retour.achats[i].achatDescription + '</option>');
                        } else {
                            $('#addLivraisonAchatId').prepend('<option disabled value="' + retour.achats[i].achatId + '"data-content="<span class=\'selectpickerAchat\'>' + retour.achats[i].achatDescription + '</span> <span class=\'selectpickerAnnotation\'>' + 'Livraison prévue le ' + retour.achats[i].achatLivraisonDate + '</span>">' + retour.achats[i].achatDescription + '</option>');
                        }
                    }
                    $('#addLivraisonAchatId').selectpicker('refresh');
                    break;
            }
        }, 'json');
    });

    $('#addLivraisonAchatId').on('change', function () {
        if ($(this).val() === '0') {
            /* Si on selectionne l'ajout d'un achat */
            var jour = $('#tablePlanning tbody').children('tr').eq(1).children('td').eq(caseAddLivraison[0].cellIndex).attr('data-jour');
            $('#addAchatChantierId').val($('#addLivraisonChantierId').val());
            $('#addAchatDate').val(jour);
            $('#addAchatLivraisonDate').val(jour);
            $('#modalAddAchat').modal('show');
            $('#modalAddLivraison').modal('hide');
        }
    });

    $('#formAddLivraison').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'planning/addDateLivraison', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    caseAddLivraison.append(retour.achatHTML);
                    $('#modalAffichageLivraison .modal-body').html(retour.contraintes);
                    $('#selectionContraintes').selectpicker('refresh');
                    refreshPlanningUI();
                    break;
            }
        }, 'json');
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
                    caseAddLivraison.append(retour.achatHTML);
                    $('#modalAddAchat').modal('hide');
                    break;
            }
        }, 'json');
    });

    $('#btnAddIndispo').on('click', function () {
        $('#modalAddAffectation').modal('hide');
        $('#modalAddIndispo .modal-title').html('Ajouter une indisponibilité');
        $('#modalAddIndispo').modal('show');
    });

    /* Indispo */
    $('#addIndispoDebutDate, #addIndispoDebutMoment, #addIndispoNbDemi').on('change', function () {
        var timeStart = dateToTimestamp($('#addIndispoDebutDate').val());
        /* Calcul du demi de fin */
        if (Math.floor($('#addIndispoNbDemi').val() % 2) === 1) {
            $('#addIndispoFinMoment').val($('#addIndispoDebutMoment').val());
        } else {
            if ($('#addIndispoDebutMoment').val() === '2') {
                var demiFin = 1;
            } else {
                var demiFin = 2;
            }
            $('#addIndispoFinMoment').val(demiFin);
        }
        /* Calcul du timestamp de fin */
        var timeEnd = timeStart;
        var nbDemiRestant = $('#addIndispoNbDemi').val();
        /* Fin du premier jour */
        if ($('#addIndispoDebutMoment').val() === '1') {
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
        $('#addIndispoFinDate').val(timestampToDate(timeEnd));
    });
    $('#addIndispoFinDate, #addIndispoFinMoment').on('change', function () {
        /* On recalcule le nombre de demi journées ouvrées entre la nouvelle date de fin et la date de début */
        var timeStart = dateToTimestamp($('#addIndispoDebutDate').val());
        var timeEnd = dateToTimestamp($('#addIndispoFinDate').val());
        var nbDemiAffect = 0;
        if ($('#addIndispoDebutMoment').val() === '2') {
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
        if ($('#addIndispoFinMoment').val() === '1' && numeroJour(timeStart) > 0 && numeroJour(timeStart) < 6) {
            nbDemiAffect--;
        }
        $('#addIndispoNbDemi').val(nbDemiAffect);
    });

    $('#formAddIndispo').on('submit', function (e) {
        e.preventDefault();
        if ($("#addIndispoPersonnelsIds option:selected").length < 1) {
            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'Vous devez sélectionner au moins 1 personnel'});
        } else {
            if (parseInt($('#addIndispoId').val()) > 0 && $("#addIndispoPersonnelsIds option:selected").length > 1) {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'Lors de la modification d\'une indisponibilité, vous ne pouvez selectionner qu\'1 personnel unique'});
            } else {

                var donnees = $(this).serialize();
                $.post(chemin + 'personnels/addIndisponibilite', donnees, function (retour) {
                    switch (retour.type) {
                        case 'error':
                            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                            break;
                        case 'success':
                            console.log(retour.HTML);
                            if (parseInt($('#addIndispoId').val()) > 0) {
                                $('.indispo[data-indispoid="' + $('#addIndispoId').val() + '"]').remove();
                            }
                            $('#modalAddIndispo').modal('hide');
                            indispoRAZ();
                            $('#divPlanning').append(retour.HTML);
                            refreshPlanningUI();
                            break;
                    }
                }, 'json');
            }
        }
    });
    $('#divPlanning').on('click', '.planningIndispoText', function () {
        var indispoId = $(this).parent('div').attr('data-indispoid');
        $.post(chemin + 'personnels/getIndisponibiliteDetails', {indispoId: indispoId}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    indispoRAZ();

                    $('#addIndispoId').val(retour.indispo.indispoId);
                    $('#addIndispoPersonnelsIds option[value="' + retour.indispo.indispoPersonnelId + '"]').prop('selected', true);
                    $('#addIndispoPersonnelsIds').selectpicker('refresh');
                    $('#addIndispoNbDemi option[value="' + retour.indispo.indispoNbDemi + '"]').prop('selected', true);
                    $('#addIndispoDebutDate').val(timestampToDate(retour.indispo.indispoDebutDate));
                    $('#addIndispoDebutMoment option[value="' + retour.indispo.indispoDebutMoment + '"]').prop('selected', true);
                    $('#addIndispoFinDate').val(timestampToDate(retour.indispo.indispoFinDate));
                    $('#addIndispoFinMoment option[value="' + retour.indispo.indispoFinMoment + '"]').prop('selected', true);
                    $('#addIndispoMotifId option[value="' + retour.indispo.indispoMotifId + '"]').prop('selected', true);
                    $('#addIndispoMotifId').selectpicker('refresh');
                    $('#btnSubmitFormIndispo').html('<i class="fas fa-edit"></i> Modifier');
                    $('#modalAddIndispo .modal-title').text('Modifier une indisponibilité');
                    $('#btnDelIndispo').show();
                    $('#modalAddIndispo').modal('show');
                    break;
            }
        }, 'json');
    });
    $('#divPlanning').on('dblclick', '.indispo', function () {
        var indispo = $(this);
        $.post(chemin + 'personnels/indisponibiliteToggleAffichage', {indispoId: indispo.attr('data-indispoid'), ligne: indispo.attr('data-ligne')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    indispo.remove()
                    $('#divPlanning').append(retour.html);
                    refreshPlanningUI();
                    break;
            }
        }, 'json');
    });
    $('#btnDelIndispo').confirm({
        columnClass: 'medium',
        title: 'Suppression de cette indisponibilité ?',
        content: 'Êtes-vous sûr(e) ?',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {
                    $.post(chemin + 'personnels/delIndisponibilite', {indispoId: $('#addIndispoId').val()}, function (retour) {
                        switch (retour.type) {
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                break;
                            case 'success':
                                $('.indispo[data-indispoid="' + $('#addIndispoId').val() + '"]').remove();
                                indispoRAZ();
                                $('#modalAddIndispo').modal('hide');
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

    $('#btnCutAffectation').on('click', function () {
        $.post(chemin + 'planning/couperAffectation', {affectationId: $('#addAffectationId').val(), couperDate: $('#couperDate').val(), couperMoment: $('#couperMoment').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    if (parseInt($('#addAffectationId').val()) > 0) {
                        $('.affectation[data-affectationid="' + $('#addAffectationId').val() + '"]').remove();
                    }
                    $('#modalAffectation').modal('hide');
                    affectationRAZ();
                    $('#divPlanning').append(retour.HTML);
                    refreshPlanningUI();
                    break;
            }
        }, 'json');
    });
    $('#btnDecaleAffectationFutur').on('click', function () {
        $.post(chemin + 'planning/deplaceAffectation', {affectationId: $('#addAffectationId').val(), cible: $('#decalageCible').val(), decalage: $('#decalageQte').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    for (i = 0; i < retour.eraseIds.length; i++) {
                        $('.affectation[data-affectationid="' + retour.eraseIds[i] + '"]').remove();
                    }
                    $('#divPlanning').append(retour.HTML);
                    refreshPlanningUI();
                    $('#modalAffectation').modal('hide');
                    break;
            }
        }, 'json');
    });
    $('#btnDecaleAffectationPasse').on('click', function () {
        $.post(chemin + 'planning/deplaceAffectation', {affectationId: $('#addAffectationId').val(), cible: $('#decalageCible').val(), decalage: '-' + $('#decalageQte').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    for (i = 0; i < retour.eraseIds.length; i++) {
                        $('.affectation[data-affectationid="' + retour.eraseIds[i] + '"]').remove();
                    }
                    $('#divPlanning').append(retour.HTML);
                    refreshPlanningUI();
                    $('#modalAffectation').modal('hide');
                    break;
            }
        }, 'json');
    });
    
    $('#btnSMS').on('click', function(){
        $('#modalSMS').modal('show');
    });

    $('.connectPersonnel').on('click', function () {
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
                    $(this).show();
                    $('#loaderConnect').hide();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'light/saisie');
                    break;
            }
        }, 'json');
    });

});


