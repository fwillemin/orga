$(document).ready(function () {

    $('#modalAddFournisseur').modal();

    $('#tableFournisseurs').DataTable({
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
        },
        "fnInitComplete": function () {
            fournisseuRAZ();
            $('#tableFournisseurs').show();
        }
    });
    
    function fournisseuRAZ() {
        $('#addFournisseurId').val('');
        $('#addFournisseurNom').val('');
        $('#addFournisseurAdresse').val('');
        $('#addFournisseurCp').val('');
        $('#addFournisseurVille').val('');
        $('#addFournisseurTelephone').val('');
        $('#addFournisseurEmail').val('');        
    }

    $('#tableFournisseurs').on('click', 'tbody tr', function () {
        window.location.assign(chemin + 'fournisseurs/ficheFournisseur/' + $(this).attr('data-fournisseurid'));
    });

    $('#btnModFournisseur').on('click', function () {
        $('#containerModFournisseur').slideDown(700);
    });

    $('.formClose').on('click', function () {
        $(this).closest('.inPageForm').slideUp(300);
    })

    $('#formAddFournisseur').on('submit', function (e) {        
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'fournisseurs/addFournisseur', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'fournisseurs/ficheFournisseur/' + retour.fournisseurId);
                    break;
            }
        }, 'json');
    });

    $('#btnAddFournisseur').on('click', function () {
        fournisseuRAZ();
        $('#modalAddFournisseur').modal('show');
    });

    $('.btnDelFournisseur').on('click', function () {
        button = $(this);
        $.confirm({
            title: 'On supprime ce fournisseur ?',
            content: 'Cela supprimera les liens entre ce fournisseur et les achats qui lui sont associés.<br>Les achats ne seront pas supprimés et cela n\'affectera pas vos rentabilités',
            type: 'blue',
            theme: 'material',
            buttons: {
                confirm: {
                    btnClass: 'btn-green',
                    text: 'Supprimer',
                    action: function () {
                        $.post(chemin + 'fournisseurs/delFournisseur', {fournisseurId: $('#addFournisseurId')}, function (retour) {
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
        })
    });
    
    $('#tableFournisseurAchats tr').on('click', function(){        
        window.location.assign(chemin + 'chantiers/ficheChantier/' + $(this).attr('data-chantierid') + '/a');
    });

});

