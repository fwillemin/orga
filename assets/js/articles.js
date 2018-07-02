$(document).ready(function () {

    $('#tableComposants').bootstrapTable({
        idField: 'composantId',
        url: chemin + 'articles/getAllComposants',
        pagination: true,
        search: true,
        cardView: false,
        showColumns: true,
        pageSize: 25,
        contextMenu: '#context-menu',
        onClickRow: function(row){ window.location.assign( chemin + 'articles/ficheComposant/' + row.composantId)},
        columns: [[{
                    field: 'composantReference',
                    title: 'Ref',
                    width: 60,
                    align: 'center'
                }, {
                    field: 'composantDesignation',
                    title: 'Designation',
                    width: 400,
                    sortable: true
                }, {
                    field: 'composantFamille',
                    title: 'Famille',
                    sortable: true,
                    width: 60
                }, {
                    field: 'composantUnite',
                    title: 'Unite',
                    sortable: true,
                    width: 40
                }
            ]
        ]
    });

    $('#tableArticles').bootstrapTable({
        idField: 'articleId',
        url: chemin + 'articles/getAllArticles',
        pagination: true,
        search: true,
        cardView: false,
        showColumns: true,
        pageSize: 25,
        contextMenu: '#context-menu',
        onClickRow: function(row){ window.location.assign( chemin + 'articles/ficheArticle/' + row.articleId)},
        columns: [[{
                    field: 'articleId',
                    title: 'ID',
                    width: 30,
                    align: 'center'
                }, {
                    field: 'articleDesignation',
                    title: 'Designation',
                    width: 400,
                    sortable: true
                }, {
                    field: 'articleFamille',
                    title: 'Famille',
                    sortable: true,
                    width: 60
                }
            ]
        ]
    });

    /* Formatage des cellules de la table */

    function prixFormatter(value) {
        if (value) {
            return '€ ' + value;
        } else {
            return '';
        }
    }
    function typeFormatter(value) {
        if (value == 1) {
            return 'Produit';
        } else {
            return 'Prestation';
        }
    }

    /* Actions du menu contextuel sur les composants */
    function contextActionsComposant(row, $el) {
        switch ($el.data("item")) {

            case 'fiche':
                /* Accès à la fiche client */
                window.location.assign(chemin + 'articles/ficheComposant/' + row.composantId);
                break;
        }
    }
    /* Actions du menu contextuel sur les articles */
    function contextActionsArticle(row, $el) {
        switch ($el.data("item")) {

            case 'fiche':
                /* Accès à la fiche client */
                window.location.assign(chemin + 'articles/ficheArticle/' + row.articleId);
                break;
        }
    }

    /* ----------------------------------- */

    $('.setFamille').on('change', function () {
        $.post(chemin + 'articles/manageFamilles', {addFamilleId: $(this).closest('tr').attr('data-familleid'), addFamilleNom: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="far fa-thumbs-up-o"></i> OK</strong>', message: '<br>Famille modifiée'});
                    break;
            }
        }, 'json');
    });

    $('#formAddFamille').on('submit', function (e) {
        e.preventDefault();
        $.post(chemin + 'articles/manageFamilles', {addFamilleNom: $('#addFamilleNom').val()}, function (retour) {
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

    $('.delFamille').on('dblclick', function () {
        var ligne = $(this).closest('tr');
        $.post(chemin + 'articles/delFamille', {familleId: ligne.attr('data-familleid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    ligne.remove();
                    $.toaster({priority: 'success', title: '<strong><i class="far fa-thumbs-up-o"></i> OK</strong>', message: '<br>Famille supprimée'});
                    break;
            }
        }, 'json');
    });

    function composantRAZ() {
        $('#addComposantDesignation').val('');
        $('#addComposantDescription').val('');
    }
    
    $('#btnDelComposant').on('dblclick', function(){
        $.post( chemin + 'articles/delComposant', {composantId: $('#addComposantId').val()}, function(retour){
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload( chemin + 'articles/composantsListe' );
                    break;
            }
        }, 'json');
    });
    
    $('#btnCopyComposant').on('dblclick', function(){
        $.post( chemin + 'articles/copyComposant', {composantId: $('#addComposantId').val()}, function(retour){
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign( chemin + 'articles/ficheComposant/' + retour.composantId );
                    break;
            }
        }, 'json');
    });
    
    $('#btnCopyArticle').on('dblclick', function(){
        $.post( chemin + 'articles/copyArticle', {articleId: $('#addArticleId').val()}, function(retour){
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign( chemin + 'articles/ficheArticle/' + retour.articleId );
                    break;
            }
        }, 'json');
    });
    
    $('#btnElevationComposant').on('dblclick', function(){
        
    });

    $('#btnAddComposant').on('click', function () {
        composantRAZ();
    });

    $('#formAddComposant').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post( chemin + 'articles/manageComposant', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign( chemin + 'articles/ficheComposant/' + retour.composantId );
                    break;
            }
        }, 'json');
    });

    $('#formAddArticle').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'articles/manageArticle', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign( chemin + 'articles/ficheArticle/' + retour.articleId );
                    break;
            }
        }, 'json');
    });

    $('.ligneOption').on('click', function () {
        
        $('.ligneOption').css('background-color', '#FFF');
        $(this).css('background-color', 'orange');

        $('#addOptionId').val($(this).attr('data-optionid'));
        $('#addOptionReference').val($(this).children('td').eq(0).text());
        $('#addOptionNom').val($(this).children('td').eq(1).text());
        $('#addOptionPrixCatalogue').val($(this).children('td').eq(2).text());
        remise = $(this).children('td').eq(3).text();
        $('#addOptionRemise').val( remise.substr(0, remise.length -1 ) );
        $('#addOptionPrixAchatNet').val( $(this).children('td').eq(4).text() );
        $('#addOptionCoefficient').val($(this).children('td').eq(5).text());
        $('#addOptionVente').val($(this).children('td').eq(6).text());
        $('#addOptionActive').prop('checked', parseInt($(this).children('td').eq(7).attr('data-optionactive')));
        
    });

    function calculPrixVente() {
        $('#addOptionVente').val(
                Math.round( $('#addOptionPrixAchatNet').val() * $('#addOptionCoefficient').val() * 100) / 100
                );
    }
    
    function calculPrixAchatNet() {
        $('#addOptionPrixAchatNet').val(
                Math.round( $('#addOptionPrixCatalogue').val() * ( 100 - $('#addOptionRemise').val() ) ) / 100
                );
    }

    $('#addOptionCoefficient').on('change', function () {
        calculPrixVente();  
    });
    
    $('#addOptionPrixCatalogue, #addOptionRemise').on('change', function () {        
        calculPrixAchatNet();
        calculPrixVente();
    });
    
    $('#addOptionPrixAchatNet').on('change', function() {
        $('#addOptionPrixCatalogue, #addOptionRemise').val('');
        calculPrixVente();
    });
    
    $('#addOptionVente').on('change', function () {
        $('#addOptionCoefficient').val(
                Math.round($(this).val() / $('#addOptionAchatNet').val() * 100) / 100
                );
        calculPrixVente();
    });

    function optionRAZ() {
        $('#addOptionId').val('');
        $('#addOptionNom').val('');
        $('#addOptionPrixAchatNet').val('');
        $('#addOptionPrixCatalogue').val('');
        $('#addOptionRemise').val('');
        $('#addOptionVente').val('');
        $('#addOptionActive').prop('checked', true);
        $('.ligneOption').css('background-color', '#FFF');
    }

    $('#btnEraseFormOption').on('click', function () {
        optionRAZ();
    });

    $('.delOption').on('dblclick', function () {
        var ligne = $(this).closest('tr');
        $.post(chemin + 'articles/delOption', {optionId: ligne.attr('data-optionid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    ligne.remove();
                    $.toaster({priority: 'success', title: '<strong><i class="far fa-thumbs-up-o"></i> OK</strong>', message: '<br>' + 'Option supprimée'});
                    break;
            }
            optionRAZ();
        }, 'json');
    });

    /* Composition de l'article */
    $('#composantChoix').on('change', function () {        
        $.post(chemin + 'articles/getOptions', {composantId: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#uniteComposant').text( $(this).attr('data-composantunite') );
                    $('#optionChoix option').remove();
                    for (i = 0; i < retour.options.length; i++) {
                        $('#optionChoix').append('<option value="' + retour.options[i].optionId + '">' + retour.options[i].optionNom + ' (' + retour.options[i].optionHT + '€)</option>');
                    }
                    $('#optionChoix').selectpicker('refresh');
                    break;
            }
        }, 'json');
    });

    $('#formAddComposition').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'articles/manageCompositions', donnees, function (retour) {
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

    $('.modCompositionQte').on('change', function () {
        var input = $(this);
        $.post(chemin + 'articles/manageCompositions', {modCompositionId: input.closest('tr').attr('data-compositionid'), modCompositionQte: input.val()}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    input.css('background-color', 'greenyellow');
                    input.closest('tr').children('td').eq(4).text('-');
                    needUpdateArticle()
                    break;
            }
        }, 'json');
    });

    function needUpdateArticle() {
        $('#totalArticle').text('');
        $('#totalArticle').append('<a href="' + chemin + 'articles/ficheArticle/' + $('#addCompositionArticleId').val() + '" style="color:orangered;"><i class="fa fa-refresh"></i> Rafraichir</a>');
    }

    $('.delComposition').on('dblclick', function () {
        var ligne = $(this).closest('tr');
        $.post(chemin + 'articles/delComposition', {compositionId: ligne.attr('data-compositionid')}, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    ligne.remove();
                    needUpdateArticle()
                    break;
            }
        }, 'json');
    });
    
    $('#btnDelArticle').on('dblclick', function(){
        $.post( chemin + 'articles/delArticle', {articleId: $('#addArticleId').val()}, function(retour){
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.reload( chemin + 'articles/articlesListe' );
                    break;
            }
        }, 'json');
    });

});

