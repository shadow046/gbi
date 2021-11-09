@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<br>
    <div class="container-fluid row center">
        <div class="table col-lg-4" >
            <table class="table-hover SupportCountTable display" id="SupportCountTable" style="font-size:70%;width:70%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th>LOCATION</th>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <th>{{$Restick->Month}}</th>
                        @endforeach
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>OFFICE</b></td>
                        <div style="display:none">{{$OfficeTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$OfficeTotal = $Restick->OfficeTotal+$OfficeTotal}}</div>
                            <td><b>{{number_format($Restick->OfficeTotal)}}</b></td>
                        @endforeach
                        <td><b>{{number_format($OfficeTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Onsite</td>
                        <div style="display:none">{{$OnsiteOfficeTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$OnsiteOfficeTotal = $Restick->OnsiteOffice+$OnsiteOfficeTotal}}</div>
                            <td>{{number_format($Restick->OnsiteOffice)}}</td>
                        @endforeach
                        <td><b>{{number_format($OnsiteOfficeTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Phone Assistance</td>
                        <div style="display:none">{{$PhoneAssistanceOfficeTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$PhoneAssistanceOfficeTotal = $Restick->PhoneAssistanceOffice+$PhoneAssistanceOfficeTotal}}</div>
                            <td>{{number_format($Restick->PhoneAssistanceOffice)}}</td>
                        @endforeach
                        <td><b>{{number_format($PhoneAssistanceOfficeTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Remote</td>
                        <div style="display:none">{{$RemoteOfficeTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$RemoteOfficeTotal = $Restick->RemoteOffice+$RemoteOfficeTotal}}</div>
                            <td>{{number_format($Restick->RemoteOffice)}}</td>
                        @endforeach
                        <td><b>{{number_format($RemoteOfficeTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td><b>PLANT</b></td>
                        <div style="display:none">{{$PlantTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$PlantTotal = $Restick->PlantTotal+$PlantTotal}}</div>
                            <td><b>{{number_format($Restick->PlantTotal)}}</b></td>
                        @endforeach
                        <td><b>{{number_format($PlantTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Onsite</td>
                        <div style="display:none">{{$OnsitePlantTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$OnsitePlantTotal = $Restick->OnsitePlant+$OnsitePlantTotal}}</div>
                            <td>{{number_format($Restick->OnsitePlant)}}</td>
                        @endforeach
                        <td><b>{{number_format($OnsitePlantTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Phone Assistance</td>
                        <div style="display:none">{{$PhoneAssistancePlantTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$PhoneAssistancePlantTotal = $Restick->PhoneAssistancePlant+$PhoneAssistancePlantTotal}}</div>
                            <td>{{number_format($Restick->PhoneAssistancePlant)}}</td>
                        @endforeach
                        <td><b>{{number_format($PhoneAssistancePlantTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Remote</td>
                        <div style="display:none">{{$RemotePlantTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$RemotePlantTotal = $Restick->RemotePlant+$RemotePlantTotal}}</div>
                            <td>{{number_format($Restick->RemotePlant)}}</td>
                        @endforeach
                        <td><b>{{number_format($RemotePlantTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td><b>STORE</b></td>
                        <div style="display:none">{{$StoreTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$StoreTotal = $Restick->StoreTotal+$StoreTotal}}</div>
                            <td><b>{{number_format($Restick->StoreTotal)}}</b></td>
                        @endforeach
                        <td><b>{{number_format($StoreTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Onsite</td>
                        <div style="display:none">{{$OnsiteStoreTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$OnsiteStoreTotal = $Restick->OnsiteStore+$OnsiteStoreTotal}}</div>
                            <td>{{number_format($Restick->OnsiteStore)}}</td>
                        @endforeach
                        <td><b>{{number_format($OnsiteStoreTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Phone Assistance</td>
                        <div style="display:none">{{$PhoneAssistanceStoreTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$PhoneAssistanceStoreTotal = $Restick->PhoneAssistanceStore+$PhoneAssistanceStoreTotal}}</div>
                            <td>{{number_format($Restick->PhoneAssistanceStore)}}</td>
                        @endforeach
                        <td><b>{{number_format($PhoneAssistanceStoreTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>Remote</td>
                        <div style="display:none">{{$RemoteStoreTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$RemoteStoreTotal = $Restick->RemoteStore+$RemoteStoreTotal}}</div>
                            <td>{{number_format($Restick->RemoteStore)}}</td>
                        @endforeach
                        <td><b>{{number_format($RemoteStoreTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td><b>GRAND TOTAL</b></td>
                        <div style="display:none">{{$ResolutionTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$ResolutionTotal = $Restick->ResolutionTotal+$ResolutionTotal}}</div>
                            <td><b>{{number_format($Restick->ResolutionTotal)}}</b></td>
                        @endforeach
                        <td><b>{{number_format($ResolutionTotal)}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
@endsection