$(document).on('click', '#goBtn', function () {
    $('#loading').show();
    window.location.href = '/dash/resolverstatus/'+$('#datefrom').val()+'/'+$('#dateto').val();
});