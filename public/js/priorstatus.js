$(document).on('click', '#goBtn', function () {
    $('#loading').show();
    window.location.href = '/dash/priorstatus/'+$('#datefrom').val()+'/'+$('#dateto').val();
});