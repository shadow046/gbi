@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<br>
<input type="hidden" id='dfrom' value='{{strtok($from, ' ')}}'>
<input type="hidden" id='dto' value='{{strtok($to, ' ')}}'>
    <div class="container-fluid row center">
        <div class="table col-lg-4" >
            <table class="table-hover SupportCountTable display" id="SupportCountTable" style="font-size:70%;width:70%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th>LOCATION</th>
                        <th class="range" id="range"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>OFFICE</b></td>
                        <td><b>{{number_format($OfficeTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Onsite</td>
                        <td>{{number_format($OnsiteOffice)}}</td>
                    </tr>
                    <tr>
                        <td>Phone Assistance</td>
                        <td>{{number_format($PhoneAssistanceOffice)}}</td>
                    </tr>
                    <tr>
                        <td>Remote</td>
                        <td>{{number_format($RemoteOffice)}}</td>
                    </tr>
                    <tr>
                        <td><b>PLANT</b></td>
                        <td><b>{{number_format($PlantTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Onsite</td>
                        <td>{{number_format($OnsitePlant)}}</td>
                    </tr>
                    <tr>
                        <td>Phone Assistance</td>
                        <td>{{number_format($PhoneAssistancePlant)}}</td>
                    </tr>
                    <tr>
                        <td>Remote</td>
                        <td>{{number_format($RemotePlant)}}</td>
                    </tr>
                    <tr>
                        <td><b>STORE</b></td>
                        <td><b>{{number_format($StoreTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Onsite</td>
                        <td>{{number_format($OnsiteStore)}}</td>
                    </tr>
                    <tr>
                        <td>Phone Assistance</td>
                        <td>{{number_format($PhoneAssistanceStore)}}</td>
                    </tr>
                    <tr>
                        <td>Remote</td>
                        <td>{{number_format($RemoteStore)}}</td>
                    </tr>
                    <tr>
                        <td><b>GRAND TOTAL</b></td>
                        <td><b>{{number_format($ResolutionTotal)}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
@endsection