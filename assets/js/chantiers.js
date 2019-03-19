$(document).ready(function () {

    /* Selection de l'onglet à activer */
    switch (window.location.href.substr(window.location.href.lastIndexOf('/') + 1).charAt(0)) {
        case 'a':
            $('#tabAchats').tab('show');
            break;
        case 'h':
            $('#tabHeures').tab('show');
            break;
        case 'b':
            $('#tabBoard').tab('show');
            break;
        case 'x':
            $('#tabAnalyse').tab('show');
            break;
    }

    $('#selectCouleurChantier').colorpicker({
        color: $('#addChantierCouleur').val(),
        container: true,
        inline: true,
        useAlpha: false
    }).on('colorpickerChange colorpickerCreate', function (e) {
        $('#demoChantier').css('background-color', e.color.toRgbString());
        $('#addChantierCouleur').val('#' + e.color.toHex());
        $.post(chemin + 'affaires/getCouleurSecondaire/', {couleur: e.color.toHex()}, function (retour) {
            $('#demoChantier').css('color', retour.couleur);
        }, 'json');
    });

    $('#btnResetCouleurChantier').on('click', function () {
        $('#selectCouleurChantier').colorpicker('setValue', $('#demoChantier').attr('data-couleuraffaire'));
        $('#selectCouleurChantier').colorpicker('update');
    });

    $('#formAddChantier').on('submit', function (e) {
        $('#loaderAddChantier').show();
        $('#btnSubmitFormChantier').hide();
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'chantiers/addChantier', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $('#loaderAddChantier').hide();
                    $('#btnSubmitFormChantier').show();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'chantiers/ficheChantier/' + retour.chantierId);
                    break;
            }
        }, 'json');
    });

