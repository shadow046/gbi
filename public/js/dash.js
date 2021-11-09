var gbitable, StoreTopIssueTable, ResTickCount;
var BtnSelected = 'Details', TopIssueLocationNameSelected = 'Store';
var serverTime = new Date();
var gbistatus;
var barChartData;
var userlogsTable;
$(document).ready(function()
{
    // $('#loading').show();
    function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    $('#loading').show();
    function updateGraph() {
        $.ajax({
            type: "GET",
            url: "piedata",
            async: false,
            success: function(data){
                new Chart($('#pieChart'), {
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
                                data: [data[0].Store,data[0].Plant,data[0].Office],
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
        $.ajax({
            type: "GET",
            url: "dashdata",
            async: false,
            success: function(data){
                const ChartLabels = [];
                const ChartStoreData= [];
                const ChartPlantData= [];
                const ChartOfficeData= [];
                for (let index = 0; index < data.data.length; index++) {
                    ChartLabels.push(data.data[index].Month);
                    ChartStoreData.push(data.data[index].Store)
                    ChartPlantData.push(data.data[index].Plant)
                    ChartOfficeData.push(data.data[index].Office)
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
        // $('#dailyChart').width($('#data').width());
        // $('#dailyChartW').width($('#dataW').width());
    }
    setTimeout(function(){
        updateGraph();
        $('#loading').hide();
    },500);
    if ($('#role').val() == "Client") {
        gbitable =
        $('table.gbiTable').DataTable({ 
            "dom": 'tip',
            "language": {
                    "emptyTable": " ",
                    // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                    "loadingRecords": "Please wait - loading..."
                },
            "pageLength": 10,
            processing: false,
            serverSide: false,
            ajax: {
                url: 'dashdata',
                statusCode: {
                    401: function() {
                        location.reload();
                    }
                }
            },
            columns: [
                { data: 'Month', name:'Month'},
                { data: 'Store', name:'Store'},
                { data: 'Plant', name:'Plant'},
                { data: 'Office', name:'Office'}
            ]
        });
    }else{
        var d = new Date();
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
                url: 'dashdata',
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
                    $('td', nRow).eq(5).text(Math.trunc(aData.Total / today.getDate()))
                }else{
                    $('td', nRow).eq(5).text(Math.trunc(aData.Total / d.getDate()))
                }
            },
            columns: [
                { data: 'Month', name:'Month'},
                { data: 'Store', name:'Store'},
                { data: 'Plant', name:'Plant'},
                { data: 'Office', name:'Office'},
                { data: 'Total', name:'Total'},
                { data: 'Total', name:'Total'}
            ]
        });
        // ResTickCount =
        // $('table.ResTickCountTable').DataTable({ 
        //     "dom": 't',
        //     "language": {
        //             "emptyTable": " ",
        //             // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
        //             "loadingRecords": "Please wait - loading..."
        //         },
        //     "pageLength": 10,
        //     "ordering": false,
        //     processing: false,
        //     async:false,
        //     serverSide: false,
        //     ajax: {
        //         url: 'ResTickCount',
        //         statusCode: {
        //             401: function() {
        //                 location.reload();
        //             }
        //         }
        //     },
        //     columns: [
        //         { data: 'Month', name:'Month'},
        //         { data: 'one', name:'one'},
        //         { data: 'two', name:'two'},
        //         { data: 'three', name:'three'},
        //         { data: 'four', name:'four'},
        //         { data: 'five', name:'five'},
        //         { data: 'morethanfive', name:'morethanfive'},
        //         { data: 'grandtotal', name:'grandtotal'},
        //     ]
        // });
        
        // setTimeout(function(){
        //     insertedRow = ResTickCount.row(2).data(),
        //     table.row(2).data(insertedRow);
        //     ResTickCount.row(3).data('','%','%','%','%','%','%','%');
        //     ResTickCount.draw(false);
        // },3000);
       
    }
    
    $('#gbiTable thead').on( 'keyup', ".column_search",function () {
        gbitable
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });
    
    function updateTime() {
        var currtime = new Date();
        var currtime1 = new Date(currtime.getTime());
        var mintime = currtime.getMinutes();
        var minsecs = currtime.getSeconds();
        if (currtime.getHours() == 0) {
            var mytime = 12;
            var am = "AM";
        }else if (currtime.getHours() > 12) {
            var mytime = currtime.getHours() - 12;
            var am = "PM";
            if (mytime < 10) {
                var mytime = '0'+mytime;
            }
        }else if (currtime.getHours() < 12) {
            var am = "AM";
            var mytime = currtime.getHours();
            if (currtime.getHours() < 10) {
                var mytime = '0'+currtime.getHours();
            }
        }else if (currtime.getHours() == 12) {
            var am = "PM";
            var mytime = currtime.getHours();
        }
        if (currtime.getMinutes() < 10) {
            var mintime = '0'+mintime;
        }
        
        if (currtime.getSeconds() < 10) {
            var minsecs = '0'+minsecs;
        }
        $('#navtime').html(mytime + ":"+ mintime + ":" + minsecs + " " + am);
        $('#loading').hide();
    }
    
    setInterval(updateTime, 1000); 
    $('#service_report').hide();
    
});
    // $('#ticketdetailsModal').modal('show');
$(document).on("click", '.DetailsBtn', function () {
    var BtnName = $(this).attr('BtnName');
    if (BtnSelected != BtnName) {
        $('.DetailsBtn[BtnName=\''+BtnSelected+'\']').removeClass('btn-secondary');
        $('.DetailsBtn[BtnName=\''+BtnSelected+'\']').toggleClass('bg-blue');
        $(this).removeClass('bg-blue');
        $(this).toggleClass('btn-secondary');
        $('#'+BtnSelected).hide()
        $('#'+BtnName).show();
        BtnSelected = BtnName;
    }
});

$(document).on('click', '#closedticketsBtn', function () {
    $('#loading').show();
    window.location.href = 'closedticket';
});
$(document).on('click', '#monthlyBtn', function () {
    $('#loading').show();
    window.location.href = 'monthlytickets';
});
$(document).on('click', '#dashboardBtn', function () {
    $('#loading').show();
    window.location.href = '/';
});

$(document).on('click', '.createBtn', function () {
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/new?tab=Service%20Report%20-%20GBI&status=For%20Verification', '_blank');
});
$(document).on('click', '#EditBtn', function () {
    var ticket = $('#TicketNumber').val();
    var status = gbistatus;
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/'+ticket+'?tab=Service%20Report%20-%20GBI&status='+gbistatus, '_blank');
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/new?tab=Service%20Report%20-%20GBI&status=For%20Verification', '_blank');
});

$(document).on("click", ".close", function () {
    // location.reload()    
});
