$(document).ready(function () {

    var graphCaParMois = document.getElementById("graphCaParMois").getContext('2d');
    new Chart(graphCaParMois, {
        type: 'bar',
        title: 'Analyse du chiffre d\'affaires',
        data: {
            labels: $('#graphCaParMois').attr('chart-labels').split(','),
            datasets: [
                {
                    data: $('#graphCaParMois').attr('chart-marge').split(','),
                    backgroundColor: 'rgb(77, 166, 255,0.5)',
                    borderColor: 'rgb(77, 166, 255,0.5)',
                    fill: true,
                    borderWidth: 3,
                    type: 'line',
                    lineTension:1,
                    label: 'Marge N'
                    
                },
                {
                    data: $('#graphCaParMois').attr('chart-ca').split(','),
                    borderColor: 'rgb(255, 153, 0,1)',
                    backgroundColor: 'rgb(255, 153, 0,0.8)',
                    borderWidth: 3,
                    label: 'CA N'
                },
                {
                    data: $('#graphCaParMois').attr('chart-margeN').split(','),
                    backgroundColor: 'rgb(51, 51, 77,0.1)',
                    borderColor: 'rgb(51, 51, 77,0.2)',
                    fill: true,
                    borderWidth: 3,
                    type: 'line',
                    lineTension:1,
                    label: 'Marge N-1',
                    hidden: true
                    
                },
                {
                    data: $('#graphCaParMois').attr('chart-caN').split(','),
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
                text: 'Analyse du chiffre d\'affaires par mois',
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

