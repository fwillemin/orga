$(document).ready(function () {

    var graphCaCumul = document.getElementById("graphCaCumul").getContext('2d');
    new Chart(graphCaCumul, {
        type: 'line',
        title: 'Analyse du chiffre d\'affaires',
        data: {
            labels: $('#graphCaCumul').attr('chart-labels').split(','),
            datasets: [
                {
                    data: $('#graphCaCumul').attr('chart-margeCumul').split(','),
                    backgroundColor: 'rgb(77, 166, 255,0.4)',
                    borderColor: 'rgb(77, 166, 255,1)',                    
                    borderWidth: 3,                    
                    label: 'Marge cumulée N'                    
                },
                {
                    data: $('#graphCaCumul').attr('chart-caCumul').split(','),
                    backgroundColor: 'rgb(255, 153, 0,0.4)',
                    borderColor: 'rgb(255, 153, 0,1)',
                    borderWidth: 3,
                    label: 'CA N'
                },
                {
                    data: $('#graphCaCumul').attr('chart-margeCumulN').split(','),
                    backgroundColor: 'rgb(51, 51, 77,0.1)',
                    borderColor: 'rgb(51, 51, 77,0.2)',
                    fill: true,
                    borderWidth: 3,
                    lineTension:0.5,
                    label: 'Marge N-1',
                    hidden: true
                    
                },
                {
                    data: $('#graphCaCumul').attr('chart-caCumulN').split(','),
                    backgroundColor: 'rgb(51, 51, 77,0.1)',
                    borderColor: 'rgb(51, 51, 77,0.2)',
                    borderWidth: 2,
                    label: 'CA N-1',
                    hidden: true
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Analyse du chiffre d\'affaires cumulé',
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
                yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'CA / Marge'
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

});

