$(document).ready(function () {

    var graphAffairesCategories = document.getElementById("graphAffairesCategories").getContext('2d');
    new Chart(graphAffairesCategories, {
        type: 'doughnut',
        title: 'Affaires par catégories',
        data: {
            labels: $('#graphAffairesCategories').attr('chart-labels').split(','),
            datasets: [
                {
                    data: $('#graphAffairesCategories').attr('chart-repartition').split(','),
                    backgroundColor: chartBackgrounds,
                    borderColor: chartBackgrounds
                }
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Affaires par catégories',
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

