$(document).ready(function () {

    $('#formLogin').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'secure/tryLogin', donnees, function (retour) {
            switch (retour.type) {
                case 'success':
                    window.location.assign( chemin + 'organibat/board');
                    break;
                default:
                    $.toaster({priority: 'danger', title: '<strong><i class="glyphicon glyphicon-alert"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });

});