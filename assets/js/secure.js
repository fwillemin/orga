$(document).ready(function () {

    $('#formLogin').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'secure/tryLogin', donnees, function (retour) {
            switch (retour.type) {
                case 'success':
                    window.location.assign( chemin + 'planning/base');
                    break;
                default:
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json')
    });

});