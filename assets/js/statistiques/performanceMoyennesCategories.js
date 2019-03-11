$(document).ready(function () {

    var graphPerformances = document.getElementById("graphPerformancesCategories").getContext('2d');
    var graph = new Chart(graphPerformances, {
        type: 'horizontalBar',
        title: 'Performances moyennes par catégories',
        data: {
            labels: $('#graphPerformancesCategories').attr('chart-labels').split(','),
            datasets: [{
                    data: $('#graphPerformancesCategories').attr('chart-performances').split(','),
                    backgroundColor: $('#graphPerformancesCategories').attr('chart-backgroundcolors').split(','),
                    borderWidth: 2,
                    label: 'Gain / Perte'
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Performances moyennes par catégories',
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
                            labelString: '% Gain / Perte de temps'
                        }
                    }],
                yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Catégories'
                        },
                        ticks: {
                            position: 0
                        }
                    }]
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var label = tooltipItem.yLabel;
                        
                        if (label) {
                            label += ': ';
                        }
                        label += tooltipItem.xLabel + '%';
                        return label;
                    }
                }
            }
        }
    });

    $('#graphPerformancesCategories').on('click', function (evt) {
        var activePoints = graph.getElementsAtEvent(evt);
        if (activePoints[0]) {
            var chartData = activePoints[0]['_chart'].config.data;
            var idx = activePoints[0]['_index'];

            var label = chartData.labels[idx];
            var value = chartData.datasets[0].data[idx];

            $.post(chemin + 'statistiques/performancesMoyennesCategoriesDetails', {indexCategorie: idx}, function (retour) {
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

