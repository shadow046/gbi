@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<br>
    <div class="container-fluid row center">
        <div class="table col-lg-6" >
            <table class="table-hover ResTickCountTable display" id="ResTickCountTable" style="font-size:60%;width:70%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th>MONTH</th>
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
                    <div style="display:none">
                        {{$grandone = 0}}
                        {{$grandtwo = 0}}
                        {{$grandthree = 0}}
                        {{$grandfour = 0}}
                        {{$grandfive = 0}}
                        {{$grandmorethanfive = 0}}
                        {{$grandtot=0}}
                    </div>
                    @foreach ($ResTickCount as $TickCount)
                        <div style="display:none">
                            {{$grandone = $TickCount->onedt+$TickCount->onet+$grandone}}
                            {{$grandtwo = $TickCount->twodt+$TickCount->twot+$grandtwo}}
                            {{$grandthree = $TickCount->threedt+$TickCount->threet+$grandthree}}
                            {{$grandfour = $TickCount->fourdt+$TickCount->fourt+$grandfour}}
                            {{$grandfive = $TickCount->fivedt+$TickCount->fivet+$grandfive}}
                            {{$grandmorethanfive = $TickCount->morethanfivedt+$TickCount->morethanfivet+$grandmorethanfive}}
                            {{$grandtot= $TickCount->TotalTicket+$grandtot}}
                        </div>
                        <tr>
                            <td class="month">{{$TickCount->Month}}</td>
                            <td class="grand">{{number_format($TickCount->TotalTicket)}}</td>
                            <td>{{number_format($TickCount->onedt+$TickCount->onet)}}</td>
                            <td>{{number_format($TickCount->twodt+$TickCount->twot)}}</td>
                            <td>{{number_format($TickCount->threedt+$TickCount->threet)}}</td>
                            <td>{{number_format($TickCount->fourdt+$TickCount->fourt)}}</td>
                            <td>{{number_format($TickCount->fivedt+$TickCount->fivet)}}</td>
                            <td>{{number_format($TickCount->morethanfivedt+$TickCount->morethanfivet)}}</td>
                            <td class="closedt">{{number_format($TickCount->onedt+$TickCount->onet+$TickCount->twodt+$TickCount->twot+$TickCount->threedt+$TickCount->threet+$TickCount->fourdt+$TickCount->fourt+$TickCount->fivedt+$TickCount->fivet+$TickCount->morethanfivedt+$TickCount->morethanfivet)}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{round((($TickCount->onedt+$TickCount->onet)/$TickCount->TotalTicket)*100,2)}}%</td>
                            <td>{{round((($TickCount->twodt+$TickCount->twot)/$TickCount->TotalTicket)*100,2)}}%</td>
                            <td>{{round((($TickCount->threedt+$TickCount->threet)/$TickCount->TotalTicket)*100,2)}}%</td>
                            <td>{{round((($TickCount->fourdt+$TickCount->fourt)/$TickCount->TotalTicket)*100,2)}}%</td>
                            <td>{{round((($TickCount->fivedt+$TickCount->fivet)/$TickCount->TotalTicket)*100,2)}}%</td>
                            <td>{{round((($TickCount->morethanfivedt+$TickCount->morethanfivet)/$TickCount->TotalTicket)*100,2)}}%</td>
                            <td>{{round((($TickCount->onedt+$TickCount->onet+$TickCount->twodt+$TickCount->twot+$TickCount->threedt+$TickCount->threet+$TickCount->fourdt+$TickCount->fourt+$TickCount->fivedt+$TickCount->fivet+$TickCount->morethanfivedt+$TickCount->morethanfivet)/$TickCount->TotalTicket)*100,2)}}%</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>Grand Total</td>
                        <td>{{number_format($grandtot)}}</td>
                        <td>{{number_format($grandone)}}</td>
                        <td>{{number_format($grandtwo)}}</td>
                        <td>{{number_format($grandthree)}}</td>
                        <td>{{number_format($grandfour)}}</td>
                        <td>{{number_format($grandfive)}}</td>
                        <td>{{number_format($grandmorethanfive)}}</td>
                        <td>{{number_format($grandone+$grandtwo+$grandthree+$grandfour+$grandfive+$grandmorethanfive)}}</td>
                    </tr>
                    <tr>
                        <td>Percentage</td>
                        <td></td>
                        <td>{{round(($grandone/$grandtot)*100,2)}}%</td>
                        <td>{{round(($grandtwo/$grandtot)*100,2)}}%</td>
                        <td>{{round(($grandthree/$grandtot)*100,2)}}%</td>
                        <td>{{round(($grandfour/$grandtot)*100,2)}}%</td>
                        <td>{{round(($grandfive/$grandtot)*100,2)}}%</td>
                        <td>{{round(($grandmorethanfive/$grandtot)*100,2)}}%</td>
                        <td>{{round((($grandone+$grandtwo+$grandthree+$grandfour+$grandfive+$grandmorethanfive)/$grandtot)*100,2)}}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
@endsection