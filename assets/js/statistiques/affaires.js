$(document).ready(function () {

    var graphAffairesParMois = document.getElementById("graphAffairesParMois").getContext('2d');
    new Chart(graphAffairesParMois, {
        type: 'bar',
        title: 'Analyse des affaires lancées par mois',
        data: {
            labels: $('#graphAffairesParMois').attr('chart-labels').split(','),
            datasets: [
                {
                    data: $('#graphAffairesParMois').attr('chart-affaires').split(','),
                    backgroundColor: 'rgb(77, 166, 255,0.5)',
                    borderColor: 'rgb(77, 166, 255,0.5)',
                    fill: true,
                    borderWidth: 3,
                    label: 'Affaires lancées N',
                    yAxisID: 'A'
                },
                {
                    data: $('#graphAffairesParMois').attr('chart-affairesN').split(','),
                    backgroundColor: 'rgb(51, 51, 77,0.1)',
                    borderColor: 'rgb(51, 51, 77,0.2)',
                    fill: true,
                    borderWidth: 3,
                    label: 'Affaires lancées N-1',
                    hidden: true,
                    yAxisID: 'A'
                },
                {
                    data: $('#graphAffairesParMois').attr('chart-cumul').split(','),
                    backgroundColor: 'rgb(51, 51, 77,0.1)',
                    borderColor: 'rgb(51, 51, 153,0.8)',
                    fill: false,
                    type: 'line',
                    borderWidth: 3,
                    lineTension: 0,
                    label: 'Affaires cumulées N',
                    yAxisID: 'B'
                },
                {
                    data: $('#graphAffairesParMois').attr('chart-cumulN').split(','),
                    backgroundColor: 'rgb(51, 51, 77,0.1)',
                    borderColor: 'rgb(51, 51, 77,0.2)',
                    fill: false,
                    type: 'line',
                    borderWidth: 3,
                    lineTension: 0,
                    label: 'Affaires cumulées N-1',
                    hidden: true,
                    yAxisID: 'B'
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Affaires lancées par mois',
                fontSize: 20,
                padding: 25
            },
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: -20,
                    bottom: 20
                }
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            scales: {
                xAxes: [{
                        scaleLabel: {
                            display: false,
                            labelString: 'Mois'
                        }
                    }],
                yAxes: [
                    {
                        id: 'A',
                        type: 'linear',
                        position: 'left',
                        display: true,
                        labelString: 'Nb Affaires',
                        scaleLabel: {
                            display: true,
                            labelString: 'nb affaires'
                        }
                    },
                    {
                        id: 'B',
                        type: 'linear',
                        position: 'right',
                        display: true,
                        labelString: 'Cumulées',
                        scaleLabel: {
                            display: true,
                            labelString: 'Cumulées'
                        }
                    }
                ]
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


});

