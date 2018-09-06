$(document).ready(function () {

    $('#rowPlanning').fadeIn();
    $('#rowLoading').fadeOut();

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
        if( $(this).prop('checked') === true ){
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

});

