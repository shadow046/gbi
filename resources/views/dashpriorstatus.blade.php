@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<br>
    <div class="container-fluid row center">
        <div class="table col-lg-4" >
            <table class="table-hover CountTable display" id="CountTable" style="font-size:70%;width:100%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th>LOCATION</th>
                        @foreach ( $PriorTickCount as $Prior)
                            <th>{{$Prior->Month}}</th>
                        @endforeach
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Office</b></td>
                        <div style="display:none">{{$OfficeTotal = 0}}</div>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$OfficeTotal = $Prior->OfficeP1+$Prior->OfficeP2+$Prior->OfficeP3+$OfficeTotal}}</div>
                            <td><b>{{number_format($Prior->OfficeP1+$Prior->OfficeP2+$Prior->OfficeP3)}}</b></td>
                        @endforeach
                        <td><b>{{number_format($OfficeTotal)}}</b></td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$OfficeP1Total = 0}}</div>
                        <td>P1</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$OfficeP1Total = $Prior->OfficeP1+$OfficeP1Total}}</div>
                            <td>{{number_format($Prior->OfficeP1)}}</td>
                        @endforeach
                        <td>{{number_format($OfficeP1Total)}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$OfficeP2Total = 0}}</div>
                        <td>P2</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$OfficeP2Total = $Prior->OfficeP2+$OfficeP2Total}}</div>
                            <td>{{number_format($Prior->OfficeP2)}}</td>
                        @endforeach
                        <td>{{number_format($OfficeP2Total)}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$OfficeP3Total = 0}}</div>
                        <td>P3</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$OfficeP3Total = $Prior->OfficeP3+$OfficeP3Total}}</div>
                            <td>{{number_format($Prior->OfficeP3)}}</td>
                        @endforeach
                        <td>{{number_format($OfficeP3Total)}}</td>
                    </tr>
                    <tr>
                        <td><b>Plant</b></td>
                        <div style="display:none">{{$PlantTotal = 0}}</div>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$PlantTotal = $Prior->PlantP1+$Prior->PlantP2+$Prior->PlantP3+$PlantTotal}}</div>
                            <td><b>{{number_format($Prior->PlantP1+$Prior->PlantP2+$Prior->PlantP3)}}</b></td>
                        @endforeach
                        <td><b>{{number_format($PlantTotal)}}</b></td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$PlantP1Total = 0}}</div>
                        <td>P1</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$PlantP1Total = $Prior->PlantP1+$PlantP1Total}}</div>
                            <td>{{number_format($Prior->PlantP1)}}</td>
                        @endforeach
                        <td>{{number_format($PlantP1Total)}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$PlantP2Total = 0}}</div>
                        <td>P2</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$PlantP2Total = $Prior->PlantP2+$PlantP2Total}}</div>
                            <td>{{number_format($Prior->PlantP2)}}</td>
                        @endforeach
                        <td>{{number_format($PlantP2Total)}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$PlantP3Total = 0}}</div>
                        <td>P3</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$PlantP3Total = $Prior->PlantP3+$PlantP3Total}}</div>
                            <td>{{number_format($Prior->PlantP3)}}</td>
                        @endforeach
                        <td>{{number_format($PlantP3Total)}}</td>
                    </tr>
                    <tr>
                        <td><b>Store</b></td>
                        <div style="display:none">{{$StoreTotal = 0}}</div>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$StoreTotal = $Prior->StoreP1+$Prior->StoreP2+$Prior->StoreP3+$StoreTotal}}</div>
                            <td><b>{{number_format($Prior->StoreP1+$Prior->StoreP2+$Prior->StoreP3)}}</b></td>
                        @endforeach
                        <td><b>{{number_format($StoreTotal)}}</b></td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$StoreP1Total = 0}}</div>
                        <td>P1</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$StoreP1Total = $Prior->StoreP1+$StoreP1Total}}</div>
                            <td>{{number_format($Prior->StoreP1)}}</td>
                        @endforeach
                        <td>{{number_format($StoreP1Total)}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$StoreP2Total = 0}}</div>
                        <td>P2</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$StoreP2Total = $Prior->StoreP2+$StoreP2Total}}</div>
                            <td>{{number_format($Prior->StoreP2)}}</td>
                        @endforeach
                        <td>{{number_format($StoreP2Total)}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$StoreP3Total = 0}}</div>
                        <td>P3</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$StoreP3Total = $Prior->StoreP3+$StoreP3Total}}</div>
                            <td>{{number_format($Prior->StoreP3)}}</td>
                        @endforeach
                        <td>{{number_format($StoreP3Total)}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$PriorGrandTotal = 0}}</div>
                        <td class="grand"><b>Grand Total</b></td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$PriorGrandTotal = $Prior->GrandTotal+$PriorGrandTotal}}</div>
                            <td><b>{{number_format($Prior->GrandTotal)}}</b></td>
                        @endforeach
                        <td><b>{{number_format($PriorGrandTotal)}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
@endsection