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
        for (let index = 7; index <= curmonth ; index++) {
            monthoption += '<option value="'+index+'">'+months[index-1]+'</option>';
        }
    }else if ($(this).val() == curyear) {
        for (let index = 1; index <= curmonth ; index++) {
            monthoption += '<option value="'+index+'">'+months[index-1]+'</option>';
        }
    }else{
        for (let index = 0; index < 12 ; index++) {
            monthoption += '<option value="'+index+'">'+months[index]+'</option>';
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
                var dataheadW = '<tr><th>&nbsp;&nbsp;&nbsp;Weekly Ticket&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Store&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Plant&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Office&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;GRAND TOTAL&nbsp;&nbsp;&nbsp;</th>';
                var yr = yearselected;
                var mon = monthselected;
                var dy = new Date(yr + "-" + mon + "-01").getDay();
                const d = new Date(yearselected, monthselected, 0);
                const adjustedDate = d.getDate() + d.getDay();
                var weekcount = Math.ceil(adjustedDate / 7);
                var firstweekend= Math.ceil(7-dy);
                var secondweekstart = firstweekend+1;
                var secondweekend = secondweekstart+6;
                var thirdweekstart = secondweekend+1;
                var thirdweekend = thirdweekstart+6;
                var newd = new Date();
                if (data.strW.length == 1) {
                    var firstweek = optionmonthselected.substring(0, 3)+' 1 - '+optionmonthselected.substring(0, 3)+' '+newd.getDate();
                }else if (data.strW.length == 2) {
                    var firstweek = optionmonthselected.substring(0, 3)+' 1 - '+optionmonthselected.substring(0, 3)+' '+firstweekend;
                    var secondweek = optionmonthselected.substring(0, 3)+' '+secondweekstart+' - '+optionmonthselected.substring(0, 3)+' '+newd.getDate();
                }else if (data.strW.length == 3) {
                    var firstweek = optionmonthselected.substring(0, 3)+' 1 - '+optionmonthselected.substring(0, 3)+' '+firstweekend;
                    var secondweek = optionmonthselected.substring(0, 3)+' '+secondweekstart+' - '+optionmonthselected.substring(0, 3)+' '+secondweekend;
                    var thirdweek = optionmonthselected.substring(0, 3)+' '+thirdweekstart+' - '+optionmonthselected.substring(0, 3)+' '+newd.getDate();
                }else if (data.strW.length == 4) {
                    var fourthweekstart = thirdweekend+1;
                    var fourthweekend = d.getDate();
                    var firstweek = optionmonthselected.substring(0, 3)+' 1 - '+optionmonthselected.substring(0, 3)+' '+firstweekend;
                    var secondweek = optionmonthselected.substring(0, 3)+' '+secondweekstart+' - '+optionmonthselected.substring(0, 3)+' '+secondweekend;
                    var thirdweek = optionmonthselected.substring(0, 3)+' '+thirdweekstart+' - '+optionmonthselected.substring(0, 3)+' '+thirdweekend;
                    var fourthweek = optionmonthselected.substring(0, 3)+' '+fourthweekstart+' - '+optionmonthselected.substring(0, 3)+' '+newd.getDate();
                }else if (data.strW.length == 5) {
                    var fourthweekstart = thirdweekend+1;
                    var fourthweekend = fourthweekstart+6;
                    var fifthweekstart = fourthweekend+1;
                    var fifthweekend = d.getDate();
                    var firstweek = optionmonthselected.substring(0, 3)+' 1 - '+optionmonthselected.substring(0, 3)+' '+firstweekend;
                    var secondweek = optionmonthselected.substring(0, 3)+' '+secondweekstart+' - '+optionmonthselected.substring(0, 3)+' '+secondweekend;
                    var thirdweek = optionmonthselected.substring(0, 3)+' '+thirdweekstart+' - '+optionmonthselected.substring(0, 3)+' '+thirdweekend;
                    var fourthweek = optionmonthselected.substring(0, 3)+' '+fourthweekstart+' - '+optionmonthselected.substring(0, 3)+' '+fourthweekend;
                    if ((newd.getMonth()+1) == monthselected && newd.getFullYear() == yearselected) {
                        if (newd.getDate() == d.getDate()) {
                            var fifthweek = optionmonthselected.substring(0, 3)+' '+fifthweekstart+' - '+optionmonthselected.substring(0, 3)+' '+fifthweekend;
                        }else{
                            var fifthweek = optionmonthselected.substring(0, 3)+' '+fifthweekstart+' - '+optionmonthselected.substring(0, 3)+' '+newd.getDate();
                        }
                    }else{
                        var fifthweek = optionmonthselected.substring(0, 3)+' '+fifthweekstart+' - '+optionmonthselected.substring(0, 3)+' '+fifthweekend;
                    }
                }

                for (let index = 0; index < data.strW.length; index++) {
                    if (index == 0) {
                        databodyW += '<tr><td>'+firstweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
                    }else if (index == 1) {
                        databodyW += '<tr><td>'+secondweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
                    }else if (index == 2) {
                        databodyW += '<tr><td>'+thirdweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
                    }else if (index == 3) {
                        databodyW += '<tr><td>'+fourthweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
                    }else if (index == 4) {
                        databodyW += '<tr><td>'+fifthweek+'</td><td>'+data.strW[index].toLocaleString()+'</td><td>'+data.plntW[index].toLocaleString()+'</td><td>'+data.ofcW[index].toLocaleString()+'</td><td>'+data.grandtotalW[index].toLocaleString()+'</td>';
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
        $('#ptext').text('MONTHLY VIEW '+optionmonthselected.toUpperCase()+' '+$('#yearselect').val()).css({'font-weight':'bold'});;
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
$(document).on('click', '#graphBtn', function () {
    $('#loading').show();
    window.location.href = '/';
});
$(document).on('click', '#exportBtn', function () {
    $('#loading').show();
    window.location.href = '/ExportData/'+optionyearselected+'/'+monthselected+'/'+optionmonthselected;
    setTimeout(function() {
        $('#loading').hide();
    },3000);
});