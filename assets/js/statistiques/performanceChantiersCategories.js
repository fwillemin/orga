$(document).ready(function () {
    
    $('#changeAnalyseChantiersCategorieId').on('change', function(e){
        e.preventDefault();
        e.stopPropagation();
        window.location.assign(chemin+'statistiques/performanceChantiersCategories/' + $(this).val());
    });

    var graphPerformances = document.getElementById("graphPerformances").getContext('2d');
    var graph = new Chart(graphPerformances, {
        type: 'bar',
        title: 'Performances ' + $('#graphPerformances').attr('chart-title'),
        data: {
            labels: $('#graphPerformances').attr('chart-labels').split(','),
            datasets: [{
                    data: $('#graphPerformances').attr('chart-performances').split(','),
                    backgroundColor: ['#009999', '#00b3b3', '#00cccc', '#00e6e6', '#00ffff', '#1affff', '#ffb3b3', '#ff9999', '#ff8080', '#ff6666', '#ff4d4d', '#ff3333'],
//                    backgroundColor: ['#70db70', '#85e085', '#99e699', '#adebad', '#c2f0c2', '#d6f5d6', '#ffcccc', '#ffb3b3', '#ff9999', '#ff8080', '#ff6666', '#ff4d4d'],
                    borderWidth: 2,
                    label: 'Année N'
                },
                {
                    data: $('#graphPerformances').attr('chart-performancesN').split(','),
                    backgroundColor: 'rgb(51, 51, 77,0.1)',
                    borderColor: 'rgb(51, 51, 77,0.2)',
                    borderWidth: 2,
                    label: 'Année N-1',
                    hidden: true
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Performances ' + $('#graphPerformances').attr('chart-title'),
                fontSize: 20,
                padding: 25
            },
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: 0,
                    bottom: 0
                }
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            scales: {
                xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: '% performance'
                        }
                    }],
                yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Nb chantiers'
                        },
                        ticks: {
                            position: 0
                        }
                    }]
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var label = data.datasets[tooltipItem.datasetIndex].label || '';

                        if (label) {
                            label += ': ';
                        }
                        label += Math.round(tooltipItem.yLabel * 100) / 100;
                        return label;
                    }
                }
            }
        }
    });

    $('#graphPerformances').on('click', function (evt) {
        var activePoints = graph.getElementsAtEvent(evt);
        if (activePoints[0]) {
            var chartData = activePoints[0]['_chart'].config.data;
            var idx = activePoints[0]['_index'];

            var label = chartData.labels[idx];
            var value = chartData.datasets[0].data[idx];

            $.post(chemin + 'statistiques/performancesChantiersCategoriesRangeDetails', {categorieId: $('#graphPerformances').attr('chart-categorieid'),range: idx}, function (retour) {
                $('#tableDetailsPerfs tbody tr').remove();
                for (i = 0; i < retour.chantiers.length; i++) {
                    $('#tableDetailsPerfs').append('<tr><td>'
                            + retour.chantiers[i].client + '</td>'
                            + '<td><a href="' + chemin + 'affaires/ficheAffaire/' + retour.chantiers[i].affaireId + '">'
                            + retour.chantiers[i].affaireObjet + '</a></td>'
                            + '<td><a href="' + chemin + 'chantiers/ficheChantier/' + retour.chantiers[i].chantierId + '">'
                            + retour.chantiers[i].chantierObjet + '</a></td><td>'
                            + retour.chantiers[i].chantierCategorie + '</td><td class="text-right">'
                            + retour.chantiers[i].chantierDeltaHeures + '</td><td class="text-right">'
                            + retour.chantiers[i].chantierPerformanceHeures + '</td></tr>');
                }
            }, 'json');
        }
    });

});

