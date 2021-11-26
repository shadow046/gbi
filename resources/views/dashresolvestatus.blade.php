@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<br>
<input type="hidden" id='dfrom' value='{{strtok($from, ' ')}}'>
<input type="hidden" id='dto' value='{{strtok($to, ' ')}}'>
    <div class="container-fluid row center">
        <div class="table col-lg-4" >
            <table class="table-hover ResolverTable display" id="ResolverTable" style="font-size:70%;width:100%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th>RESOLVER</th>
                        <th class='range' id="range"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Escalate to L2</td>
                        <td>{{number_format($Escalated)}}</td>
                    </tr>
                    <tr>
                        <td>L1</td>
                        <td>{{number_format($L1)}}</td>
                    </tr>
                    <tr>
                        <td>L2</td>
                        <td>{{number_format($L2)}}</td>
                    </tr>
                    <tr>
                        <td class="grand"><b>Grand Total</b></td>
                        <td>{{number_format($ResolverTotal)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
@endsection