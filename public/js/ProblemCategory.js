$(document).on('click', '#goBtn', function () {
    $('#loading').show();
    window.location.href = '/dash/pcategory/'+$('#datefrom').val()+'/'+$('#dateto').val();
});
$(document).ready(function()
{ 
    new Chart($('#pie1Chart'), {
        type: 'pie',
        data: {
            labels: ['SOFTWARE/APPLICATION','HARDWARE','INFRASTRUCTURE','OTHERS'],
            datasets: [
                {
                    backgroundColor: [
                        "#2bcc71",
                        "#3b98db",
                        "#ff7600",
                        "#9ba5a6"
                    ],
                    data: [$('#software').val(),$('#hardware').val(),$('#infra').val(),$('#others').val()],
                }
            ]
        },
        options: {
            tooltips: {
                enabled: true
            },
            plugins: {
                datalabels: {
                formatter: (value, ctx) => {
                    return (value +"%");
                },
                color: '#41e',
                }
            },
            legend:{
                position: 'right',
                labels:{
                    fontColor: "black"
                },
            },
            animation: {
                duration: 3000,
            },
        }
    });
});