var gbitable, StoreTopIssueTable, ResTickCount;
var BtnSelected = 'Details', TopIssueLocationNameSelected = 'Store';
var serverTime = new Date();
var gbistatus;
var barChartData;
var userlogsTable;
var yearstart = '2021';
var curmonth = serverTime.getMonth()+1;
var curyear = serverTime.getFullYear();
var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var monthshort = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "August", "September", "October", "November", "December"];
var go = 0;
var count = 0;
var datefrom = 'from', dateto = 'to';
var piechart;
function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
}
function updateGraph() {
    $.ajax({
        type: "GET",
        url: "/piedata",
        data: {
            datefrom: "default",
        },
        async: false,
        success: function(data){
            piechart = new Chart($('#pieChart'), {
                type: 'pie',
                data: {
                    labels: ['Store','Plant','Office'],
                    datasets: [
                        {
                            backgroundColor: [
                                "#2ecc71",
                                "#3498db",
                                "#95a5a6"
                            ],
                            data: [data[0].Store.replace(/,/g, ''),data[0].Plant.replace(/,/g, ''),data[0].Office.replace(/,/g, '')],
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
                            console.log(ctx);
                            return (value * 100 / ctx.dataset._meta[0].total).toFixed(2) + "%";
                        },
                        color: '#41e',
                        }
                    },
                    animation: {
                        duration: 3000,
                    },
                }
            });
        }
    });
    
    if (window.location.pathname.split("/")[3]== "default") {
        $.ajax({
            type: "GET",
            url: "/dashdata/default/1",
            async: false,
            success: function(data){
                const ChartLabels = [];
                const ChartStoreData= [];
                const ChartPlantData= [];
                const ChartOfficeData= [];
                for (let index = 0; index < data.data.length; index++) {
                    ChartLabels.push(data.data[index].Month);
                    ChartStoreData.push(data.data[index].Store.replace(/,/g, ''))
                    ChartPlantData.push(data.data[index].Plant.replace(/,/g, ''))
                    ChartOfficeData.push(data.data[index].Office.replace(/,/g, ''))
                }
                var ctx = $('#dailyChart');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                    labels: ChartLabels,
                        datasets: [
                            {
                                label: 'Store',
                                backgroundColor: "#2ecc71",
                                data: ChartStoreData
                            },
                            {
                                label: 'Plant',
                                backgroundColor: "#3498db",
                                data: ChartPlantData
                            },
                            {
                                label: 'Office',
                                backgroundColor: "#95a5a6",
                                data: ChartOfficeData
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                beginAtZero: true,
                                }
                            }]
                        },
                        plugins: {
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                formatter: Math.round,
                                font: {
                                weight: 'bold'
                                }
                            }
                        },
                        legend:{
                            position: 'bottom',
                            labels:{
                                fontColor: "black"
                            },
                        },
                        layout: {
                            padding: {
                                top: 30
                            }
                        },
                        animation: {
                            duration: 3000,
                        },
                    }
                });
            }
        });
    }else{
        $.ajax({
            type: "GET",
            url: '/dashdata/'+window.location.pathname.split("/")[3]+'/'+window.location.pathname.split("/")[4],
            async: false,
            success: function(data){
                const ChartLabels = [];
                const ChartStoreData= [];
                const ChartPlantData= [];
                const ChartOfficeData= [];
                for (let index = 0; index < data.data.length; index++) {
                    ChartLabels.push(data.data[index].Month);
                    ChartStoreData.push(data.data[index].Store.replace(/,/g, ''));
                    ChartPlantData.push(data.data[index].Plant.replace(/,/g, ''));
                    ChartOfficeData.push(data.data[index].Office.replace(/,/g, ''));
                }
                var ctx = $('#dailyChart');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                    labels: ChartLabels,
                        datasets: [
                            {
                                label: 'Store',
                                backgroundColor: "#2ecc71",
                                data: ChartStoreData
                            },
                            {
                                label: 'Plant',
                                backgroundColor: "#3498db",
                                data: ChartPlantData
                            },
                            {
                                label: 'Office',
                                backgroundColor: "#95a5a6",
                                data: ChartOfficeData
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                beginAtZero: true,
                                }
                            }]
                        },
                        plugins: {
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                formatter: Math.round,
                                font: {
                                weight: 'bold'
                                }
                            }
                        },
                        legend:{
                            position: 'bottom',
                            labels:{
                                fontColor: "black"
                            },
                        },
                        layout: {
                            padding: {
                                top: 30
                            }
                        },
                        animation: {
                            duration: 3000,
                        },
                    }
                });
            }
        });
    }
    $('#loading').hide();
}
$(document).ready(function()
{
    // $('#loading').show();
    var pathfrom = window.location.pathname.split("/")[3];
    var pathto = window.location.pathname.split("/")[4];
    $('#loading').show();
    var monthoption = '<option selected disabled>from</option>';
    if (curyear == yearstart) {
        for (let index = 6; index < curmonth ; index++) {
            monthoption += '<option value="'+index+'">'+months[index-1]+'</option>';
        }
    }else{
        for (let index = 1; index <= 12 ; index++) {
            monthoption += '<option value="'+index+'">'+months[index-1]+'</option>';
        }
    }
    $("#datefrom").find('option').remove().end().append(monthoption);
    $('#dateto').prop('disabled', true);
    $('#goBtn').prop('disabled', true);
    if (pathfrom == "default") {
        $('#datefrom').val('from');
        $('#dateto').val('to');
        datefrom = pathfrom;
        dateto = pathto;
    }else{
        if (moment(pathfrom+'-01' , 'YYYY-MM-DD', true).isValid() && moment(pathto+'-01' , 'YYYY-MM-DD', true).isValid()) {
            $('#datefrom').val(pathfrom);
            $('#dateto').val(pathto);
            datefrom = pathfrom;
            dateto = pathto;
        }else{
            alert('Please do not try to alter this URL!!!')
        }
    }
    setTimeout(function(){
        updateGraph();
    },500);
    if (pathfrom == "default") {
        console.log('shoot');
        gbitable =
        $('table.gbiTable').DataTable({ 
            "dom": 't',
            "language": {
                    "emptyTable": " ",
                    // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                    "loadingRecords": "Please wait - loading..."
                },
            "pageLength": 10,
            "ordering": false,
            processing: false,
            serverSide: false,
            ajax: {
                url: '/dashdata/default/1',
                statusCode: {
                    401: function() {
                        location.reload();
                    }
                }
            },
            "fnRowCallback": function(nRow, aData) {
                var month = moment().month(aData.Month).format("M");
                var d = new Date(aData.Year,month,0);
                var today = new Date();
                var curMonth = today.getMonth()+1;
                if (month == curMonth) {
                    $('td', nRow).eq(5).text(Math.trunc(aData.Tot / today.getDate()))
                }else{
                    $('td', nRow).eq(5).text(Math.trunc(aData.Tot / d.getDate()))
                }
            },
            columns: [
                { data: 'Month', name:'Month'},
                { data: 'Store', name:'Store'},
                { data: 'Plant', name:'Plant'},
                { data: 'Office', name:'Office'},
                { data: 'Total', name:'Total'},
                { data: 'Tot', name:'Tot'}
            ]
        });
    }else{
        gbitable =
        $('table.gbiTable').DataTable({ 
            "dom": 't',
            "language": {
                    "emptyTable": " ",
                    // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                    "loadingRecords": "Please wait - loading..."
                },
            "pageLength": 10,
            "ordering": false,
            processing: false,
            serverSide: false,
            ajax: {
                url: '/dashdata/'+datefrom+'/'+dateto,
                statusCode: {
                    401: function() {
                        location.reload();
                    }
                }
            },
            "fnRowCallback": function(nRow, aData) {
            //"createdRow": function ( nRow, aData ) {
                var month = moment().month(aData.Month).format("M");
                var d = new Date(aData.Year,month,0);
                var today = new Date();
                var curMonth = today.getMonth()+1;
                if (month == curMonth) {
                    $('td', nRow).eq(5).text(Math.trunc(aData.Tot / today.getDate()))
                }else{
                    $('td', nRow).eq(5).text(Math.trunc(aData.Tot / d.getDate()))
                }
            },
            columns: [
                { data: 'Month', name:'Month'},
                { data: 'Store', name:'Store'},
                { data: 'Plant', name:'Plant'},
                { data: 'Office', name:'Office'},
                { data: 'Total', name:'Total'},
                { data: 'Tot', name:'Tot'}
            ]
        });
    }
});

// $(document).on('change', '#datefrom', function(){
//     var from = $(this).val();
//     var monthoptionto = '<option selected disabled>to</option>';
//     for (let index = from; index < curmonth ; index++) {
//         monthoptionto += '<option value="'+index+'">'+months[index-1]+'</option>';
//     }
//     $("#dateto").find('option').remove().end().append(monthoptionto);
//     $('#dateto').prop('disabled', false);
//     $('#goBtn').prop('disabled', true);
// });
// $(document).on('change', '#datefrom', function(){
//     $('#goBtn').prop('disabled', false);
// });

$(document).on('click', '#goBtn', function () {
    window.location.href = '/dash/totalticket/'+$('#datefrom').val()+'/'+$('#dateto').val();
});