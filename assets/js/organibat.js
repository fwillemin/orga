var path = 'http://192.168.0.1/ORGANIBAT/organibat2';
//var path = 'https://v2.organibat.com';
var chemin = path + '/index.php/';
var cheminJs = path + '/assets/js/';

function refactorDate(timeDate, type) {
    type = type || 'input';
    if (timeDate > 0) {
        var refactor = new Date(timeDate * 1000);
        var jour_refactor = refactor.getDate();
        if (jour_refactor < 10) {
            jour_refactor = '0' + jour_refactor;
        }
        ;
        var mois_refactor = refactor.getMonth() + 1;
        if (mois_refactor < 10) {
            mois_refactor = '0' + mois_refactor;
        }
        ;
        if (type == 'input') {
            return refactor.getFullYear() + '-' + mois_refactor + '-' + jour_refactor;
        } else {
            return jour_refactor + '/' + mois_refactor + '/' + refactor.getFullYear();
        }
    } else {
        return '';
    }
}

$(document).ready(function () {

    /* Affichage de la session avec ESP+ESC */
    $(document).on('keydown', function (e) {
        if (e.keyCode == 32) {
            hold = true;
        }
    });
    $(document).on('keydown', function (e) {
        if (e.keyCode == 27 && hold === true) {
            $('#modalSession').modal('show');
        }
    });

    $('.selectpicker').selectpicker();
    $('.formloader').hide();

    $(document).on('show.bs.modal', '.modal', function (event) {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function () {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    $('#formModParametres').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var donnees = $(this).serialize();
        console.log(donnees);
        $.post(chemin + 'organibat/modParametres', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="fas fa-check"></i> OK</strong>', message: '<br>' + 'Paramètres modifiés'});
                    break;
            }
        }, 'json');
    });
});