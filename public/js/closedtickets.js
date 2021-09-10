var gbitable, StoreTopIssueTable;
var BtnSelected = 'Details', TopIssueLocationNameSelected = 'Store';
var serverTime = new Date();


$(document).ready(function()
{
    $('#loading').show();
    $('#gbiTable thead tr:eq(0) th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%" placeholder="Search '+title+'" class="column_search" />' );
    });
    gbitable =
    $('table.gbiTable').DataTable({ 
        "dom": 'tip',
        "language": {
                "emptyTable": " ",
                // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                "loadingRecords": "Please wait - loading..."
            },
        "pageLength": 10,
        "order": [[ 1, "desc" ]],
        processing: false,
        serverSide: false,
        ajax: 'closedtickets',
        columns: [
            // { data: 'DateCreated',
            //     "render": function ( data, type, row, meta) 
            //     {
            //         var dates = new Date(data);
            //         return moment(dates).format('LLL');
            //     }
            // },
            { data: 'DateCreated', render: function ( data, type, row ) 
                {
                    return moment(data).add(8, 'hours').format('lll');
                }},
            { data: 'TaskNumber', name:'TaskNumber'},
            { data: 'ProblemCategory', name:'ProblemCategory'},
            { data: 'Issue', name:'Issue'},
            { data: 'StoreCode', name:'StoreCode'},
            { data: 'StoreName', name:'StoreName', "width": "17%"}
        ]
    });
    $('#gbiTable thead').on( 'keyup', ".column_search",function () {
        gbitable
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
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
        $('#loading').hide();
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
$(document).on('click', '#openTicketBtn', function () {
    $('#loading').show();
    window.location.href = '/';
});

$(document).on('click', '#logsBtn', function () {
    $('table.userlogsTable').dataTable().fnDestroy();
    $('#userlogsTable thead tr:eq(0) th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%" placeholder="Search '+title+'" class="column_search" />' );
    });
    userlogsTable =
    $('table.userlogsTable').DataTable({ 
        "dom": 'itp',
        "language": {
                "emptyTable": " ",
                "loadingRecords": "Please wait - loading..."
            },
        "order": [[ 0, "desc" ]],
        "pageLength": 10,
        processing: false,
        serverSide: true,
        ajax: 'userlogs',
        columns: [
            { data: 'Date', name:'Date'},
            { data: 'fullname', name:'fullname'},
            { data: 'Access_Level', name:'Access_Level'},
            { data: 'activity', name:'activity'}
        ]
    });
    $('#userlogsModal').modal('show');
});

$(document).on('click', '#userBtn', function () {
    $('#loading').show();
    window.location.href = '/users';
});

$(document).on('click', '.createBtn', function () {
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/new?tab=Service%20Report%20-%20GBI&status=For%20Verification', '_blank');
});

$(document).on("click", '.TopIssueLocationBtn', function () {
    var TopIssueLocationName = $(this).attr('TopIssueLocationName');
    if (TopIssueLocationNameSelected != TopIssueLocationName) {
        $('.TopIssueLocationBtn[TopIssueLocationName=\''+TopIssueLocationNameSelected+'\']').removeClass('btn-secondary');
        $('.TopIssueLocationBtn[TopIssueLocationName=\''+TopIssueLocationNameSelected+'\']').toggleClass('bg-blue');
        $(this).removeClass('bg-blue');
        $(this).toggleClass('btn-secondary');
        $('#'+TopIssueLocationNameSelected).hide()
        $('#'+TopIssueLocationName).show();
        TopIssueLocationNameSelected = TopIssueLocationName;
    }
});

$(document).on("click", "#TopIssueMore", function () {
    $('#topissueModal').modal('show');
    
});
$(document).on("click", ".close", function () {
    // location.reload()    
});

$(document).on("click", "#StoreTopIssueTable tbody tr", function () {
    var trdata = StoreTopIssueTable.row(this).data();
    console.log(trdata);

});
$(document).on("click", "#gbiTable tbody tr", function () {
    $('#loading').show();
    var trdata = gbitable.row(this).data();
    var TaskNumber = trdata.TaskNumber;
    $('#TicketNumber').val(trdata.TaskNumber);
    $('#gbisbu').val(trdata.gbisbu);
    $('#Status').val(trdata.IncidentStatus);
    $('.DetailsBtn[BtnName=\'Details\']').click();
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
            $('#gbisbu').val(data.Sbu);
            $('#ContactPerson').val(data.Contact_Person);
            $('#ContactNumber').val(data.Contact_Number);
            $('#Email_Address').val(data.Email_Address);
            $('#Problem').val(data.Problem_Reported);
            $('#Issue').val(trdata.Issue);
            $('#RootCause').val(data.Root_Cause);
            $('#LatestNotes').val(data.Latest_Notes);
            $('#IncidentStatus').val(data.IncidentStatus);
            $('#StoreType').val(data.GBIStoreType);
            $('#ActionTaken').val(data.GBIActionTaken);
            var remarks = ' ';
            if (data.Remarks.length > 0) {
                for (let index = 0; index < data.Remarks.length; index++) {
                    var remarksdate = new Date(data.Remarks[index].Timestamp);
                    if (data.Remarks[index].Message) {
                        remarks +='<div class="container row"><label class="col-sm-3 control-label">'+data.Remarks[index].Author+'<br><small>'+moment(remarksdate).format('lll')+'</small></label><div class="col-sm-9"><div class="text-break">'+data.Remarks[index].Message+'</div></div><hr></div>';
                    }
                }
                $('#remarks-details').empty().append(remarks);
                $('.DetailsBtn[BtnName=\'Remarks\']').show();
            }else{
                $('.DetailsBtn[BtnName=\'Remarks\']').hide();
            }
            var history = '';
            console.log(data.History);
            if (data.History.length > 0) {
                $('.DetailsBtn[BtnName=\'History\']').show();
            }else{
                $('.DetailsBtn[BtnName=\'History\']').hide();
            }
            for (let index = 0; index < data.History.length; index++) {
                var historydate = new Date(data.History[index].Timestamp);
                if (index != 0) {
                    if (data.History[index].Timestamp != data.History[index-1].Timestamp) {
                        history +='<tr><td>'+data.History[index].Source+'<br><small><i>'+moment(historydate).format('lll')+'</i></small></td>';
                    }else{
                        history +='<tr><td></td>';
                    }
                }else{
                    history +='<tr><td>'+data.History[index].Source+'<br><small><i>'+moment(historydate).format('lll')+'</i></small></td>';
                }
                if (data.History[index].AuditLevel == '1') {
                    history +='<td>'+data.History[index].Message+'</td><td></td><td></td></tr>';
                }else{
                    if (data.History[index].Updated) {
                        history +='<td>'+data.History[index].Action+'</td>';
                        if (data.History[index].Original) {
                            history +='<td>'+data.History[index].Original+'</td>';
                        }else{
                            history +='<td></td>';
                        }
                        if (data.History[index].Updated) {
                            history +='<td>'+data.History[index].Updated+'</td></tr>';
                        }else{
                            history +='<td></td></tr>';
                        }
                    }
                }
            }
            $('#tbodyhistory').empty().append(history);
            $('#gbidiv').hide();
        },
        error: function (data) {
            alert(data.responseText);
        }
    });
});
$(document).on('click', '#openticketsBtn', function () {
    $('#loading').show();
    window.location.href = 'openticket';
});
$(document).on('click', '#monthlyBtn', function () {
    $('#loading').show();
    window.location.href = 'monthlytickets';
});
$(document).on('click', '#dashboardBtn', function () {
    $('#loading').show();
    window.location.href = '/';
});
