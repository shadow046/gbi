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
let start = 0;
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
                const ChartBlankData= [];
                for (let index = 0; index < data.data.length; index++) {
                    ChartLabels.push(data.data[index].Month);
                    ChartStoreData.push(data.data[index].Store.replace(/,/g, ''))
                    ChartPlantData.push(data.data[index].Plant.replace(/,/g, ''))
                    ChartOfficeData.push(data.data[index].Office.replace(/,/g, ''))
                    // ChartBlankData.push(data.data[index].Blank.replace(/,/g, ''))
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
                            // {
                            //     label: 'Blank SBU',
                            //     backgroundColor: "#95cca6",
                            //     data: ChartBlankData
                            // }
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
                const ChartBlankData= [];
                for (let index = 0; index < data.data.length; index++) {
                    ChartLabels.push(data.data[index].Month);
                    ChartStoreData.push(data.data[index].Store.replace(/,/g, ''));
                    ChartPlantData.push(data.data[index].Plant.replace(/,/g, ''));
                    ChartOfficeData.push(data.data[index].Office.replace(/,/g, ''));
                    // ChartBlankData.push(data.data[index].Blank.replace(/,/g, ''));
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
                            // {
                            //     label: 'Blank SBU',
                            //     backgroundColor: "#95cca6",
                            //     data: ChartBlankData
                            // }
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
        if (moment(pathfrom, 'YYYY-MM-DD', true).isValid() && moment(pathto , 'YYYY-MM-DD', true).isValid()) {
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
                    $('td', nRow).eq(6).text(Math.trunc(aData.Tot / window.location.pathname.split("/")[4].split("-")[2]))
                }else{
                    $('td', nRow).eq(6).text(Math.trunc(aData.Tot / d.getDate()))
                }
            },
            columns: [
                { data: 'Month', name:'Month'},
                { data: 'Store', name:'Store'},
                { data: 'Plant', name:'Plant'},
                { data: 'Office', name:'Office'},
                { data: 'Total', name:'Total'},
                { data: 'Tot', name:'Tot'}
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                // Total over all pages
                storesum = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 1 ).footer() ).html(storesum.toLocaleString());
                officesum = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 2 ).footer() ).html(officesum.toLocaleString());
                plantsum = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 3 ).footer() ).html(plantsum.toLocaleString());
                grandtotal = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 4 ).footer() ).html(grandtotal.toLocaleString());
            },
            "initComplete": function(){
                piechart = new Chart($('#pieChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Store','Office','Plant'],
                        datasets: [
                            {
                                backgroundColor: ["#2ecc71","#3498db","#95a5a6"],
                                data: [$('#TStore').html().split(",").join(""),$('#TOffice').html().split(",").join(""),$('#TPlant').html().split(",").join("")]
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
                                return (value * 100 / ctx.dataset._meta[1].total).toFixed(2) + "%";
                            },
                            color: '#41e',
                            }
                        },
                        animation: {
                            duration: 3000,
                        },
                        legend:{
                            position: 'right',
                        },
                    }
                });
            }
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
                start++;
                if (start == 1) {
                    if (window.location.pathname.split("/")[4].split("-")[1] ==  window.location.pathname.split("/")[3].split("-")[1]) {
                        $('td', nRow).eq(5).text(Math.trunc(aData.Tot / ((window.location.pathname.split("/")[4].split("-")[2]-window.location.pathname.split("/")[4].split("-")[2])+1)))
                    }else if (month == window.location.pathname.split("/")[3].split("-")[1]) {
                        $('td', nRow).eq(5).text(Math.trunc(aData.Tot / ((d.getDate()-window.location.pathname.split("/")[3].split("-")[2])+1)))
                    }
                }else{
                    if (month == curMonth) {
                        $('td', nRow).eq(5).text(Math.trunc(aData.Tot / window.location.pathname.split("/")[4].split("-")[2]))
                    }else{
                        $('td', nRow).eq(5).text(Math.trunc(aData.Tot / d.getDate()))
                    }
                }
            },
            columns: [
                { data: 'Month', name:'Month'},
                { data: 'Store', name:'Store'},
                { data: 'Plant', name:'Plant'},
                { data: 'Office', name:'Office'},
                { data: 'Total', name:'Total'},
                { data: 'Tot', name:'Tot'}
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                // Total over all pages
                storesum = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 1 ).footer() ).html(storesum.toLocaleString());
                officesum = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 2 ).footer() ).html(officesum.toLocaleString());
                plantsum = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 3 ).footer() ).html(plantsum.toLocaleString());
                grandtotal = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 4 ).footer() ).html(grandtotal.toLocaleString());
            },
            "initComplete": function(){
                piechart = new Chart($('#pieChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Store','Office','Plant'],
                        datasets: [
                            {
                                backgroundColor: ["#2ecc71","#3498db","#95a5a6"],
                                data: [$('#TStore').html().split(",").join(""),$('#TOffice').html().split(",").join(""),$('#TPlant').html().split(",").join("")]
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
                                return (value * 100 / ctx.dataset._meta[1].total).toFixed(2) + "%";
                            },
                            color: '#41e',
                            }
                        },
                        animation: {
                            duration: 3000,
                        },
                        legend:{
                            position: 'right',
                        },
                    }
                });
            }
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