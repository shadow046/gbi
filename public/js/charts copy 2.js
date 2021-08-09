var dates;
var Store;
var Plant;
var Office;
$(document).ready(function()
{
    $.ajax({
        type: "GET",
        url: "agings",
        success: function(data){
            var dates = data.dates;
            var Store = data.str;
            var Plant = data.plnt;
            var Office = data.ofc
            // var console.log(data.plnt);
     
            console.log(Plant);
    var barChartData = {
        labels: dates,
        datasets: [
            {
               
                label: 'Store',
                fill: false,
                borderColor: '#D4CFCF',
                data: Store,
            },
            {
                label: 'Plant',
                fill: false,
                borderColor: '#E88406',
                data: Plant,
            },
            {
                label: 'Office',
                fill: false,
                borderColor: '#4496F3',
                data: Office,
                datalabels: {
                    align: 'end',
                    anchor: 'end'
                }
            }
        ]
    };

        var ctx = $('#dailyChart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: false,
                title: {
                    display: true,
                    text: 'Daily Tickets'
                },
            }

            // type: 'line',
            // data: barChartData,
            // options: {
            //     plugins: {
            //     datalabels: {
            //         backgroundColor: function(context) {
            //         return context.dataset.backgroundColor;
            //         },
            //         borderRadius: 4,
            //         color: 'white',
            //         font: {
            //         weight: 'bold'
            //         },
            //         formatter: Math.round,
            //         padding: 6
            //     }
            //     },

            //     // Core options
            //     aspectRatio: 5 / 3,
            //     layout: {
            //     padding: {
            //         top: 32,
            //         right: 16,
            //         bottom: 16,
            //         left: 8
            //     }
            //     },
            //     elements: {
            //     line: {
            //         fill: false,
            //         tension: 0.4
            //     }
            //     },
            //     scales: {
            //     y: {
            //         stacked: true
            //     }
            //     }
            // }
        });
      }
    });
});