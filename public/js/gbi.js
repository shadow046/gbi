var gbitable, StoreTopIssueTable;
var BtnSelected = 'Details', TopIssueLocationNameSelected = 'Store';
var serverTime = new Date();
var gbistatus;
var userlogsTable;
jQuery(document).ready(function()
{
    jQuery('#loading').show();
    jQuery('#gbiTable thead tr:eq(0) th').each( function () {
        var title = jQuery(this).text();
        if (title == "TICKET NUMBER") {
            jQuery(this).html( '<input type="text" style="width:100%" placeholder="Search by date [YYYY][MM][DD]" class="column_search" />' );
        }else{
            jQuery(this).html( '<input type="text" style="width:100%" placeholder="Search '+title+'" class="column_search" />' );
        }
    });
    
    gbitable =
    jQuery('table.gbiTable').DataTable({ 
        "dom": 'tip',
        "language": {
                "emptyTable": " ",
                // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                "loadingRecords": "Please wait - loading..."
            },
        "pageLength": 10,
        "order": [[ 0, "desc" ]],
        processing: false,
        serverSide: false,
        ajax: {
            url: 'getticket'
        },
        columns: [
            // { data: 'DateCreated',
            //     "render": function ( data, type, row, meta) 
            //     {
            //         var dates = new Date(data);
            //         return moment(dates).format('LLL');
            //     }
            // },
            { data: 'TaskNumber', name:'TaskNumber'},
            { data: 'ProblemCategory', name:'ProblemCategory'},
            { data: 'Issue', name:'Issue'},
            { data: 'StoreCode', name:'StoreCode'},
            { data: 'StoreName', name:'StoreName', "width": "17%"},
            // { data: 'Location', name:'Location'},
            { data: 'IncidentStatus', name:'IncidentStatus', "width": "17%"},
            { data: 'LatestNotes', name:'LatestNotes', "width": "17%"}
        ]
    });

    jQuery('#gbiTable thead').on( 'keyup', ".column_search",function () {
        gbitable
            .column( jQuery(this).parent().index() )
            .search( this.value )
            .draw();
    });
    //Store Top Issue
    jQuery("#StoreTopIssueTable").append(
       jQuery('<tfoot/>').append( jQuery("#StoreTopIssueTable thead tr").clone())
   );
    StoreTopIssueTable =
    jQuery('table.StoreTopIssueTable').DataTable({ 
        "dom": 'itp',
        "language": {
                "emptyTable": " ",
                // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                "loadingRecords": "Please wait - loading..."
            },
        "pageLength": 10,
        "order": [[ 3, "desc" ]],
        processing: false,
        serverSide: false,
        ajax: 'storetopissue',
        
        columns: [
            // { data: 'DateCreated', name:'DateCreated'},
            { data: 'SubCategory', name:'SubCategory'},
            { data: 'Open', name:'Open'},
            { data: 'Closed', name:'Closed'},
            { data: 'Total', name:'Total'}
            // { data: 'IncidentStatus', name:'IncidentStatus'}
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
        jQuery('#navtime').html(mytime + ":"+ mintime + ":" + minsecs + " " + am);
        jQuery('#loading').hide();
    }
    
    setInterval(updateTime, 1000); 
    jQuery('#service_report').hide();
    
});
    // jQuery('#ticketdetailsModal').modal('show');
jQuery(document).on("click", '.DetailsBtn', function () {
    var BtnName = jQuery(this).attr('BtnName');
    if (BtnSelected != BtnName) {
        jQuery('.DetailsBtn[BtnName=\''+BtnSelected+'\']').removeClass('btn-secondary');
        jQuery('.DetailsBtn[BtnName=\''+BtnSelected+'\']').toggleClass('bg-blue');
        jQuery(this).removeClass('bg-blue');
        jQuery(this).toggleClass('btn-secondary');
        jQuery('#'+BtnSelected).hide()
        jQuery('#'+BtnName).show();
        BtnSelected = BtnName;
    }
});
jQuery('#userlogsTable thead').on( 'keyup', ".column_search",function () {
        userlogsTable
            .column( jQuery(this).parent().index() )
            .search( this.value )
            .draw();
});
jQuery(document).on('click', '#graphBtn', function () {
    jQuery('#loading').show();
    window.location.href = '/dailytickets';
});
jQuery(document).on('click', '#userBtn', function () {
    jQuery('#loading').show();
    window.location.href = '/users';
});

jQuery(document).on('click', '#logsBtn', function () {
    jQuery('#userlogsTable thead tr:eq(0) th').each( function () {
        var title = jQuery(this).text();
        jQuery(this).html( '<input type="text" style="width:100%" placeholder="Search '+title+'" class="column_search" />' );
    });
    userlogsTable =
    jQuery('table.userlogsTable').DataTable({ 
        "dom": 'itp',
        "language": {
                "emptyTable": " ",
                "loadingRecords": "Please wait - loading..."
            },
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
    jQuery('#userlogsModal').modal('show');
});

jQuery(document).on('click', '#closeTicketBtn', function () {
    jQuery('#loading').show();
    // window.location.href = '/closed';
});
jQuery(document).on('click', '.createBtn', function () {
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/new?tab=Service%20Report%20-%20GBI&status=For%20Verification', '_blank');
});
jQuery(document).on('click', '#EditBtn', function () {
    var ticket = jQuery('#TicketNumber').val();
    var status = gbistatus;
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/'+ticket+'?tab=Service%20Report%20-%20GBI&status='+gbistatus, '_blank');
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/new?tab=Service%20Report%20-%20GBI&status=For%20Verification', '_blank');
});
jQuery(document).on("click", '.TopIssueLocationBtn', function () {
    var TopIssueLocationName = jQuery(this).attr('TopIssueLocationName');
    if (TopIssueLocationNameSelected != TopIssueLocationName) {
        jQuery('.TopIssueLocationBtn[TopIssueLocationName=\''+TopIssueLocationNameSelected+'\']').removeClass('btn-secondary');
        jQuery('.TopIssueLocationBtn[TopIssueLocationName=\''+TopIssueLocationNameSelected+'\']').toggleClass('bg-blue');
        jQuery(this).removeClass('bg-blue');
        jQuery(this).toggleClass('btn-secondary');
        jQuery('.'+TopIssueLocationNameSelected).hide()
        jQuery('.'+TopIssueLocationName).show();
        TopIssueLocationNameSelected = TopIssueLocationName;
    }
});

jQuery(document).on("click", "#TopIssueMore", function () {
    jQuery('#topissueModal').modal('show');
    
});
jQuery(document).on("click", ".close", function () {
    // location.reload()    
});

jQuery(document).on("click", "#StoreTopIssueTable tbody tr", function () {
    var trdata = StoreTopIssueTable.row(this).data();
    console.log(trdata);
});
jQuery(document).on("click", "#gbiTable tbody tr", function () {
    jQuery('#loading').show();
    var trdata = gbitable.row(this).data();
    var TaskNumber = trdata.TaskNumber;
    jQuery('#TicketNumber').val(trdata.TaskNumber);
    jQuery('#gbisbu').val(trdata.gbisbu);
    jQuery('#Status').val(trdata.IncidentStatus);
    gbistatus = trdata.TaskStatus;
    jQuery.ajax({
        type: "GET",
        url: "taskdata",
        data: {
            TaskNumber: TaskNumber
        },
        success: function(data){
             jQuery('#loading').hide();
            jQuery('#ticketdetailsModal').modal('show'); 
            if (trdata.gbisbu == "Plant") {
                jQuery('.Location').show();
                jQuery('#Location').val(data.Location);
            }else{
                jQuery('.Location').hide();
            }
            jQuery('#StoreCode').val(data.Store_Code);
            jQuery('#StoreName').val(data.Store_Name);
            jQuery('#Address').val(data.Store_Address);
            jQuery('#Ownership').val(data.Ownership);
            jQuery('#gbisbu').val(data.Sbu);
            jQuery('#ContactPerson').val(data.Contact_Person);
            jQuery('#ContactNumber').val(data.Contact_Number);
            jQuery('#Email_Address').val(data.Email_Address);
            jQuery('#Problem').val(data.Problem_Reported);
            jQuery('#Issue').val(trdata.Issue);
            jQuery('#RootCause').val(data.Root_Cause);
            jQuery('#LatestNotes').val(data.Latest_Notes);
            jQuery('#IncidentStatus').val(data.IncidentStatus);
            jQuery('#StoreType').val(data.GBIStoreType);
            jQuery('#ActionTaken').val(data.GBIActionTaken);
            var remarks = ' ';
            if (data.Remarks.length > 0) {
                for (let index = 0; index < data.Remarks.length; index++) {
                    var remarksdate = new Date(data.Remarks[index].Timestamp);
                    remarks +='<div class="container row"><label class="col-sm-3 control-label">'+data.Remarks[index].Author+'<br><small>'+moment(remarksdate).format('lll')+'</small></label><div class="col-sm-9"><div class="text-break">'+data.Remarks[index].Message+'</div></div><hr></div>';
                }
                jQuery('#remarks-details').empty().append(remarks);
                jQuery('.DetailsBtn[BtnName=\'Remarks\']').show();
            }else{
                jQuery('.DetailsBtn[BtnName=\'Remarks\']').hide();
            }
            var history = '';
            console.log(data.History);

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
            jQuery('#tbodyhistory').empty().append(history);
            console.log(history);
            jQuery('#gbidiv').hide();
        },
        error: function (data) {
            alert(data.responseText);
        }
    });

});

