var path = 'http://192.168.0.1/ORGANIBAT/organibat2';
//var path = 'https://www.organibat.com';
var chemin = path + '/index.php/';
var cheminJs = path + '/assets/js/';

$(document).ready(function () {

    $('#inscriptionRS, #inscriptionNom, #inscriptionPrenom').on('keyup', function () {
        $.post(chemin + 'showroom/getDomaine', {chaine: $('#inscriptionRS').val(), nom:$('#inscriptionNom').val(), prenom:$('#inscriptionPrenom').val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#inscriptionDomaine').val(retour.domaine);
                    break;
            }
        }, 'json');
    });

    $('#formInscription').on('submit', function (e) {
        e.preventDefault();
        $('#btnSubmitInscription').hide();
        $('#loaderInscription').show();
        var donnees = $(this).serialize();
        $.post(chemin + 'showroom/addInscription', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $('#btnSubmitInscription').show();
                    $('#loaderInscription').hide();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign('showroom/inscriptionValidee');
                    break;
            }
        }, 'json');
    });

});