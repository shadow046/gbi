var gbitable;
$(document).ready(function()
{
    gbitable =
    $('table.gbiTable').DataTable({ 
        "dom": 'flrtp',
        "language": {
                "emptyTable": " ",
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Searching...</span> '
            },
        processing: true,
        serverSide: true,
        ajax: 'getticket',
        columns: [
            { data: 'DateCreated', name:'DateCreated'},
            { data: 'TaskNumber', name:'TaskNumber'},
            { data: 'CreatedBy', name:'CreatedBy'},
            { data: 'TaskStatus', name:'TaskStatus'}
        ]
    });
    $('#service_report').hide();
});

$(document).on("click", "#gbiTable tr", function () {
    var trdata = gbitable.row(this).data();
    var TaskNumber = trdata.TaskNumber;

    $.ajax({
        type: "GET",
        url: "taskdata",
        data: {
            TaskNumber: TaskNumber
        },
        success: function(data){
            console.log(data);
            $('#service_report').show();
            $('#Store_Code').text('Store Code: '+data.Store_Code);
            $('#Store_Name').text('Store Name: '+data.Store_Name);
            $('#Store_Address').text('Store Address: '+data.Store_Address);
            $('#Contact_Person').text('Contact Person: '+data.Contact_Person);
            $('#Contact_Number').text('Contact Number: '+data.Contact_Number);
            $('#Email_Address').text('Email Address: '+data.Email_Address);
            $('#Response_Time').text('Response Time: '+data.Response_Time);
            $('#Created_By').text('Created By: '+data.Created_By);
            $('#Problem_Category').text('Problem Category: '+data.Problem_Category);
            $('#Sub_Category').text('Sub Category: '+data.Sub_Category);
            $('#Problem_Reported').text('Problem Reported: '+data.Problem_Reported);
            $('#Machine_Model').text('Machine Model: '+data.Machine_Model);
            $('#Root_Cause').text('Root Cause: '+data.Root_Cause);
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

