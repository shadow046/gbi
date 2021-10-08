var year = 2021;
var yearstart = 2021;
var dt = new Date();
var curmonth = dt.getMonth()+1;
var curyear = dt.getFullYear();
var ctx;
let mychart;
var getdata;
var optionyearselected;
var monthselected;
var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var monthshort = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "August", "September", "October", "November", "December"];
$(document).ready(function()
{   
    $('#loading').show();
    function updateTime() {
        var currtime = new Date();
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
    var yearoption = '<option selected disabled>select year</option>';
    for (let index = year; index <= curyear; index++) {
        yearoption += '<option value="'+index+'">'+index+'</option>';
    }
    $("#yearselect").find('option').remove().end().append(yearoption);
    
});
$(document).on('change', '#yearselect', function(){
    var monthoption = '<option selected disabled>select month</option>';
    if ($(this).val() == yearstart) {
        for (let index = 10; index <= curmonth ; index++) {
            monthoption += '<option value="'+index+'">'+months[index-1]+'</option>';
        }
    }else if ($(this).val() == curyear) {
        for (let index = 1; index <= curmonth ; index++) {
            monthoption += '<option value="'+index+'">'+months[index-1]+'</option>';
        }
    }else{
        for (let index = 1; index <= 12 ; index++) {
            monthoption += '<option value="'+index+'">'+months[index-1]+'</option>';
        }
    }
    $("#monthselect").find('option').remove().end().append(monthoption);
    $('#monthselect').prop('disabled', false);
    $('#exportBtn').hide();
});
$(document).on('change', '#monthselect', function(){
    $('#groupselect').val('day');
    $('#bydays').show();
    $('#loading').show();
    $('.ptext').hide();
    var barChartData;
    var barChartDataW;

    monthselected = $(this).val();
    optionmonthselected = $('#monthselect option:selected').text();
    optionyearselected = $('#yearselect option:selected').text();
    var yearselected = $('#yearselect').val();
    // setTimeout(function() {
        $.ajax({
            type:'get',
            url:'monthlyticketsdata',
            async: false,
            data:{
                month: monthselected,
                year: yearselected
            },
            success: function(data){
                getdata = data;
                $('#datahead').empty();
                $('#databody').empty();
                $('#datafoot').empty();
                $('#dataheadW').empty();
                $('#databodyW').empty();
                $('#datafootW').empty();
                $('#chart1').empty();
                $('#chart2').empty();
                $('#chart1').append('<canvas id="dailyChart" height="250" width="900" style="margin:0 auto"></canvas>');
                $('#chart2').append('<canvas id="dailyChartW" height="250" width="900" style="margin:0 auto"></canvas>');
                var datahead = '<tr><th style="font-size:12px">GBI SBU</th>';
                var databodystore = '<tr><td>Store</td>';
                var databodyplant = '<tr><td>Plant</td>';
                var databodyoffice = '<tr><td>Office</td>';
                var datafoot = '<tr><td>Grand Total</td>';
                var databodyW = ' ';
                var dataheadW = '<tr><th>&nbsp;&nbsp;&nbsp;WEEK&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Store&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Plant&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Office&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;GRAND TOTAL&nbsp;&nbsp;&nbsp;</th>';

                for (let index = 0; index < data.strW.length; index++) {
                    if (index == 0) {
                        databodyW += '<tr><td>'+data.firstweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
                    }else if (index == 1) {
                        databodyW += '<tr><td>'+data.secondweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
                    }else if (index == 2) {
                        databodyW += '<tr><td>'+data.thirdweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
                    }else if (index == 3) {
                        databodyW += '<tr><td>'+data.fourthweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
                    }else if (index == 4) {
                        databodyW += '<tr><td>'+data.fifthweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
                    }
                }
                databodyW += '<tr><td>Grand Total</td><td>'+data.strtotalW.toLocaleString()+'</td><td>'+data.plnttotalW.toLocaleString()+'</td><td>'+data.ofctotalW.toLocaleString()+'</td><td>'+data.grandtotalW[data.weekcount].toLocaleString()+'</td>';
                var datafootW = '<tr><td>Percentage</td><td>'+data.percent[0]+'</td><td>'+data.percent[1]+'</td><td>'+data.percent[2]+'</td><td>100%</td>';
                data.dates.forEach(element => {
                    datahead+='<th style="font-size:12px;">&nbsp;&nbsp;'+element+'&nbsp;&nbsp;</th>';
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
                datahead+='<th>Grand Total</th></tr>';
                databodystore +='<td>'+data.strtotal.toLocaleString()+'</td></tr>';
                databodyplant +='<td>'+data.plnttotal.toLocaleString()+'</td></tr>';
                databodyoffice +='<td>'+data.ofctotal.toLocaleString()+'</td></tr>';
                datafoot +='</tr>';
                $('#datahead').append(datahead);
                $('#databody').append(databodystore);
                $('#databody').append(databodyplant);
                $('#databody').append(databodyoffice);
                $('#datafoot').append(datafoot);
                $('#dataheadW').append(dataheadW);
                $('#databodyW').append(databodyW);
                $('#datafootW').append(datafootW);
                $('#loading').hide();
                
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
                        }
                    ]
                };
                barChartDataW = {
                    labels: data.weekslabel,
                    datasets: [
                        {
                            label: 'Store',
                            fill: true,
                            borderColor: '#867979',
                            data: data.strW,
                            backgroundColor: [
                                'rgba(212, 207, 207, 1)',
                                'rgba(212, 207, 207, 1)',
                                'rgba(212, 207, 207, 1)',
                                'rgba(212, 207, 207, 1)',
                                'rgba(212, 207, 207, 1)'
                            ]
                        },
                        {
                            label: 'Plant',
                            fill: true,
                            borderColor: '#E88406',
                            data: data.plntW,
                            backgroundColor: [
                                'rgba(232, 132, 6, 1)',
                                'rgba(232, 132, 6, 1)',
                                'rgba(232, 132, 6, 1)',
                                'rgba(232, 132, 6, 1)',
                                'rgba(232, 132, 6, 1)'
                            ]
                        },
                        {
                            label: 'Office',
                            fill: true,
                            borderColor: '#4496F3',
                            data: data.ofcW,
                            backgroundColor: [
                                'rgba(68, 150, 243, 1)',
                                'rgba(68, 150, 243, 1)',
                                'rgba(68, 150, 243, 1)',
                                'rgba(68, 150, 243, 1)',
                                'rgba(68, 150, 243, 1)'
                            ]
                        }
                    ]
                };
                $('#groupselect').show();
            }
        });

        ctx = $('#dailyChart');
        ctxW = $('#dailyChartW');
            var mychart = new Chart(ctx, {
                type: 'line',
                data: barChartData,
                options: {
                    maintainAspectRatio: false,
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
                        text: optionmonthselected+' tickets'
                    },
                }
            });
            var mychartW = new Chart(ctxW, 
                {
                    type: 'bar',
                    data: barChartDataW,
                    options: {
                        events: [],
                        responsive: false,
                        maintainAspectRatio: false,
                        legend: {      
                        },
                        title: {
                            display: true,
                            text: optionmonthselected+' tickets'
                        },
                        scales: {
                            yAxes: [{
                                ticks: {          
                                beginAtZero: true,
                                }
                            }]
                        },
                        animation: {
                            onComplete: function() {
                                var chartInstance = this.chart,
                                ctx = chartInstance.ctx;

                                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';
                                
                                this.data.datasets.forEach(function(dataset, i) {
                                    var meta = chartInstance.controller.getDatasetMeta(i);
                                    meta.data.forEach(function(bar, index) {
                                        if (dataset.data[index] >= 0) {
                                            var data = dataset.data[index];
                                            ctx.fillStyle = dataset.borderColor;
                                            ctx.fillText(data, bar._model.x, bar._model.y);
                                        }
                                    });
                                });
                            }
                        },
                    },
                }
            );

        $('#dailyChart').width('900');
        $('#dailyChartW').width('900');
        $('#ptext').text('MONTHLY VIEW '+optionmonthselected.toUpperCase()+' '+$('#yearselect').val()).css({'font-weight':'bold'});
        $('.ptext').show();
        if (curmonth == monthselected) {
            $('#exportBtn').hide();
        }else{
            $('#exportBtn').show();
        }
    // }, 500);
});
$(document).on('change', '#groupselect', function () {
    if ($(this).val() == 'day') {
        $('#bydays').show();
        $('#byweeks').hide();
    }else{
        $('#bydays').hide();
        $('#byweeks').show();
    }
});
$(document).on('click', '#dailyBtn', function () {
    $('#loading').show();
    window.location.href = '/dailytickets';
});
$(document).on('click', '#dashboardBtn', function () {
    $('#loading').show();
    window.location.href = '/';
});
$(document).on('click', '#closedticketsBtn', function () {
    $('#loading').show();
    window.location.href = 'closedticket';
});
$(document).on('click', '#openticketsBtn', function () {
    $('#loading').show();
    window.location.href = 'openticket';
});
$(document).on('click', '#exportBtn', function () {
    $('#loading').show();
   
    window.location.href = '/ExportData/'+optionyearselected+'/'+monthselected+'/'+optionmonthselected;
    setTimeout(function() {
        $('#loading').hide();
    },3000);
});