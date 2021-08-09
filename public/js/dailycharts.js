var dates;
var Store;
var Plant;
var Office;
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
    }
    
    function updateGraph() {
        $.ajax({
            type: "GET",
            url: "dailyticketsdata",
            success: function(data){
                console.log(data)
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
                    datahead+='<th style="font-size:9px">'+`${da}-${mo}`+'</th>';
                });
                data.str.forEach(element => {
                    databodystore +='<td>'+element+'</td>';
                });
                data.plnt.forEach(element => {
                    databodyplant +='<td>'+element+'</td>';
                });
                data.ofc.forEach(element => {
                    databodyoffice +='<td>'+element+'</td>';
                });
                data.grandtotal.forEach(element => {
                    datafoot +='<td>'+element+'</td>';
                });
                datahead+='<th style="font-size:12px">Grand Total</th></tr>';
                databodystore +='<td>'+data.strtotal+'</td></tr>';
                databodyplant +='<td>'+data.plnttotal+'</td></tr>';
                databodyoffice +='<td>'+data.ofctotal+'</td></tr>';
                datafoot +='</tr>';
                $('#datahead').append(datahead);
                $('#databody').append(databodystore);
                $('#databody').append(databodyplant);
                $('#databody').append(databodyoffice);
                $('#datafoot').append(datafoot);
                var barChartData = {
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
                        },
                    }
                });
            }
        });
        $('#dailyChart').width($('#data').width());
        $('#dailyChartW').width($('#dataW').width());

    }
    updateGraph();
    setInterval(updateTime, 1000);
});

$(document).on('click', '#dashboardBtn', function () {
    $('#loading').show();
    window.location.href = '/';
});

$(document).on('click', '#monthlyBtn', function () {
    $('#loading').show();
    window.location.href = '/monthlytickets';
});