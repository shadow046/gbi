var year = 2021;
var yearstart = 2021;
var dt = new Date();
var curmonth = dt.getMonth()+1;
var curyear = dt.getFullYear();
var barChartDataW;
var barChartData;
var ctx;
let mychart;
var getdata;
var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
$(document).ready(function()
{   
    // var years = '2021'; 
    // var weeks = '29';
    
    //     var d = new Date(years, 6, 1);
    //     console.log(d.getWeek());   

    //         var dayNum = d.getDay();
    //         var diff = --weeks * 7;
    //         console.log(dayNum);
    //         // If 1 Jan is Friday to Sunday, go to next week
    //         if (!dayNum || dayNum > 4) {
    //             diff += 7;
    //         }
    //         console.log(diff);
    //         // Add required number of days
    //         d.setDate(d.getDate() - d.getDay() + ++diff);
    // console.log(d);   
    // console.log(d.getWeek);   
    
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
        for (let index = 7; index < curmonth ; index++) {
            monthoption += '<option value="'+index+'">'+months[index-1]+'</option>';
        }
    }else if ($(this).val() == curyear) {
        for (let index = 1; index < curmonth ; index++) {
            monthoption += '<option value="'+index+'">'+months[index-1]+'</option>';
        }
    }else{
        for (let index = 0; index < 12 ; index++) {
            monthoption += '<option value="'+index+'">'+months[index]+'</option>';
        }
    }
    $("#monthselect").find('option').remove().end().append(monthoption);
    $('#monthselect').prop('disabled', false);
});
$(document).on('change', '#monthselect', function(){
    var monthselected = $(this).val();
    var optionmonthselected = $('#monthselect option:selected').text();
    var yearselected = $('#yearselect').val();
    $('#loading').show();
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
            var datahead = '<tr><th style="font-size:14px">GBI SBU</th>';
            var databodystore = '<tr><td>Store</td>';
            var databodyplant = '<tr><td>Plant</td>';
            var databodyoffice = '<tr><td>Office</td>';
            var datafoot = '<tr><td>Grand Total</td>';
            var dataheadW = '<tr><th>&nbsp;&nbsp;&nbsp;Weekly Ticket&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Store&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Plant&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;Office&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;GRAND TOTAL&nbsp;&nbsp;&nbsp;</th>';
            var databodyW = '<tr><td>Week 1</td><td>'+data.strW[0]+'</td><td>'+data.plntW[0]+'</td><td>'+data.ofcW[0]+'</td><td>'+data.grandtotalW[0].toLocaleString()+'</td>';
            databodyW += '<tr><td>Week 2</td><td>'+data.strW[1]+'</td><td>'+data.plntW[1]+'</td><td>'+data.ofcW[1]+'</td><td>'+data.grandtotalW[1].toLocaleString()+'</td>';
            databodyW += '<tr><td>Week 3</td><td>'+data.strW[2]+'</td><td>'+data.plntW[2]+'</td><td>'+data.ofcW[2]+'</td><td>'+data.grandtotalW[2].toLocaleString()+'</td>';
            databodyW += '<tr><td>Week 4</td><td>'+data.strW[3]+'</td><td>'+data.plntW[3]+'</td><td>'+data.ofcW[3]+'</td><td>'+data.grandtotalW[3].toLocaleString()+'</td>';
            if (data.weekcount == 5) {
                databodyW += '<tr><td>Week 5</td><td>'+data.strW[4]+'</td><td>'+data.plntW[4]+'</td><td>'+data.ofcW[4]+'</td><td>'+data.grandtotalW[4].toLocaleString()+'</td>';
            }
            databodyW += '<tr><td>Grand Total</td><td>'+data.strtotalW.toLocaleString()+'</td><td>'+data.plnttotalW.toLocaleString()+'</td><td>'+data.ofctotalW.toLocaleString()+'</td><td>'+data.grandtotalW[4].toLocaleString()+'</td>';
            var datafootW = '<tr><td>Percentage</td><td>'+data.percent[0]+'</td><td>'+data.percent[1]+'</td><td>'+data.percent[2]+'</td><td>100%</td>';
            data.dates.forEach(element => {
                // let d = new Date(element);
                // let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
                // let mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(d);
                // let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
                datahead+='<th style="font-size:12px;">&nbsp;&nbsp;&nbsp;'+element+'&nbsp;&nbsp;&nbsp;</th>';
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
    let mychart = new Chart(ctx, {
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
    let mychartW = new Chart(ctxW, 
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
                    duration: 1,
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
    $('#dailyChart').width($('#data').width());
    $('#dailyChartW').width($('#data').width()*.80);
    console.log($('#data').width()*.9);
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
$(document).on('click', '#weeklyBtn', function () {
    $('#loading').show();
    window.location.href = '/weeklytickets';
});