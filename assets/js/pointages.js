$(document).ready(function () {

    $('#pointagePersonnelId').selectpicker();

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
        var page = $(this).attr('data-cible');
        $.post(chemin + 'pointages/getSemaineAnnee/', {dateFormattee: $(this).datepicker('getFormattedDate')}, function (retour) {
            window.location.assign(chemin + 'pointages/' + page + '/' + retour.semaine + '/' + retour.annee);
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

    $('#btnRechPeriode').on('click', function () {
        if ($('#pointagePersonnelId').val() !== '') {
            window.location.assign(chemin + 'pointages/feuilles/' + $('#pointagePersonnelId').val() + '/' + $('#pointageMois').val() + '-' + $('#pointageAnnee').val());
        } else {
            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'Selectionnez un personnel'});
        }
    });

    $('#btnReleveSave').on('click', function () {
        /* on repasse la valeur prop de chaque input dans son attr value */
        $('input').each(function () {
            $(this).attr('value', $(this).val());
        });
        /* export */
        $.post(chemin + 'pointages/addPointage/', {pointageHTML: $('#tablePointages')[0].outerHTML, personnelId: $(this).closest('div').attr('data-personnelid'), periode: $(this).closest('div').attr('data-periode')}, function (retour) {
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

    $('#btnReleveDel').confirm({
        columnClass: 'medium',
        title: 'Supprimer cette feuille de pointage enregistrée ?',
        content: 'La suppression de cette feuille de pointage entrainera l\'annulation de toutes les modifications que vous avez effectuées. <br>La feuille de pointage sera régénrée par Organibat.',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {
                    $.post(chemin + 'pointages/delPointage', {pointageId: $('#btnReleveDel').attr('data-pointageid')}, function (retour) {
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

    /* Validation rapide des heures saisies par les ouvriers en un click */
    $('.trou.unchecked').on('click', function () {
        var trou = $(this);
        var elem = $(this).next();
        // on modifie l'heure à valide
        $.post(chemin + 'pointages/quickValide/', {heureId: elem.attr('data-heureid')}, function (retour) {
            if (retour.type == 'success') {
//                $.toaster({priority: 'success', title: '<strong><i class="fas fa-check"></i> OK</strong>', message: '<br>' + 'Heures validées'});
                trou.attr('class', 'trou valide');
            }
        }, 'json');
    });

    $('.hsOK').on('click', function () {
        $.post(chemin + '/pointages/valideHS', {personnelId: $(this).closest('tr').attr('data-personnelid'), annee: $('#tableHeuresSupp').attr('data-annee'), semaine: $('#tableHeuresSupp').attr('data-semaine'), heuresSupp: $(this).closest('tr').children('td').eq(3).find('input').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json')
    });
    $('.hsIgnored').on('click', function () {
        $.post(chemin + '/pointages/deleteHS', {personnelId: $(this).closest('tr').attr('data-personnelid'), annee: $('#tableHeuresSupp').attr('data-annee'), semaine: $('#tableHeuresSupp').attr('data-semaine')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload();
                    break;
            }
        }, 'json')
    });
});