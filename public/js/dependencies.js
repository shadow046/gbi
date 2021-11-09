$(document).on('click', '#goBtn', function () {
    $('#loading').show();
    window.location.href = '/dash/dependencies/'+$('#datefrom').val()+'/'+$('#dateto').val();
});