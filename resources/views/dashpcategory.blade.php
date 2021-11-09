@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<br>
    <div class="container-fluid row">
        <div class="table col" >
            <table class="table-hover CategoryTable display nowrap" id="CategoryTable" style="font-size:70%;width:100%;">
                <thead style="background-color:#00127f;color:white;height:14px;overflow:hidden;">
                    <tr >
                        <th>CATEGORY</th>
                        <th>TOTAL</th>
                        <th>PERCENTAGE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SOFTWARE/APPLICATION</td>
                        <td>{{number_format($TopSoft['Total'])}}</td>
                        <td>{{round(($TopSoft['Total']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}%</td>
                    </tr>
                    <tr>
                        <td>HARDWARE</td>
                        <td>{{number_format($TopHard['Total'])}}</td>
                        <td>{{round(($TopHard['Total']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}%</td>
                    </tr>
                    <tr>
                        <td>INFRASTRUCTURE</td>
                        <td>{{number_format($TopInfra['Total'])}}</td>
                        <td>{{round(($TopInfra['Total']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}%</td>
                    </tr>
                    <tr>
                        <td>OTHERS</td>
                        <td>{{number_format($TopOthers['Others'])}}</td>
                        <td>{{round(($TopOthers['Others']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}%</td>
                    </tr>
                    <tr>
                        <td><b>GRAND TOTAL</b></td>
                        <td><b>{{number_format($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others'])}}</b></td>
                        <td><b>{{round((($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others'])/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}%</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col">
            <input type="hidden" id="software" value='{{round(($TopSoft['Total']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}'>
            <input type="hidden" id="hardware" value='{{round(($TopHard['Total']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}'>
            <input type="hidden" id="infra" value='{{round(($TopInfra['Total']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}'>
            <input type="hidden" id="others" value='{{round(($TopOthers['Others']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}'>
            <canvas id="pie1Chart" height="120" width='300' style="margin:0 auto"></canvas>
        </div>
        <div class="table col" >
            <table class="table-hover ProblemCategoryTable display nowrap" id="ProblemCategoryTable" style="font-size:70%;width:100%;">
                <thead style="background-color:#00127f;color:white;height:14px;overflow:hidden;">
                    <tr>
                        <th valign="top" colspan="9"><center>TOP ISSUE – PER PROBLEM CATEGORY</center></th>
                    </tr>
                    <tr style="line-height: 2px;min-height: 2px;height: 2px;">
                        <th>SOFTWARE</th>
                        <th>COUNT</th>
                        <th>PERCENTAGE</th>
                        <th>HARDWARE</th>
                        <th>COUNT</th>
                        <th>PERCENTAGE</th>
                        <th>INFRASTRUCTURE</th>
                        <th>COUNT</th>
                        <th>PERCENTAGE</th>
                    </tr>
                </thead>
                <tbody>
                    @for ( $i = 0;  $i < 3;  $i++)
                        <tr>
                            <td>{{$softwarekey[$i]}}</td>
                            <td>{{number_format($softwareval[$i])}}</td>
                            <td>{{round(($softwareval[$i]/$TopSoft['Total'])*100,2)}}%</td>
                            <td>{{$Hardwarekey[$i]}}</td>
                            <td>{{number_format($Hardwareval[$i])}}</td>
                            <td>{{round(($Hardwareval[$i]/$TopHard['Total'])*100,2)}}%</td>
                            <td>{{$Infrawarekey[$i]}}</td>
                            <td>{{number_format($Infrawareval[$i])}}</td>
                            <td>{{round(($Infrawareval[$i]/$TopInfra['Total'])*100,2)}}%</td>
                        </tr>
                    @endfor
                    <tr>
                        <td>{{$softwarekey[3]}}</td>
                        <td>{{number_format($softwareval[3])}}</td>
                        <td>{{round(($softwareval[3]/$TopSoft['Total'])*100,2)}}%</td>
                        <td>{{$Hardwarekey[3]}}</td>
                        <td>{{number_format($Hardwareval[3])}}</td>
                        <td>{{round(($Hardwareval[3]/$TopHard['Total'])*100,2)}}%</td>
                        <td>{{$Infrawarekey[3]}}</td>
                        <td>{{number_format($Infrawareval[3])}}</td>
                        <td>{{round(($Infrawareval[3]/$TopInfra['Total'])*100,2)}}%</td>
                    </tr>
                    <tr>
                        <td><b>{{$softwarekey[4]}}</b></td>
                        <td><b>{{number_format($softwareval[4])}}</b></td>
                        <td><b>{{round(($softwareval[4]/$TopSoft['Total'])*100,2)}}%</b></td>
                        <td><b>{{$Hardwarekey[4]}}</b></td>
                        <td><b>{{number_format($Hardwareval[4])}}</b></td>
                        <td><b>{{round(($Hardwareval[4]/$TopHard['Total'])*100,2)}}%</b></td>
                        <td><b>{{$Infrawarekey[4]}}</b></td>
                        <td><b>{{number_format($Infrawareval[4])}}</b></td>
                        <td><b>{{round(($Infrawareval[4]/$TopInfra['Total'])*100,2)}}%</b></td>
                    </tr>
                    {{-- <tr><td>Total</td><td>{{$TopSoft['Total']}}</td><td>100%</td></tr> --}}
                </tbody>
            </table>
        </div>
    </div>
    
    <hr>
@endsection