<div id="AgingModal" class="modal fade">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title w-100 text-center">AGING TICKET TREND</h6>
                <a class="close cancel" aria-label="Close" data-dismiss="modal" style="cursor: pointer">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body" style="max-height:400px;overflow-y:auto;">
                <div class="container Store">
                    <table class="table-hover table AgingTable" id="AgingTable" style="font-size:70%;width:100%">
                        <thead style="background-color:#00127f;color:white">
                            <tr>
                                <th>SBU</th>
                                <th><=5 </th>
                                <th>6 to 10</th>
                                <th>11 to 15</th>
                                <th>16 to 20</th>
                                <th>>20</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>Store</b></td>
                                <td>{{ $fivedaysStore }}</td>
                                <td>{{ $sixto10Store }}</td>
                                <td>{{ $elevento15Store }}</td>
                                <td>{{ $sixteento20Store }}</td>
                                <td>{{ $greaterthan20Store }}</td>
                                <td><b>{{ number_format(+$fivedaysStore+$sixto10Store+$elevento15Store+$sixteento20Store+$greaterthan20Store) }}</b></td>
                            </tr>
                            <tr>
                                <td><b>Office</b></td>
                                <td>{{ $fivedaysOffice }}</td>
                                <td>{{ $sixto10Office }}</td>
                                <td>{{ $elevento15Office }}</td>
                                <td>{{ $sixteento20Office }}</td>
                                <td>{{ $greaterthan20Office }}</td>
                                <td><b>{{ number_format(+$fivedaysOffice+$sixto10Office+$elevento15Office+$sixteento20Office+$greaterthan20Office) }}</b></td>
                            </tr>
                            <tr>
                                <td><b>Plant</b></td>
                                <td>{{ $fivedaysPlant }}</td>
                                <td>{{ $sixto10Plant }}</td>
                                <td>{{ $elevento15Plant }}</td>
                                <td>{{ $sixteento20Plant }}</td>
                                <td>{{ $greaterthan20Plant }}</td>
                                <td><b>{{ number_format(+$fivedaysPlant+$sixto10Plant+$elevento15Plant+$sixteento20Plant+$greaterthan20Plant) }}</b></td>
                            </tr>
                            <tr>
                                <td><b>GRAND TOTAL</b></td>
                                <td>{{ $fivedays }}</td>
                                <td>{{ $sixto10 }}</td>
                                <td>{{ $elevento15 }}</td>
                                <td>{{ $sixteento20 }}</td>
                                <td>{{ $greaterthan20 }}</td>
                                <td><b>{{ number_format(+$fivedays+$sixto10+$elevento15+$sixteento20+$greaterthan20) }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
</div>