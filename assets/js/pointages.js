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
        $.post(chemin + 'pointages/getSemaineAnnee/', {dateFormattee: $(this).datepicker('getFormattedDate')}, function (retour) {
            window.location.assign(chemin + 'pointages/heures/' + retour.semaine + '/' + retour.annee);
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
});