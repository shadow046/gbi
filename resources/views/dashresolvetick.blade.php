@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<input type="hidden" id='dfrom' value='{{strtok($from, ' ')}}'>
<input type="hidden" id='dto' value='{{strtok($to, ' ')}}'>
<br>
    <div class="container-fluid row center">
        <div class="table col-lg-6" >
            <table class="table-hover ResTickCountTable display" id="ResTickCountTable" style="font-size:60%;width:70%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th>DATE RANGE</th>
                        <th>TOTAL TICKETS</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>>5</th>
                        <th>CLOSED TICKETS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="range" id='range'></td>
                        <td class="grand">{{number_format($totalticket)}}</td>
                        <td>{{number_format($oneday)}}</td>
                        <td>{{number_format($twoday)}}</td>
                        <td>{{number_format($threeday)}}</td>
                        <td>{{number_format($fourday)}}</td>
                        <td>{{number_format($fiveday)}}</td>
                        <td>{{number_format($morethanfive)}}</td>
                        <td class="closedt">{{number_format($closedticket)}}</td>
                    </tr>
                    <tr>
                        <td><b>Percentage</b></td>
                        <td></td>
                        <td>{{round(($oneday/$totalticket)*100,2)}}%</td>
                        <td>{{round(($twoday/$totalticket)*100,2)}}%</td>
                        <td>{{round(($threeday/$totalticket)*100,2)}}%</td>
                        <td>{{round(($fourday/$totalticket)*100,2)}}%</td>
                        <td>{{round(($fiveday/$totalticket)*100,2)}}%</td>
                        <td>{{round(($morethanfive/$totalticket)*100,2)}}%</td>
                        <td>{{round(($closedticket/$totalticket)*100,2)}}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
@endsection