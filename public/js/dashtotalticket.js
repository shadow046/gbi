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
var monthshort = ["0","Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
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
    // setTimeout(function(){
    //     updateGraph();
    // },500);
    $('#loading').hide();
    if (pathfrom == "default") {
        var range = monthshort[parseFloat($('#dfrom').val().split("-")[1])]+'. '+parseFloat($('#dfrom').val().split("-")[2])+' - '+monthshort[parseFloat($('#dto').val().split("-")[1])]+'. '+parseFloat($('#dto').val().split("-")[2]);
        var from = new Date($('#dfrom').val().split("-")[1]+"-"+$('#dfrom').val().split("-")[2]+"-"+$('#dfrom').val().split("-")[0]);
        var to = new Date($('#dto').val().split("-")[1]+"-"+$('#dto').val().split("-")[2]+"-"+$('#dto').val().split("-")[0]);
        var Difference_In_Time = to.getTime() - from.getTime();
        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
        $('#range').text(range);
        $('#days').text(Difference_In_Days+1);
        $('#average').text(Math.trunc($('#total').text().split(",").join("")/$('#days').text()));
        console.log($('#total').text().split(",").join(""));
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
                        return (value * 100 / $('#total').text().split(",").join("")).toFixed(2) + "%";
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
        var ctx = $('#dailyChart');
        var myChart = new Chart(ctx, {
            type: 'bar',
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
                    display: false,
                    labels: {
                        display: false
                    }
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
    }else{ 
        var range = monthshort[window.location.pathname.split("/")[3].split("-")[1]]+'. '+window.location.pathname.split("/")[3].split("-")[2]+' - '+monthshort[window.location.pathname.split("/")[4].split("-")[1]]+'. '+window.location.pathname.split("/")[4].split("-")[2];
        console.log(range);
        var from = new Date(window.location.pathname.split("/")[3].split("-")[1]+"/"+window.location.pathname.split("/")[3].split("-")[2]+"/"+window.location.pathname.split("/")[3].split("-")[0]);
        var to = new Date(window.location.pathname.split("/")[4].split("-")[1]+"/"+window.location.pathname.split("/")[4].split("-")[2]+"/"+window.location.pathname.split("/")[4].split("-")[0]);
        var Difference_In_Time = to.getTime() - from.getTime();
        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
        $('#range').text(range);
        $('#days').text(Difference_In_Days+1);
        $('#average').text(Math.trunc($('#total').text().split(",").join("")/$('#days').text()));

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
                        return (value * 100 / $('#total').text().split(",").join("")).toFixed(2) + "%";
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
        var ctx = $('#dailyChart');
        var myChart = new Chart(ctx, {
            type: 'bar',
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
                    display: false,
                    labels: {
                        display: false
                    }
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
        // gbitable =
        // $('table.gbiTable').DataTable({ 
        //     "dom": 't',
        //     "language": {
        //             "emptyTable": " ",
        //             // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
        //             "loadingRecords": "Please wait - loading..."
        //         },
        //     "pageLength": 10,
        //     "ordering": false,
        //     processing: false,
        //     serverSide: false,
        //     ajax: {
        //         url: '/dashdata/'+datefrom+'/'+dateto,
        //         statusCode: {
        //             401: function() {
        //                 location.reload();
        //             }
        //         }
        //     },
        //     "fnRowCallback": function(nRow, aData) {
        //     //"createdRow": function ( nRow, aData ) {
        //         var month = moment().month(aData.Month).format("M");
        //         var d = new Date(aData.Year,month,0);
        //         var today = new Date();
        //         var curMonth = today.getMonth()+1;
        //         start++;
        //         if (start == 1) {
        //             if (window.location.pathname.split("/")[4].split("-")[1] ==  window.location.pathname.split("/")[3].split("-")[1]) {
        //                 $('td', nRow).eq(5).text(Math.trunc(aData.Tot / ((window.location.pathname.split("/")[4].split("-")[2]-window.location.pathname.split("/")[3].split("-")[2])+1)))
        //             }else if (month == window.location.pathname.split("/")[3].split("-")[1]) {
        //                 $('td', nRow).eq(5).text(Math.trunc(aData.Tot / ((d.getDate()-window.location.pathname.split("/")[3].split("-")[2])+1)))
        //             }
        //         }else{
        //             if (month == curMonth) {
        //                 $('td', nRow).eq(5).text(Math.trunc(aData.Tot / window.location.pathname.split("/")[4].split("-")[2]))
        //             }else{
        //                 $('td', nRow).eq(5).text(Math.trunc(aData.Tot / d.getDate()))
        //             }
        //         }
        //     },
        //     columns: [
        //         { data: 'Month', name:'Month'},
        //         { data: 'Store', name:'Store'},
        //         { data: 'Plant', name:'Plant'},
        //         { data: 'Office', name:'Office'},
        //         { data: 'Total', name:'Total'},
        //         { data: 'Tot', name:'Tot'}
        //     ],
        //     // "footerCallback": function(row, data, start, end, display) {
        //     //     var api = this.api(), data;
        //     //     // Remove the formatting to get integer data for summation
        //     //     var intVal = function ( i ) {
        //     //         return typeof i === 'string' ?
        //     //             i.replace(/[\$,]/g, '')*1 :
        //     //             typeof i === 'number' ?
        //     //                 i : 0;
        //     //     };
        //     //     // Total over all pages
        //     //     storesum = api
        //     //         .column( 1 )
        //     //         .data()
        //     //         .reduce( function (a, b) {
        //     //             return intVal(a) + intVal(b);
        //     //         }, 0 );

        //     //     // Update footer
        //     //     $( api.column( 1 ).footer() ).html(storesum.toLocaleString());
        //     //     officesum = api
        //     //         .column( 2 )
        //     //         .data()
        //     //         .reduce( function (a, b) {
        //     //             return intVal(a) + intVal(b);
        //     //         }, 0 );
        //     //     $( api.column( 2 ).footer() ).html(officesum.toLocaleString());
        //     //     plantsum = api
        //     //         .column( 3 )
        //     //         .data()
        //     //         .reduce( function (a, b) {
        //     //             return intVal(a) + intVal(b);
        //     //         }, 0 );
        //     //     $( api.column( 3 ).footer() ).html(plantsum.toLocaleString());
        //     //     grandtotal = api
        //     //         .column( 4 )
        //     //         .data()
        //     //         .reduce( function (a, b) {
        //     //             return intVal(a) + intVal(b);
        //     //         }, 0 );
        //     //     $( api.column( 4 ).footer() ).html(grandtotal.toLocaleString());
        //     //     $( api.column( 0 ).footer() ).html(monthshort[window.location.pathname.split("/")[3].split("-")[1]]+'. '+window.location.pathname.split("/")[3].split("-")[2]+' - '+monthshort[window.location.pathname.split("/")[4].split("-")[1]]+'. '+window.location.pathname.split("/")[4].split("-")[2]);
                
        //     // },
        //     "initComplete": function(){
        //         var count = new Array();
        //         piechart = new Chart($('#pieChart'), {
        //             type: 'pie',
        //             data: {
        //                 labels: ['Store','Office','Plant'],
        //                 datasets: [
        //                     {
        //                         backgroundColor: ["#2ecc71","#3498db","#95a5a6"],
        //                         data: [$('#TStore').html().split(",").join(""),$('#TOffice').html().split(",").join(""),$('#TPlant').html().split(",").join("")]
        //                     }
        //                 ]
        //             },
        //             options: {
        //                 tooltips: {
        //                     enabled: true
        //                 },
        //                 plugins: {
        //                     datalabels: {
        //                     formatter: (value, ctx) => {
        //                         return (value * 100 / ctx.dataset._meta[1].total).toFixed(2) + "%";
        //                     },
        //                     color: '#41e',
        //                     }
        //                 },
        //                 animation: {
        //                     duration: 3000,
        //                 },
        //                 legend:{
        //                     position: 'right',
        //                 },
        //             }
        //         });
        //     }
        // });
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