var gbitable, StoreTopIssueTable;
var BtnSelected = 'Details', TopIssueLocationNameSelected = 'Store';
var serverTime = new Date();


$(document).ready(function()
{
    gbitable =
    $('table.gbiTable').DataTable({ 
        "dom": 'ftip',
        "language": {
                "emptyTable": " ",
                // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                "loadingRecords": "Please wait - loading..."
            },
        "pageLength": 5,
        "order": [[ 1, "desc" ]],
        processing: false,
        serverSide: false,
        ajax: 'getticket',
        columns: [
            { data: 'DateCreated', name:'DateCreated'},
            { data: 'TaskNumber', name:'TaskNumber'},
            { data: 'Issue', name:'Issue'},
            { data: 'gbisbu', name:'gbisbu'},
            // { data: 'Location', name:'Location'},
            { data: 'IncidentStatus', name:'IncidentStatus'}
        ]
    });

    
    //Store Top Issue
    StoreTopIssueTable =
    $('table.StoreTopIssueTable').DataTable({ 
        "dom": 'itp',
        "language": {
                "emptyTable": " ",
                // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                "loadingRecords": "Please wait - loading..."
            },
        "pageLength": 10,
        "order": [[ 1, "desc" ]],
        processing: false,
        serverSide: false,
        ajax: 'storetopissue',
        columns: [
            // { data: 'DateCreated', name:'DateCreated'},
            { data: 'key', name:'key'},
            { data: 'open', name:'open'},
            { data: 'closed', name:'closed'},
            { data: 'total', name:'total'}
            // { data: 'IncidentStatus', name:'IncidentStatus'}
        ]
    });
    $('div.dataTables_filter').appendTo('#search');
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
    }
    
    setInterval(updateTime, 1000); 
    $('#service_report').hide();
    
});
    // $('#ticketdetailsModal').modal('show');
$(document).on("click", '.DetailsBtn', function () {
    var BtnName = $(this).attr('BtnName');
    if (BtnSelected != BtnName) {
        $('.DetailsBtn[BtnName=\''+BtnSelected+'\']').removeClass('btn-secondary');
        $('.DetailsBtn[BtnName=\''+BtnSelected+'\']').toggleClass('bg-blue');
        $(this).removeClass('bg-blue');
        $(this).toggleClass('btn-secondary');
        $('#'+BtnSelected).hide()
        $('#'+BtnName).show();
        BtnSelected = BtnName;
    }
});
$(document).on('click', '#graphBtn', function () {
    $('#loading').show();
    window.location.href = '/dailytickets';
});

$(document).on("click", '.TopIssueLocationBtn', function () {
    var TopIssueLocationName = $(this).attr('TopIssueLocationName');
    if (TopIssueLocationNameSelected != TopIssueLocationName) {
        $('.TopIssueLocationBtn[TopIssueLocationName=\''+TopIssueLocationNameSelected+'\']').removeClass('btn-secondary');
        $('.TopIssueLocationBtn[TopIssueLocationName=\''+TopIssueLocationNameSelected+'\']').toggleClass('bg-blue');
        $(this).removeClass('bg-blue');
        $(this).toggleClass('btn-secondary');
        $('.'+TopIssueLocationNameSelected).hide()
        $('.'+TopIssueLocationName).show();
        TopIssueLocationNameSelected = TopIssueLocationName;
    }
});

$(document).on("click", "#TopIssueMore", function () {
    $('#topissueModal').modal('show');
    
});
$(document).on("click", "#gbiTable tbody tr", function () {
    $('#loading').show();
    var trdata = gbitable.row(this).data();
    var TaskNumber = trdata.TaskNumber;
    $('#TicketNumber').val(trdata.TaskNumber);
    $('#gbisbu').val(trdata.gbisbu);
    $('#Status').val(trdata.IncidentStatus);

    $.ajax({
        type: "GET",
        url: "taskdata",
        data: {
            TaskNumber: TaskNumber
        },
        success: function(data){
            $('#loading').hide();
            $('#ticketdetailsModal').modal('show');
            if (trdata.gbisbu == "Plant") {
                $('.Location').show();
                $('#Location').val(data.Location);
            }else{
                $('.Location').hide();
            }
            $('#StoreCode').val(data.Store_Code);
            $('#StoreName').val(data.Store_Name);
            $('#Address').val(data.Store_Address);
            $('#Ownership').val(data.Ownership);
            $('#ContactPerson').val(data.Contact_Person);
            $('#ContactNumber').val(data.Contact_Number);
            $('#Email_Address').val(data.Email_Address);
            $('#Problem').val(data.Problem_Reported);
            $('#Issue').val(trdata.Issue);
            $('#RootCause').val(data.Root_Cause);
            $('#LatestNotes').val(data.Latest_Notes);
            $('#Response_Time').val('Response Time: '+data.Response_Time);
            $('#Created_By').val('Created By: '+data.Created_By);
            $('#Problem_Category').val('Problem Category: '+data.Problem_Category);
            $('#Sub_Category').text('Sub Category: '+data.Sub_Category);
            $('#Machine_Model').text('Machine Model: '+data.Machine_Model);
            $('#Incident_Status').text('Incident Status: '+data.Incident_Status);
            $('#gbidiv').hide();
        },
        error: function (data) {
            alert(data.responseText);
        }
    });
    // if($('#editBtn').val() == 'Cancel'){
    //     $('#myid').val(id);
    //     $('#subBtn').val('Update');
    //     $('#customer_code').val(trdata.code);
    //     $('#customer_name').val(trdata.customer);
    //     $('#customerModal').modal('show');
    // }else{
    // window.location.href = 'customer/'+id;
    // }
    
});

