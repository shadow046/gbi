<div id="ticketdetailsModal" class="modal fade responsive">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content" style="width:100%;height:100%">
            {{-- <div class="modal-header">
                <h6 class="modal-title w-100 text-center">STOCK REQUEST FORM</h6>
            </div> --}}
            <div class="modal-header">
                <div class="modal-title w-100 text-center">
                    <input type="button" class="btn btn-secondary DetailsBtn" BtnName="Details" value="Ticket Details">
                    <input type="button" class="btn bg-blue DetailsBtn" BtnName="Remarks" value="Remarks">
                    <input type="button" class="btn bg-blue DetailsBtn" BtnName="History" value="History">
                    <a class="close cancel" aria-label="Close" data-dismiss="modal" style="cursor: pointer">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
            </div>
            <div class="modal-body" id="Details" style="max-height:400px;overflow-y:auto;">
                <div class="container row">
                    <div class="col text-left">
                        <label class="label" for="TicketNumber">Ticket No.</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="TicketNumber" type="text"name="TicketNumber" disabled>
                    </div>
                    <div class="col text-left">
                        <label class="label" for="StoreCode">Store Code</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="StoreCode" type="text" name="StoreCode" disabled>
                    </div>
                    <div class="col text-left">
                        <label class="label" for="gbisbu">SBU</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="gbisbu" type="text" name="gbisbu" disabled>
                    </div>
                    <div class="col text-left">
                        <label class="label" for="StoreType">Store Type</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="StoreType" type="text" name="StoreType" disabled>
                    </div>
                </div>
                <div class="container row">
                    <div class="col-3 text-left">
                        <label class="label" for="StoreName">Store Name</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="StoreName" type="text"name="StoreName" disabled>
                    </div>
                    <div class="col-3 text-left Location">
                        <label class="label" for="Location">Location</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="Location" type="text"name="Location" disabled>
                    </div>
                    <div class="col text-left">
                        <label class="label" for="Address">Address</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="Address" type="text"name="Address" disabled>
                    </div>
                </div>
                <div class="container row">
                    <div class="col text-left">
                        <label class="label" for="ContactPerson">Contact Person</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="ContactPerson" type="text"name="ContactPerson" disabled>
                    </div>
                    <div class="col text-left">
                        <label class="label" for="ContactNumber">Contact Number</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="ContactNumber" type="text" name="ContactNumber" disabled>
                    </div>
                    <div class="col text-left">
                        <label class="label" for="EmailAddress">Email Address</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="EmailAddress" type="text" name="EmailAddress" disabled>
                    </div>
                    <div class="col text-left">
                        <label class="label" for="Ownership">Ownership</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="Ownership" type="text" name="Ownership" disabled>
                    </div>
                </div>
                <div class="container row">
                    <div class="col-9 text-left">
                        <label class="label" for="Problem">Problem Reported</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="Problem" type="text" name="Problem" disabled>
                    </div>
                    <div class="col text-left">
                        <label class="label" for="Issue">Issue</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="Issue" type="text" name="Issue" disabled>
                    </div>
                </div>
                <div class="container row">
                    <div class="col text-left">
                        <label class="label" for="RootCause">Root Cause</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="RootCause" type="text" name="RootCause" disabled>
                    </div>
                    <div class="col text-left">
                        <label class="label" for="IncidentStatus">Incident Status</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="IncidentStatus" type="text" name="IncidentStatus" disabled>
                    </div>
                </div>
                <div class="container row">
                    <div class="col text-left">
                        <label class="label" for="LatestNotes">Latest Notes</label>
                        <input class="input col" style="font-size:11px;font-family:Arial;width:100%;" id="LatestNotes" type="text" name="LatestNotes" disabled>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="Remarks" style="max-height:400px;overflow-y:auto;display:none;">
                <div id="remarks-details">
                </div>
            </div>
            <div class="modal-body" id="History" style="max-height:400px;overflow-y:auto;display:none;">
                <table id="tablehistory" style="width:100%">
                    <thead>
                        <tr>
                            <th>Source</th>
                            <th>Action/Field</th>
                            <th>Original</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyhistory">
                    </tbody>
                </table>    
            </div>
            
            <hr>
            <div class="modal-footer">
                @if (auth()->user()->hasanyrole('Agent', 'Manager') || (auth()->user()->id == 19 || (auth()->user()->id == 8)
                    <input type="button" class="btn btn-secondary createBtn mr-auto" value="Create New Ticket">
                    <input type="button" class="btn btn-secondary EditBtn" id="EditBtn" value="Edit">
                @endif
            </div>
        </div>
    </div>
</div>