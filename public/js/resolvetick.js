$(document).on('click', '#goBtn', function () {
    $('#loading').show();
    window.location.href = '/dash/resolvetick/'+$('#datefrom').val()+'/'+$('#dateto').val();
});