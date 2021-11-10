var datefrom = 'from', dateto = 'to';

$(function() {
    $('#datefrom').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm',
        minDate: new Date('2021', '5', '1'),
        maxDate: new Date(new Date().getFullYear(), new Date().getMonth()-1, 1),
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            $("#dateto").datepicker("option", "minDate", new Date(inst.selectedYear, inst.selectedMonth, 1));
            $("#dateto").prop('disabled', false);
            $("#goBtn").prop('disabled', true);
            setTimeout(function(){
                $("#dateto").datepicker('show');
            }, 16); 
        }
    });
    $('#dateto').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm',
        maxDate: new Date(new Date().getFullYear(), new Date().getMonth()-1, 1),
        onClose: function(dateTexts, insts) { 
            $(this).datepicker('setDate', new Date(insts.selectedYear, insts.selectedMonth, 1));
            $("#goBtn").prop('disabled', false);
        }
    });
});
$(document).ready(function()
{  
    var pathfrom = window.location.pathname.split("/")[3];
    var pathto = window.location.pathname.split("/")[4];
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
});

$(document).on('click', '#dashboardBtn', function () {
    window.location.href = '/dash/totalticket/default/1';
});

$(document).on('click', '#TotalTicket', function () 
{
    $('#loading').show();
    window.location.href = '/dash/totalticket/'+datefrom+'/'+dateto;
});

$(document).on('click', '#ProblemCategory', function () 
{
    $('#loading').show();
    window.location.href = '/dash/pcategory/'+datefrom+'/'+dateto;
});

$(document).on('click', '#ResolveTick', function () 
{
    $('#loading').show();
    window.location.href = '/dash/resolvetick/'+datefrom+'/'+dateto;
});
$(document).on('click', '#PriorStatus', function () 
{
    $('#loading').show();
    window.location.href = '/dash/priorstatus/'+datefrom+'/'+dateto;
});
$(document).on('click', '#ResolverStatus', function () 
{
    $('#loading').show();
    window.location.href = '/dash/resolverstatus/'+datefrom+'/'+dateto;
});
$(document).on('click', '#Dependencies', function () 
{
    $('#loading').show();
    window.location.href = '/dash/dependencies/'+datefrom+'/'+dateto;
});