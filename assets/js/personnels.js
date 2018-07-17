$(document).ready(function () {

    $('#modalAddPersonnel').modal();

    $('#tablePersonnels').DataTable({
        pageLength: 50,
        language: {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            },
            "select": {
                "rows": {
                    _: "%d lignes séléctionnées",
                    0: "Aucune ligne séléctionnée",
                    1: "1 ligne séléctionnée"
                }
            }
        }
    });

//    console.log(window.location.pathname);
//    if (window.location.pathname == '/organibat2/index.php/personnels/liste/ajouter') {
//        $('#modalAddPersonnel').modal('show');
//    };

    $('#tablePersonnels').on('click', 'tbody tr', function () {
        window.location.assign(chemin + 'personnels/fichePersonnel/' + $(this).attr('data-personnelid'));
    });

    $('#btnModPersonnel').on('click', function () {
        $('#containerModPersonnel').slideDown(700);
    });

    $('.formClose').on('click', function () {
        $(this).closest('.inPageForm').slideUp(300);
    })

    $('#formAddPersonnel').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'personnels/addPersonnel', donnees, function (retour) {
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

    $('#btnAddPersonnel').on('click', function () {
        $('#modalAddPersonnel').modal('show');
    });
    $('#btnDelMessage').on('click', function () {
        console.log('click');
        $('#addPersonnelMessage').val('');
    });

    $('#formAddEquipe').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'personnels/addEquipe', donnees, function (retour) {
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

    $('#tableEquipes').on('click', 'tbody tr', function () {
        window.location.assign(chemin + 'personnels/equipes/' + $(this).attr('data-equipeid'));
    });

    $('#btnDelEquipe').confirm({
        title: 'On supprime cette équipe ?',
        content: 'Les personnels qui composaient cette équipe ne seront pas effacés, ils seront à nouveau "seul".',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {                    
                    $.post(chemin + 'personnels/delEquipe', {equipeId: $('#addEquipeId').val()}, function (retour) {
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

    $('.affectEquipe').on('change', function () {
        $.post(chemin + 'personnels/affectationPersonnelEquipe', {personnelId: $(this).closest('tr').attr('data-personnelid'), equipeId: $('#addEquipeId').val()}, function (retour) {
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
    
    $('#tableTauxHoraires').on('click', 'tbody tr', function () {
        window.location.assign(chemin + 'personnels/fichePersonnel/' + $('#addPersonnelId').val() + '/' + $(this).attr('data-tauxhoraireid'));
    });
    
    $('#formAddTauxHoraire').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();        
        $.post(chemin + 'personnels/addTauxHoraire', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'personnels/fichePersonnel/' + $('#addPersonnelId').val());
                    break;
            }
        }, 'json');
    });
    
    $('#btnDelTauxHoraire').confirm({
        title: 'On supprime ce taux horaire ?',
        content: 'Cela aura un impact sur les calculs de rentabilité des affaires.',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {                    
                    $.post(chemin + 'personnels/delTauxHoraire', {tauxHoraireId: $('#addTauxHoraireId').val()}, function (retour) {
                        switch (retour.type) {
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                break;
                            case 'success':
                                window.location.assign(chemin + 'personnels/fichePersonnel/' + $('#addPersonnelId').val());
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

