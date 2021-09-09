<div id="topissueModal" class="modal fade">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title w-100 text-center">TOP ISSUES</h6>
                <a class="close cancel" aria-label="Close" data-dismiss="modal" style="cursor: pointer">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body" style="max-height:400px;overflow-y:auto;">
                <div class="container Store" id="toptable">
                    <table class="table-hover table StoreTopIssueTable" id="StoreTopIssueTable" style="font-size:70%;width:100%">
                        <thead style="background-color:#00127f;color:white">
                            <tr>
                                <th>ISSUE</th>
                                <th>OPEN</th>
                                @if(Request::is('/'))
                                    <th>CLOSED</th>
                                    <th>TOTAL</th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>