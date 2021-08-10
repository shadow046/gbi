<div id="topissueModal" class="modal fade">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h6 class="modal-title w-100 text-center">TOP ISSUES</h6>
                <a class="close cancel" aria-label="Close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            {{-- <div class="modal-header">
                <div class="modal-title w-100 text-center">
                    <input type="button" class="btn btn-secondary TopIssueLocationBtn" TopIssueLocationName="Store" value="STORE">
                    <input type="button" class="btn bg-blue TopIssueLocationBtn" TopIssueLocationName="Plant" value="PLANT">
                    <input type="button" class="btn bg-blue TopIssueLocationBtn" TopIssueLocationName="Office" value="OFFICE">
                </div>
            </div> --}}
            <div class="modal-body" style="max-height:400px;overflow-y:auto;">
                <div class="container Store">
                    <table class="table-hover table StoreTopIssueTable" id="StoreTopIssueTable" style="font-size:70%;width:100%">
                        <thead style="background-color:#00127f;color:white">
                            <tr>
                                {{-- <th>RANKING</th> --}}
                                <th>ISSUE</th>
                                <th>OPEN</th>
                                <th>CLOSED</th> 
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="container Plant">
                    <table class="table-hover table PlantTopIssueTable" id="PlantTopIssueTable" style="font-size:70%;width:100%;display:none">
                        <thead style="background-color:#00127f;color:white">
                            <tr>
                                {{-- <th>RANKING</th> --}}
                                <th>ISSUE</th>
                                <th>GBI SBU</th>
                                <th>OPEN</th>
                                <th>CLOSED</th> 
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="container Office">
                    <table class="table-hover table OfficeTopIssueTable" id="OfficeTopIssueTable" style="font-size:70%;width:100%;display:none">
                        <thead style="background-color:#00127f;color:white">
                            <tr>
                                {{-- <th>RANKING</th> --}}
                                <th>ISSUE</th>
                                <th>GBI SBU</th>
                                <th>OPEN</th>
                                <th>CLOSED</th> 
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-body" id="Remarks" style="max-height:400px;overflow-y:auto;display:none;">GRAPH</div>
            <div class="modal-body" id="History" style="max-height:400px;overflow-y:auto;display:none;">EXPORT</div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>