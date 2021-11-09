@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<br>
    <div class="container-fluid row center">
        <div class="table col-lg-4" >
            <table class="table-hover ResolverTable display" id="ResolverTable" style="font-size:70%;width:100%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th>RESOLVER</th>
                        @foreach ( $ResolverTickCount as $Resolver)
                            <th>{{$Resolver->Month}}</th>
                        @endforeach
                        <th>GRAND TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Escalate to L2</td>
                        <div style="display:none">{{$EscalatedTotal = 0}}</div>
                        @foreach ( $ResolverTickCount as $Resolver)
                            <div style="display:none">{{$EscalatedTotal = $Resolver->Escalated+$EscalatedTotal}}</div>
                            <td>{{number_format($Resolver->Escalated)}}</td>
                        @endforeach
                        <td><b>{{number_format($EscalatedTotal)}}</b></td>
                    </tr>
                    <tr>
                        <td>L1</td>
                        <div style="display:none">{{$L1Total = 0}}</div>
                        @foreach ( $ResolverTickCount as $Resolver)
                            <div style="display:none">{{$L1Total = $Resolver->L1+$L1Total}}</div>
                            <td>{{number_format($Resolver->L1)}}</td>
                        @endforeach
                        <td><b>{{number_format($L1Total)}}</b></td>
                    </tr>
                    <tr>
                        <td>L2</td>
                        <div style="display:none">{{$L2Total = 0}}</div>
                        @foreach ( $ResolverTickCount as $Resolver)
                            <div style="display:none">{{$L2Total = $Resolver->L2+$L2Total}}</div>
                            <td>{{number_format($Resolver->L2)}}</td>
                        @endforeach
                        <td><b>{{number_format($L2Total)}}</b></td>
                    </tr>
                    <tr>
                        <td class="grand"><b>Grand Total</b></td>
                        <div style="display:none">{{$GrandTotal = 0}}</div>
                        @foreach ( $ResolverTickCount as $Resolver)
                            <div style="display:none">{{$GrandTotal = $Resolver->ResolverTotal+$GrandTotal}}</div>
                            <td>{{number_format($Resolver->ResolverTotal)}}</td>
                        @endforeach
                        <td class="grand"><b>{{number_format($GrandTotal)}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
@endsection