@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<br>
<input type="hidden" id='dfrom' value='{{strtok($from, ' ')}}'>
<input type="hidden" id='dto' value='{{strtok($to, ' ')}}'>
    <div class="container-fluid row center">
        <div class="table col-lg-4" >
            <table class="table-hover CountTable display" id="CountTable" style="font-size:70%;width:100%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th>LOCATION</th>
                        <th class="range" id="range"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Office</b></td>
                        <td><b>{{number_format($OfficeP1+$OfficeP2+$OfficeP3)}}</b></td>
                    </tr>
                    <tr>
                        <td>P1</td>
                        <td>{{number_format($OfficeP1)}}</td>
                    </tr>
                    <tr>
                        <td>P2</td>
                        <td>{{number_format($OfficeP2)}}</td>
                    </tr>
                    <tr>
                        <td>P3</td>
                        <td>{{number_format($OfficeP3)}}</td>
                    </tr>
                    <tr>
                        <td><b>Plant</b></td>
                        <td><b>{{number_format($PlantP1+$PlantP2+$PlantP3)}}</b></td>
                    </tr>
                    <tr>
                        <td>P1</td>
                        <td>{{number_format($PlantP1)}}</td>
                    </tr>
                    <tr>
                        <td>P2</td>
                        <td>{{number_format($PlantP2)}}</td>
                    </tr>
                    <tr>
                        <td>P3</td>
                        <td>{{number_format($PlantP3)}}</td>
                    </tr>
                    <tr>
                        <td><b>Store</b></td>
                        <td><b>{{number_format($StoreP1+$StoreP2+$StoreP3)}}</b></td>
                    </tr>
                    <tr>
                        <td>P1</td>
                        <td>{{number_format($StoreP1)}}</td>
                    </tr>
                    <tr>
                        <td>P2</td>
                        <td>{{number_format($StoreP2)}}</td>
                    </tr>
                    <tr>
                        <td>P3</td>
                        <td>{{number_format($StoreP3)}}</td>
                    </tr>
                    <tr>
                        <td class="grand"><b>Grand Total</b></td>
                        <td><b>{{number_format($GrandTotal)}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
@endsection