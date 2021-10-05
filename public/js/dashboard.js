var dates;
var Store;
var Plant;
var Office;
var start = 0;
var barChartData;
var StoreTopIssueTable, userlogsTable;
$(document).ready(function()
{
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
        if (currtime.getSeconds() == 28 || currtime.getSeconds() == 58) {
            updateGraph();
        }
        $('#navtime').html(mytime + ":"+ mintime + ":" + minsecs + " " + am);
        // if (start <= 2) {
    }
    $('#userlogsTable thead').on( 'keyup', ".column_search",function () {
    userlogsTable
        .column( $(this).parent().index() )
        .search( this.value )
        .draw();
    });
    function updateGraph() {
        $.ajax({
            type: "GET",
            url: "dailyticketsdata",
            async: false,
            success: function(data){
                $('#datahead').empty();
                $('#databody').empty();
                $('#datafoot').empty();
                var datahead = '<tr><th style="font-size:12px">GBI SBU</th>';
                var databodystore = '<tr><td>Store</td>';
                var databodyplant = '<tr><td>Plant</td>';
                var databodyoffice = '<tr><td>Office</td>';
                var datafoot = '<tr><td>Grand Total</td>';
                data.dates.forEach(element => {
                    let d = new Date(element);
                    let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
                    let mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(d);
                    let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
                    datahead+='<th style="font-size:9px">&nbsp;&nbsp;&nbsp;'+`${mo}`+'&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;'+`${da}`+'&nbsp;&nbsp;&nbsp;</th>';
                });
                data.str.forEach(element => {
                    databodystore +='<td>'+element.toLocaleString()+'</td>';
                });
                data.plnt.forEach(element => {
                    databodyplant +='<td>'+element.toLocaleString()+'</td>';
                });
                data.ofc.forEach(element => {
                    databodyoffice +='<td>'+element.toLocaleString()+'</td>';
                });
                data.grandtotal.forEach(element => {
                    datafoot +='<td>'+element.toLocaleString()+'</td>';
                });
                datahead+='<th style="font-size:12px">Grand Total&nbsp;&nbsp;</th></tr>';
                databodystore +='<td>'+data.strtotal.toLocaleString()+'</td></tr>';
                databodyplant +='<td>'+data.plnttotal.toLocaleString()+'</td></tr>';
                databodyoffice +='<td>'+data.ofctotal.toLocaleString()+'</td></tr>';
                datafoot +='</tr>';
                $('#datahead').append(datahead);
                $('#databody').append(databodystore);
                $('#databody').append(databodyplant);
                $('#databody').append(databodyoffice);
                $('#datafoot').append(datafoot);
                barChartData = {
                labels: data.dates,
                datasets: [
                    {
                        label: 'Store',
                        fill: false,
                        borderColor: '#D4CFCF',
                        data: data.str,
                    },
                    {
                        label: 'Plant',
                        fill: false,
                        borderColor: '#E88406',
                        data: data.plnt,
                    },
                    {
                        label: 'Office',
                        fill: false,
                        borderColor: '#4496F3',
                        data: data.ofc,
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
                    }
                }
            });
            }
        });
        // $('#dailyChart').width($('#data').width());
        // $('#dailyChartW').width($('#dataW').width());
        $('#loading').hide();
    }
    $('#loading').show();
    setTimeout(function(){
        updateGraph();
    },3000);
    setInterval(updateTime, 1000);
    // $("#StoreTopIssueTable").append(
    //    $('<tfoot/>').append( $("#StoreTopIssueTable thead tr").clone())
    // );
    StoreTopIssueTable =
    $('table.StoreTopIssueTable').DataTable({ 
        "dom": 'itp',
        "language": {
                "emptyTable": " ",
                // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                "loadingRecords": "Please wait - loading..."
            },
        "pageLength": 10,
        "order": [[ 3, "desc" ]],
        processing: false,
        serverSide: false,
        ajax: 'storetopissue',
        columns: [
            { data: 'SubCategory', name:'SubCategory'},
            { data: 'Open', name:'Open'},
            { data: 'Closed', name:'Closed'},
            { data: 'Total', name:'Total'}
        ]
    });
   
});
$(document).on('click', '#monthlyBtn', function () {
    $('#loading').show();
    window.location.href = 'monthlytickets';
});

$(document).on('click', '#openticketsBtn', function () {
    $('#loading').show();
    window.location.href = 'openticket';
});
$(document).on('click', '#OpenTicketDiv', function () {
    $('#loading').show();
    window.location.href = 'openticketdata';
    $.ajax({
        type: "GET",
        url: "openticketdata",
        success: function(data){
            $('#loading').hide();
        }
    });
});
$(document).on('click', '#CloseTicketDiv', function () {
    window.open('closeticketdata', '_blank');
});
$(document).on('click', '#CancelledTicketDiv', function () {
    window.open('cancelticketdata', '_blank');
});

$(document).on('click', '#closedticketsBtn', function () {
    $('#loading').show();
    window.location.href = 'closedticket';
});

$(document).on('click', '#userBtn', function () {
    $('#loading').show();
    window.location.href = 'users';
});

$(document).on('click', '#userLogsBtn', function () {
    $('table.userlogsTable').dataTable().fnDestroy();
    $('#userlogsTable thead tr:eq(0) th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%" placeholder="Search '+title+'" class="column_search" />' );
    });
    userlogsTable =
    $('table.userlogsTable').DataTable({ 
        "dom": 'itp',
        "language": {
                "emptyTable": " ",
                "loadingRecords": "Please wait - loading..."
            },
        "order": [[ 0, "desc" ]],
        "pageLength": 10,
        processing: false,
        serverSide: false,
        ajax: 'userlogs',
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ],
        columns: [
            { data: 'id', name:'id'},
            { data: 'Date', name:'Date'},
            { data: 'fullname', name:'fullname'},
            { data: 'Access_Level', name:'Access_Level'},
            { data: 'activity', name:'activity'}
        ]
    });
    $('#userlogsModal').modal('show');
});


$(document).on("click", "#TopIssueMore", function () {
    $('#topissueModal').modal('show');
});

$(document).on("click", "#AgingMore", function () {
    $('#AgingModal').modal('show');
});