$(document).ready(function () {

    var graphChantiersCategories = document.getElementById("graphChantiersCategories").getContext('2d');
    new Chart(graphChantiersCategories, {
        type: 'doughnut',
        title: 'Chantiers par catégories',
        data: {
            labels: $('#graphChantiersCategories').attr('chart-labels').split(','),
            datasets: [
                {
                    data: $('#graphChantiersCategories').attr('chart-repartition').split(','),
                    backgroundColor: chartBackgrounds,
                    borderColor: chartBackgrounds
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Chantiers par catégories',
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