//    Ajouter une place depuis le formulaire d'un chantier
    $('#btnAddPlaceChantier').on('click', function () {
        $('#addPlaceAdresseChantier').val('');
        $('#modalAddPlaceChantier').modal('show');
    });

    $('#formAddPlaceChantier').on('submit', function (e) {
        e.preventDefault();
        $('#btnSubmitFormPlaceChantier').hide();
        $('#loaderAddPlaceChantier').show();
        var donnees = $(this).serialize();
        $.post(chemin + 'clients/addPlace', donnees, function (retour) {
            $('#btnSubmitFormPlaceChantier').show();
            $('#loaderAddPlaceChantier').hide();
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    $('#addChantierPlaceId').append('<option value="' + retour.place.placeId + '" selected>' + retour.place.placeAdresse + '</option>');
                    $('#addPlaceAdresseChantier').val('');
                    $('#addChantierPlaceId').selectpicker('refresh');
                    $('#modalAddPlaceChantier').modal('hide');
                    break;
            }
        }, 'json');
    });

    $('#addAchatQte, #addAchatPrix').on('change', function () {
        $('#addAchatTotal').val($('#addAchatQte').val() * $('#addAchatPrix').val())
    });
    $('#addAchatQtePrevisionnel, #addAchatPrixPrevisionnel').on('change', function () {
        $('#addAchatTotalPrevisionnel').val($('#addAchatQtePrevisionnel').val() * $('#addAchatPrixPrevisionnel').val())
    });

    function achatRAZ() {
        $('#addAchatId').val('');
        $('#addAchatDescription').val('');
        $('#addAchatQte').val('');
        $('#addAchatPrix').val('');
        $('#addAchatTotal').val('');
        $('#addAchatQtePrevisionnel').val('');
        $('#addAchatPrixPrevisionnel').val('');
        $('#addAchatTotalPrevisionnel').val('');
        $('#addAchatFournisseurId option[value="0"]').prop('selected', true);
        $('#addAchatLivraisonAvancement option[value="0"]').prop('selected', true);
        $('#addAchatLivraisonDate').val('');

        $('#tableAchats tr').removeClass('ligneSelectionnee');
        $('.js-onAchatMod').hide();
        $('#btnSubmitFormAchat').html('<i class="fas fa-plus-square"></i> Ajouter');
    }

    $('#btnAddAchat').on('click', function () {
        achatRAZ();
        $('#containerAddAchat').slideDown(700);
    });
    $('.formClose').on('click', function () {
        $(this).closest('.inPageForm').slideUp(300);
        achatRAZ();
    });

    $('#tableAchats tr.ligneClikable').on('click', function () {
        window.location.assign(chemin + 'chantiers/ficheChantier/' + $('#addAchatChantierId').val() + '/a' + $(this).attr('data-achatid'));
    });

    $('#formAddAchat').on('submit', function (e) {
        $('#loaderAddAchat').show();
        $('#btnSubmitFormAchat').hide();
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'chantiers/addAchat', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $('#loaderAddAchat').hide();
                    $('#btnSubmitFormAchat').show();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    window.location.assign(chemin + 'chantiers/ficheChantier/' + $('#addAchatChantierId').val() + '/a');
                    break;
            }
        }, 'json');
    });

    $('#btnDelAchat').confirm({
        title: 'Suppression de cet achat ?',
        content: 'Êtes-vous sûr de vouloir supprimer cet achat ?',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {
                    $.post(chemin + 'chantiers/delAchat', {achatId: $('#addAchatId').val()}, function (retour) {
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

    $('#btnDelChantier').confirm({
        title: 'Suppression du chantier ?',
        content: 'Êtes-vous sûr de vouloir supprimer ce chantier ?',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Supprimer',
                action: function () {
                    $.post(chemin + 'chantiers/delChantier', {chantierId: $('#addChantierId').val()}, function (retour) {
                        switch (retour.type) {
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                break;
                            case 'success':
                                window.location.assign(chemin + 'affaires/ficheAffaire/' + $('#addChantierAffaireId').val());
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

    $('#btnClotureChantier').confirm({
        title: 'Clôture du chantier ?',
        content: 'Une fois clôturé, il ne sera plus possible de modifier les achats, d\'ajouter ou de modifier des affectations ou encore de saisir des heures.',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Ok, je clôture !',
                action: function () {
                    $.post(chemin + 'chantiers/clotureChantier', {chantierId: $('#addChantierId').val()}, function (retour) {
                        switch (retour.type) {
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                break;
                            case 'success':
                                window.location.assign(chemin + 'chantiers/ficheChantier/' + $('#addChantierId').val());
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

    $('#btnReouvertureChantier').on('click', function () {
        btnReouverture = $(this);
    }).confirm({
        title: 'Réouverture du chantier ?',
        content: 'L\'affaire de ce chantier repassera dans l\'état "En cours"',
        type: 'blue',
        theme: 'material',
        buttons: {
            confirm: {
                btnClass: 'btn-green',
                text: 'Réouverture',
                action: function () {
                    $.post(chemin + 'chantiers/reouvertureChantier', {chantierId: btnReouverture.attr('data-chantierid')}, function (retour) {
                        switch (retour.type) {
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                                break;
                            case 'success':
                                window.location.assign(chemin + 'chantiers/ficheChantier/' + btnReouverture.attr('data-chantierid'));
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

    $('#tableResumeAchats tr.ligneClikable').on('click', function () {
        window.location.assign(chemin + 'chantiers/ficheChantier/' + $('#tableResumeAchats').attr('data-chantierid') + '/a' + $(this).attr('data-achatid'));
    });

    $('#selectChantierFiche').on('change', function () {
        if ($('#tabAnalyse').hasClass('active')) {
            window.location.assign(chemin + 'chantiers/ficheChantier/' + $(this).val() + '/x');
        } else if ($('#tabHeures').hasClass('active')) {
            window.location.assign(chemin + 'chantiers/ficheChantier/' + $(this).val() + '/h');
        } else if ($('#tabAchats').hasClass('active')) {
            window.location.assign(chemin + 'chantiers/ficheChantier/' + $(this).val() + '/a');
        } else {
            window.location.assign(chemin + 'chantiers/ficheChantier/' + $(this).val());
        }
    });

    $('.btnReAffecter').on('click', function () {
        $('#lierAffectationId').val($(this).closest('tr').attr('data-affectationid'));
        $('#suivreApresLier').prop('checked', false);
        $('#modalLierAffectationChantier').modal('show');
    });
    $('#formLierAffectation').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'planning/relierAffectation', donnees, function (retour) {
            switch (retour.type) {
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
                case 'success':
                    if ($('#suivreApresLier').prop('checked') === true) {
                        window.location.assign(chemin + 'chantiers/ficheChantier/' + $('#lierChantierId').val() + '/h');
                    } else {
                        window.location.reload();
                    }
                    break;
            }
        }, 'json');
    });

    /* GRAPHS */
    var graphChantierEtatHeures = document.getElementById("graphChantierEtatHeures").getContext('2d');
    new Chart(graphChantierEtatHeures, {
        type: 'bar',
        data: {
            labels: ["Heures du chantier"],
            datasets: [{
                    label: "Pointées",
                    data: [$('#graphChantierEtatHeures').attr('js-pointees')],
                    backgroundColor: [
                        'rgba(163, 234, 128, 0.7)'
                    ],
                    borderColor: [
                        'rgba(81, 184, 30, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: "Planifiées",
                    data: [$('#graphChantierEtatHeures').attr('js-planifiees')],
                    backgroundColor: [
                        'rgba(236, 228, 141, 0.7)'
                    ],
                    borderColor: [
                        'rgba(197, 184, 32, 1)'
                    ],
                    borderWidth: 1
                }]
        },
        options: {
            responsive: true,
            scales: {
                xAxes: [{
                        stacked: true,
                    }],
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        stacked: true
                    }]
            },
            annotation: {
                annotations: [{
                        type: 'line',
                        mode: 'horizontal',
                        scaleID: 'y-axis-0',
                        value: $('#graphChantierEtatHeures').attr('js-prevues'),
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 2,
                        label: {
                            enabled: true,
                            content: 'Heures prévues',
                            backgroundColor: ['rgba(89, 89, 89, 0.53)']
                        }
                    }]
            }
        }
    });

    var graphChantierEtatAchats = document.getElementById("graphChantierEtatAchats").getContext('2d');
    new Chart(graphChantierEtatAchats, {
        type: 'horizontalBar',
        data: {
            labels: ["Budgets du chantier"],
            datasets: [{
                    label: "Consommé",
                    data: [$('#graphChantierEtatAchats').attr('js-consomme')],
                    backgroundColor: [
                        'rgba(163, 234, 128, 0.7)'
                    ],
                    borderColor: [
                        'rgba(81, 184, 30, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: "Provisionné",
                    data: [$('#graphChantierEtatAchats').attr('js-prevu')],
                    backgroundColor: [
                        'rgba(236, 228, 141, 0.7)'
                    ],
                    borderColor: [
                        'rgba(197, 184, 32, 1)'
                    ],
                    borderWidth: 1
                }]
        },
        options: {
            responsive: false,
            scales: {
                xAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            },
            legend: {
                position: 'bottom'
            },
            annotation: {
                annotations: [{
                        type: 'line',
                        mode: 'vertical',
                        scaleID: 'x-axis-0',
                        value: $('#graphChantierEtatAchats').attr('js-budget'),
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 2,
                        label: {
                            enabled: true,
                            content: 'Budget inital',
                            backgroundColor: ['rgba(89, 89, 89, 0.53)']
                        }
                    }]
            }
        }
    });

    var graphChantierResume = document.getElementById("graphChantierResume").getContext('2d');
    var marges = $('#graphChantierResume').attr('js-datamarge').split(',');
    if (parseFloat(marges[0]) > 0) {
        colorCommercial = 'lightgreen';
    } else {
        colorCommercial = '#E92768';
    }
    if (parseFloat(marges[1]) > 0) {
        colorTempsReel = 'lightgreen';
    } else {
        colorTempsReel = '#E92768';
    }
    if (parseFloat(marges[2]) > 0) {
        colorFinChantier = 'lightgreen';
    } else {
        colorFinChantier = '#E92768';
    }
    new Chart(graphChantierResume, {
        type: 'bar',
        data: {
            labels: $('#graphChantierResume').attr('js-dataLabels').split(','),
            datasets: [{
                    label: "Achats",
                    data: $('#graphChantierResume').attr('js-dataachats').split(','),
                    backgroundColor: 'rgba(234, 197, 47, 0.7)',
                },
                {
                    label: "Main d'oeuvre",
                    data: $('#graphChantierResume').attr('js-dataheures').split(','),
                    backgroundColor: 'rgba(28, 139, 211, 0.7)',
                },
                {
                    label: "Frais généraux",
                    data: $('#graphChantierResume').attr('js-dataFG').split(','),
                    backgroundColor: 'rgba(34, 59, 75, 0.7)',
                }, {
                    label: "Marge",
                    data: marges,
                    backgroundColor: [colorCommercial, colorTempsReel, colorFinChantier],
                }]
        },
        options: {
            scales: {
                xAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        stacked: false
                    }],
                yAxes: [{stacked: true}]
            },
            legend: {
                position: 'bottom',
            },
            annotation: {
                annotations: [{
                        type: 'line',
                        mode: 'horizontal',
                        scaleID: 'y-axis-0',
                        value: $('#graphChantierResume').attr('js-chiffrage'),
                        borderColor: 'rgba(89, 89, 89, 0.9)',
                        borderWidth: 2,
                        label: {
                            enabled: true,
                            content: 'Chiffrage : ' + $('#graphChantierResume').attr('js-chiffrage') + '€',
                            backgroundColor: 'rgba(89, 89, 89, 0)',
                            fontColor: 'rgba(89, 89, 89, 0.9)',
                            yAdjust: 10,
                            position: 'left'
                        }
                    }]
            }
        }
    });


    var graphChantierParticipation = document.getElementById("graphChantierParticipations");
    if (graphChantierParticipation != null) {
        var participations = $('#graphChantierParticipations').attr('js-dataparticipation').split(',');

        new Chart(graphChantierParticipation.getContext('2d'), {
            type: 'pie',
            data: {
                labels: $('#graphChantierParticipations').attr('js-datapersonnels').split(','),
                datasets: [{
                        data: participations,
                        backgroundColor: chartBackgrounds,
                        label: '# heures'
                    }]
            },
            options: {
                legend: {
                    display: true,
                    position: 'left',
                },
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20
                    }
                }
            }
        });
    }

    var graphAnalyseChantier = document.getElementById("graphAnalyseChantier").getContext('2d');
    new Chart(graphAnalyseChantier, {
        type: 'doughnut',
        title: 'Analyse du chantier',
        data: {
            labels: $('#graphAnalyseChantier').attr('chart-labels').split(','),
            datasets: [
                {
                    data: $('#graphAnalyseChantier').attr('chart-repartition').split(','),
                    backgroundColor: chartBackgrounds,
                    borderColor: chartBackgrounds
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Analyse du chantier',
                fontSize: 20,
                padding: 25
            },
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: -20,
                    bottom: 50
                }
            },
            legend: {
                display: true,
                position: 'right'
            }
        }
    });
});

