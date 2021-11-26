$(document).on('click', '#goBtn', function () {
    $('#loading').show();
    window.location.href = '/dash/dependencies/'+$('#datefrom').val()+'/'+$('#dateto').val();
});
$(document).ready(function()
{
    if (pathfrom == 'default') {
        var range = monthshort[parseFloat($('#dfrom').val().split("-")[1])]+'. '+parseFloat($('#dfrom').val().split("-")[2])+' - '+monthshort[parseFloat($('#dto').val().split("-")[1])]+'. '+parseFloat($('#dto').val().split("-")[2]);
        $('#range').text(range);
    }else{
        var range = monthshort[parseFloat($('#datefrom').val().split("-")[1])]+'. '+parseFloat($('#datefrom').val().split("-")[2])+' - '+monthshort[parseFloat($('#dateto').val().split("-")[1])]+'. '+parseFloat($('#dateto').val().split("-")[2]);
        $('#range').text(range);
    }
});