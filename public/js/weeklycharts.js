var year = 2020;
var yearstart = 2021;
var dt = new Date();
var curmonth = dt.getMonth()+1;
var curyear = dt.getFullYear();
var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
function getWeek(dt){
    var calc=function(o){
        if(o.dtmin.getDay()!=1){
            if(o.dtmin.getDay()<=4 && o.dtmin.getDay()!=0)o.w+=1;
            o.dtmin.setDate((o.dtmin.getDay()==0)? 2 : 1+(7-o.dtmin.getDay())+1);
        }
        o.w+=Math.ceil((((o.dtmax.getTime()-o.dtmin.getTime())/(24*60*60*1000))+1)/7);
    },getNbDaysInAMonth=function(year,month){
        var nbdays=31;
        for(var i=0;i<=3;i++){
            nbdays=nbdays-i;
            if((dtInst=new Date(year,month-1,nbdays)) && dtInst.getDate()==nbdays && (dtInst.getMonth()+1)==month  && dtInst.getFullYear()==year)
                break;
        }
        return nbdays;
    };
    if(dt.getMonth()+1==1 && dt.getDate()>=1 && dt.getDate()<=3 && (dt.getDay()>=5 || dt.getDay()==0)){
        var pyData={"dtmin":new Date(dt.getFullYear()-1,0,1,0,0,0,0),"dtmax":new Date(dt.getFullYear()-1,11,getNbDaysInAMonth(dt.getFullYear()-1,12),0,0,0,0),"w":0};
        calc(pyData);
        return pyData.w;
    }else{
        var ayData={"dtmin":new Date(dt.getFullYear(),0,1,0,0,0,0),"dtmax":new Date(dt.getFullYear(),dt.getMonth(),dt.getDate(),0,0,0,0),"w":0},
            nd12m=getNbDaysInAMonth(dt.getFullYear(),12);
        if(dt.getMonth()==12 && dt.getDay()!=0 && dt.getDay()<=3 && nd12m-dt.getDate()<=3-dt.getDay())ayData.w=1;else calc(ayData);
        return ayData.w;
    }
}
$(document).ready(function()
{   
    
    //get week number
    
    //

    // console.log(getWeek(new Date(2023,02-1)));
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

    // var weekoption = '<option selected disabled>select week</option>';
    // for (let index = 1; index <= 53; index++) {
    //     // console.log(getWeek
    //     // (
    //     // new Date(
    //     //     year,
    //     //     01-1)
    //     // ));
    //     var years = $(this).val(); 
    //     var week = index;
    //     var nextweek = index+1;
    //     var firstdayofweek = new Date(years, 0, 1);
    //     var lastdayofweek = new Date(years, 0, 1);
    //     var dayNum = firstdayofweek.getDay();
    //     var diff = --week * 7;
    //     // If 1 Jan is Friday to Sunday, go to next week
    //     if (!dayNum || dayNum > 4) {
    //         diff += 7;
    //     }
    //     // Add required number of days
    //     firstdayofweek.setDate(firstdayofweek.getDate() - firstdayofweek.getDay() + ++diff);
    //     var dayNum = lastdayofweek.getDay();
    //     var diff = --nextweek * 7;
    //     if (!dayNum || dayNum > 4) {
    //         diff += 7;
    //     }
    //     lastdayofweek.setDate(lastdayofweek.getDate() - lastdayofweek.getDay() + ++diff);
    //     if ($('#yearselect').val() == 2021) {
    //         if (firstdayofweek.getMonth() > 5) {
    //             if (index > 27 ) {
    //                 if (index < getWeek(new Date())) {
    //                     weekoption += '<option value="'+index+'">Week '+index+' - '+months[firstdayofweek.getMonth()]+' '+firstdayofweek.getDate()+'-'+months[lastdayofweek.getMonth()]+' '+(lastdayofweek.getDate()-1)+'</option>';
    //                 }
    //             }
    //         }
    //     }else{
    //         if (firstdayofweek.getMonth() == 0) {
    //             if (index != 53 ) {
    //                 weekoption += '<option value="'+index+'">Week '+index+' - '+months[firstdayofweek.getMonth()]+' '+firstdayofweek.getDate()+'-'+months[lastdayofweek.getMonth()]+' '+(lastdayofweek.getDate()-1)+'</option>';
    //             }
    //         }else{
    //             weekoption += '<option value="'+index+'">Week '+index+' - '+months[firstdayofweek.getMonth()]+' '+firstdayofweek.getDate()+'-'+months[lastdayofweek.getMonth()]+' '+(lastdayofweek.getDate()-1)+'</option>';
    //         }
    //     }
            
    // }
    // $("#weekselect").find('option').remove().end().append(weekoption);
    // $('#weekselect').prop('disabled', false);

    

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
        data:{
            month: monthselected,
            year: yearselected
        },
        success: function(data){
            $('#dataheadW').empty();
            $('#databodyW').empty();
            $('#datafootW').empty();
            var dataheadW = '<tr><th>Weekly Ticket</th><th>Store</th><th>Plant</th><th>Office</th><th>Grand Total</th>';
            var databodyW = '<tr><td>Week 1</td><td>'+data.strW[0]+'</td><td>'+data.plntW[0]+'</td><td>'+data.ofcW[0]+'</td><td>'+data.grandtotalW[0]+'</td>';
            databodyW += '<tr><td>Week 2</td><td>'+data.strW[1]+'</td><td>'+data.plntW[1]+'</td><td>'+data.ofcW[1]+'</td><td>'+data.grandtotalW[1]+'</td>';
            databodyW += '<tr><td>Week 3</td><td>'+data.strW[2]+'</td><td>'+data.plntW[2]+'</td><td>'+data.ofcW[2]+'</td><td>'+data.grandtotalW[2]+'</td>';
            databodyW += '<tr><td>Week 4</td><td>'+data.strW[3]+'</td><td>'+data.plntW[3]+'</td><td>'+data.ofcW[3]+'</td><td>'+data.grandtotalW[3]+'</td>';
            databodyW += '<tr><td>Grand Total</td><td>'+data.strtotalW+'</td><td>'+data.plnttotalW+'</td><td>'+data.ofctotalW+'</td><td>'+data.grandtotalW[4]+'</td>';
            var datafootW = '<tr><td>Percentage</td><td>'+data.percent[0]+'</td><td>'+data.percent[1]+'</td><td>'+data.percent[2]+'</td><td>100%</td>';
            // data.dates.forEach(element => {
            //     let d = new Date(element);
            //     let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
            //     let mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(d);
            //     let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
            //     datahead+='<th style="font-size:9px">'+`${da}-${mo}`+'</th>';
            // });
            // data.str.forEach(element => {
            //     databodystore +='<td>'+element+'</td>';
            // });
            // data.plnt.forEach(element => {
            //     databodyplant +='<td>'+element+'</td>';
            // });
            // data.ofc.forEach(element => {
            //     databodyoffice +='<td>'+element+'</td>';
            // });
            // data.grandtotal.forEach(element => {
            //     datafoot +='<td>'+element+'</td>';
            // });
            // datahead+='<th>Grand Total</th></tr>';
            // databodystore +='<td>'+data.strtotal+'</td></tr>';
            // databodyplant +='<td>'+data.plnttotal+'</td></tr>';
            // databodyoffice +='<td>'+data.ofctotal+'</td></tr>';
            // datafoot +='</tr>';
            $('#dataheadW').append(dataheadW);
            $('#databodyW').append(databodyW);
            // $('#databody').append(databodyplant);
            // $('#databody').append(databodyoffice);
            $('#datafootW').append(datafootW);
            $('#dailyChartW').width($('#data').width());
            $('#loading').hide();

            // var barChartData = {
            //     labels: data.dates,
            //     datasets: [
            //         {
            //             label: 'Store',
            //             fill: false,
            //             borderColor: '#D4CFCF',
            //             data: data.str,
            //         },
            //         {
            //             label: 'Plant',
            //             fill: false,
            //             borderColor: '#E88406',
            //             data: data.plnt,
            //         },
            //         {
            //             label: 'Office',
            //             fill: false,
            //             borderColor: '#4496F3',
            //             data: data.ofc,
            //             datalabels: {
            //                 align: 'end',
            //                 anchor: 'end'
            //             }
            //         }
            //     ]
            // };
            // var ctx = $('#dailyChart');
            // var myChart = new Chart(ctx, {
            //     type: 'line',
            //     data: barChartData,
            //     options: {
            //         elements: {
            //             rectangle: {
            //                 borderWidth: 2,
            //                 borderColor: '#c1c1c1',
            //                 borderSkipped: 'bottom'
            //             }
            //         },
            //         responsive: false,
            //         title: {
            //             display: true,
            //             text: optionmonthselected+' tickets'
            //         },
            //     }
            // });
        }
    });
});
$(document).on('click', '#dailyBtn', function () {
    $('#loading').show();
    window.location.href = '/dailytickets';
});