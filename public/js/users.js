var table;
var userid;
$(document).ready(function()
{
    $('#loading').show();
    $('#userTable thead tr:eq(0) th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%" placeholder="Search '+title+'" class="column_search" />' );
    });
    table = 
    $('#userTable').DataTable({ 
        "dom": 'lrtip',
        processing: true,
        serverSide: false,
        "language": {
            "emptyTable": "No registered user to this branch"
        },
        "pageLength": 10,
        ajax: 'getusers',
        columns: [
            { data: 'name', name:'name' },
            { data: 'email', name:'email' },
            { data: 'Access_Level', name:'Access_Level' },
            { data: 'status', name:'status' }
        ]
    });
    function updateTime() {
        var currtime = new Date();
        var currtime1 = new Date(currtime.getTime());
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
});
$(document).on('click', '#addBtn', function () {
    $('#subBtn').val('Save');
    $('#first_name').val('');
    $('#email').val('');
    $('#role').val('');
    $('#status').val('Active');
    $('#statusrow').hide();
    $('#userModal').modal('show');
});

$(document).on('click', '#subBtn', function (e) {
    e.preventDefault();
    if ($('#subBtn').val() == 'Save') {
        $('#userModal').toggle();
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: "adduser",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="ctok"]').attr('content')
            },
            data: $('#userForm').serialize(),
            success: function(data){
                if($.isEmptyObject(data.error)){
                    alert("User data saved");
                    window.location.reload();
                }else{
                    $('#loading').hide();
                    alert(data.error);
                    $('#userModal').toggle();
                }
            },
            error: function (data) {
                $('#loading').hide();
                alert(data.responseText);
                $('#userModal').toggle();
            }
        });
    }else{
        $('#userModal').toggle();
        $('#loading').show();
        $.ajax({
            type: "PUT",
            url: "updateuser/"+userid,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="ctok"]').attr('content')
            },
            data: $('#userForm').serialize(),
            success: function(data){
                if($.isEmptyObject(data.error)){
                    alert("User data saved");
                    window.location.reload();
                }else{
                    $('#loading').hide();
                    alert(data.error);
                    $('#userModal').toggle();
                }
            },
            error: function (data) {
                $('#loading').hide();
                alert(data.responseText);
                $('#userModal').toggle();
            }
        });
    }
    
});
$(document).on('click', '#userTable tbody tr', function () {
    var trdata = table.row(this).data();
    userid = trdata.id;
    $('#first_name').val(trdata.name);
    $('#email').val(trdata.email);
    $('#role').val(trdata.Access_Level);
    if (trdata.status == 'Active') {
        $('#status').val(1);
    }else{
        $('#status').val(0);
    }
    $('#subBtn').val('Update');
    $('#userModal').modal('show');
});
$(document).on('click', '#openticketsBtn', function () {
    $('#loading').show();
    window.location.href = 'openticket';
});
$(document).on('click', '#dashboardBtn', function () {
    $('#loading').show();
    window.location.href = '/';
});
$(document).on('click', '#closedticketsBtn', function () {
    $('#loading').show();
    window.location.href = 'closedticket';
});