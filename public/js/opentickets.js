var gbitable, StoreTopIssueTable;
var BtnSelected = 'Details', TopIssueLocationNameSelected = 'Store';
var serverTime = new Date();
var gbistatus;
var userlogsTable;
$(document).ready(function()
{
    $('#loading').show();
    $('#gbiTable thead tr:eq(0) th').each( function () {
        var title = $(this).text();
        if (title == "DATE") {
            $(this).html('<input type="text" style="width:100px" placeholder="Search '+title+'" class="column_search" />' );
        }else if (title == "TICKET NUMBER") {
                $(this).html('<input type="text" style="width:100px" placeholder="Search TICKET #" class="column_search" />' );
        }else if (title == "AGE") {
                $(this).html('<input type="text" style="width:70px" placeholder="Search '+title+'" class="column_search" />' );
        }else if (title == "STORE CODE") {
                $(this).html('<input type="text" style="width:70px" placeholder="Search Code" class="column_search" />' );
        }else if (title == "GBI-SBU") {
                $(this).html('<input type="text" style="width:50px" placeholder="Search Code" class="column_search" />' );
        }else if (title == "CALL TYPE") {
                $(this).html('<input type="text" style="width:70px" placeholder="Search '+title+'" class="column_search" />' );
        }else if (title == "CATEGORY") {
                $(this).html('<input type="text" style="width:100px" placeholder="Search '+title+'" class="column_search" />' );
        }else if (title == "ISSUE") {
                $(this).html('<input type="text" style="width:100px" placeholder="Search '+title+'" class="column_search" />' );
        }else if (title == "INCIDENT STATUS") {
                $(this).html('<input type="text" style="width:200px" placeholder="Search '+title+'" class="column_search" />' );
        }else if (title == "LATEST NOTES") {
                $(this).html('<input type="text" style="width:300px" placeholder="Search '+title+'" class="column_search" />' );
        }else{
            $(this).html('<input type="text" style="width:150px" placeholder="Search '+title+'" class="column_search" />' );
        }
    });
    if ($('#role').val() == "Client") {
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
            ajax: {
                url: 'getticket',
                statusCode: {
                    401: function() {
                        location.reload();
                    }
                }
            },
            columns: [
                { data: 'DateCreated', render: function ( data, type, row ) 
                    {
                        return moment(data).add(8, 'hours').format('lll');
                    }},
                { data: 'TaskNumber', name:'TaskNumber'},
                { data: 'DateCreated', render: function ( data, type, row ) {
                    var d1 = moment(data).add(8, 'hours');
                    var d2 = moment();
                    if (d2.diff(d1, 'days') <= 5) {
                        return 'Less than 5 days';
                    }else if (d2.diff(d1, 'days') >= 6 && d2.diff(d1, 'days') <= 10) {
                        return '6 to 10 days';
                    }else if (d2.diff(d1, 'days') >= 11 && d2.diff(d1, 'days') <= 15) {
                        return '11 to 15 days';
                    }else if (d2.diff(d1, 'days') >= 16 && d2.diff(d1, 'days') <= 20) {
                        return '16 to 20 days';
                    }else if (d2.diff(d1, 'days') >= 21) {
                        return 'More than 20 days';
                    }
                },"width": "10%"},
                { data: 'CallType', name:'CallType'},
                { data: 'ProblemCategory', name:'ProblemCategory'},
                { data: 'Issue', name:'Issue'},
                { data: 'StoreCode', name:'StoreCode'},
                { data: 'StoreName', name:'StoreName'},
                { data: 'SBU', name:'SBU'},
                { data: 'SystemStatus', name:'SystemStatus'},
                { data: 'IncidentStatus', name:'IncidentStatus'}
            ]
        });
    }else{
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
            ajax: {
                url: 'getticket',
                statusCode: {
                    401: function() {
                        location.reload();
                    }
                }
            },
            // "fnRowCallback": function(nRow, aData) {
            //     if (aData.ResolvedTime != null) {
            //         console.log(aData.ResolvedTime.split('.')[0]);
            //         var d1 = moment(aData.DateCreated).add(8, 'hours');
            //         var d2 = moment(aData.ResolvedTime.split('.')[0]).add(8, 'hours');
            //         if (d2.diff(d1, 'days') <= 5) {
            //             $('td', nRow).eq(2).text('Less than 5 days');
            //         }else if (d2.diff(d1, 'days') >= 6 && d2.diff(d1, 'days') <= 10) {
            //             $('td', nRow).eq(2).text('6 to 10 days1');
            //         }else if (d2.diff(d1, 'days') >= 11 && d2.diff(d1, 'days') <= 15) {
            //             $('td', nRow).eq(2).text('11 to 15 days');
            //             console.log(aData.DateCreated+"--"+aData.ResolvedTime+'++'+d2.diff(d1, 'days'));
            //         }else if (d2.diff(d1, 'days') >= 16 && d2.diff(d1, 'days') <= 20) {
            //             $('td', nRow).eq(2).text('16 to 20 days');
            //         }else if (d2.diff(d1, 'days') >= 21) {
            //             $('td', nRow).eq(2).text('More than 20 days');
            //         }
            //     }else{
            //         var d1 = moment(aData.DateCreated).add(8, 'hours');
            //         var d2 = moment();
            //         if (d2.diff(d1, 'days') <= 5) {
            //             $('td', nRow).eq(2).text('Less than 5 days');
            //         }else if (d2.diff(d1, 'days') >= 6 && d2.diff(d1, 'days') <= 10) {
            //             $('td', nRow).eq(2).text('6 to 10 days');
            //         }else if (d2.diff(d1, 'days') >= 11 && d2.diff(d1, 'days') <= 15) {
            //             $('td', nRow).eq(2).text('11 to 15 days');
            //         }else if (d2.diff(d1, 'days') >= 16 && d2.diff(d1, 'days') <= 20) {
            //             $('td', nRow).eq(2).text('16 to 20 days');
            //         }else if (d2.diff(d1, 'days') >= 21) {
            //             $('td', nRow).eq(2).text('More than 20 days');
            //         }
            //     }
            // },
            columns: [
                { data: 'DateCreated', render: function ( data, type, row ) 
                    {
                        return moment(data).add(8, 'hours').format('lll');
                    }},
                { data: 'TaskNumber', name:'TaskNumber'},
                // { data: null, "width": "10%"},
                { data: 'DateCreated', render: function ( data, type, row ) {
                    if (row.ResolvedTime != null) {
                        var d1 = moment(data).add(8, 'hours');
                        var d2 = moment(row.ResolvedTime.split('.')[0]);
                        if (d2.diff(d1, 'days') <= 5) {
                            return 'Less than 5 days';
                        }else if (d2.diff(d1, 'days') >= 6 && d2.diff(d1, 'days') <= 10) {
                            return '6 to 10 days';
                        }else if (d2.diff(d1, 'days') >= 11 && d2.diff(d1, 'days') <= 15) {
                            console.log(data+"--"+row.ResolvedTime+'++'+d2.diff(d1, 'days'));
                            return '11 to 15 days';
                        }else if (d2.diff(d1, 'days') >= 16 && d2.diff(d1, 'days') <= 20) {
                            return '16 to 20 days';
                        }else if (d2.diff(d1, 'days') >= 21) {
                            return 'More than 20 days';
                        }
                    }else{
                        var d1 = moment(data).add(8, 'hours');
                        var d2 = moment();
                        if (d2.diff(d1, 'days') <= 5) {
                            return 'Less than 5 days';
                        }else if (d2.diff(d1, 'days') >= 6 && d2.diff(d1, 'days') <= 10) {
                            return '6 to 10 days';
                        }else if (d2.diff(d1, 'days') >= 11 && d2.diff(d1, 'days') <= 15) {
                            return '11 to 15 days';
                        }else if (d2.diff(d1, 'days') >= 16 && d2.diff(d1, 'days') <= 20) {
                            return '16 to 20 days';
                        }else if (d2.diff(d1, 'days') >= 21) {
                            return 'More than 20 days';
                        }
                    }
                },"width": "10%"},
                { data: 'CallType', name:'CallType'},
                { data: 'ProblemCategory', name:'ProblemCategory'},
                { data: 'Issue', name:'Issue'},
                { data: 'StoreCode', name:'StoreCode'},
                { data: 'StoreName', name:'StoreName'},
                { data: 'SBU', name:'SBU'},
                { data: 'Status', name:'Status'},
                { data: 'SystemStatus', name:'SystemStatus'},
                { data: 'IncidentStatus', name:'IncidentStatus'}
            ]
        });
    }
    

    $('#gbiTable thead').on( 'keyup', ".column_search",function () {
        gbitable
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
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

$(document).on('click', '#closedticketsBtn', function () {
    $('#loading').show();
    window.location.href = 'closedticket';
});
$(document).on('click', '#monthlyBtn', function () {
    $('#loading').show();
    window.location.href = 'monthlytickets';
});
$(document).on('click', '#dashboardBtn', function () {
    $('#loading').show();
    window.location.href = '/';
});

$(document).on('click', '.createBtn', function () {
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/new?tab=Service%20Report%20-%20GBI&status=For%20Verification', '_blank');
});
$(document).on('click', '#EditBtn', function () {
    var ticket = $('#TicketNumber').val();
    var status = gbistatus;
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/'+ticket+'?tab=Service%20Report%20-%20GBI&status='+gbistatus, '_blank');
    window.open('http://wf.ideaserv.com.ph/#/GBI/task/assignment/new?tab=Service%20Report%20-%20GBI&status=For%20Verification', '_blank');
});

$(document).on("click", ".close", function () {
    // location.reload()    
});

$(document).on("click", "#gbiTable tbody tr", function () {
    $('#loading').show();
    var trdata = gbitable.row(this).data();
    var TaskNumber = trdata.TaskNumber;
    $('#TicketNumber').val(trdata.TaskNumber);
    $('#gbisbu').val(trdata.gbisbu);
    $('#Status').val(trdata.IncidentStatus);
    $('.DetailsBtn[BtnName=\'Details\']').click();
    gbistatus = trdata.TaskStatus;
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
            $('#IncidentStatus').val(data.IncidentStatus);
            $('#StoreType').val(data.GBIStoreType);
            $('#ActionTaken').val(data.GBIActionTaken);
            $('#LatestNotes').val(data.Latest_Notes);
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
            console.log(history);
            $('#gbidiv').hide();
        },
        error: function (data) {
            alert(data.responseText);
        }
    });

});

