$(document).ready(function () {

    $('#formAddCategorie').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'categories/addCategorie', donnees, function (retour) {
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

    $('#tableCategories').on('click', 'tbody tr', function () {
        window.location.assign(chemin + 'categories/liste/' + $(this).attr('data-categorieid'));
    });

    $('#btnDelCategorie').confirm({
        title: 'On supprime cette Catégorie ?',
        content: 'Tous les chantiers liés à cette catégorie seront considérés comme <strong>Non classés</strong>',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {                    
                    $.post(chemin + 'categories/delCategorie', {categorieId: $('#addCategorieId').val()}, function (retour) {
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

