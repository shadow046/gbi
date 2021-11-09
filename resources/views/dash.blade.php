@extends('layouts.app')
@section('content')
<div>
    <div>
        <input type="button" class="btn" style="margin-left:0px;margin-right:0px;border-radius: 0px;background-color: gray;color: white;cursor: pointer;" id="dashboardBtn" value="DASHBOARD">&nbsp;&nbsp;
        <input type="button" class="btn" style="margin-left:-5px;margin-right:0px;border-radius: 0px;background-color: #0d1a80;color: white;cursor: pointer;" id="dashboardBtn" value="BUTTON">&nbsp;&nbsp;
    </div>
        <ul class="nav mr-auto list-inline" style="font-size: 14px">
            <li class="nav-item" style="margin-left:50px;margin-right:0px;padding-top: 5px;padding-bottom: 5px;">
                <a class="nav-link {{ Request::is('dash') ? 'active' : '' }}" href="{{ url('/') }}">TOTAL TICKETS</a>
            </li>
            <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">PROBLEM CATEGORY</a>
            </li>
            <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">STATUS SUMMARY</a>
            </li>
            <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">RESOLVED TICKETS</a>
            </li>
            <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">PRIORITY STATUS</a>
            </li>
            <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">RESOLVER STATUS</a>
            </li>
            <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">DEPENDENCIES</a>
            </li>
        </ul>
</div>
<br>
<nav class="navbar navbar-expand-lg navbar-light bg-light" style="display:none">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    @if (!auth()->user()->hasrole("Agent"))
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="dashboardBtn" value="DASHBOARD">&nbsp;&nbsp;
    @endif
    <a href="{{route('logout')}}" class="nav-link ml-auto"><input type="button" class="btn logoutBtn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="logoutBtn" value="LOGOUT"></a>
</nav>

@if (Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{{ Session::get('success') }}</li>
        </ul>
    </div>
@endif
    <div class="container-fluid row">
        <div class="table col-md-5" >
            <table class="table-hover gbiTable display nowrap" id="gbiTable" style="font-size:60%;width:70%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th>MONTH</th>
                        <th>STORE</th>
                        <th>OFFICE</th>
                        <th>PLANT</th>
                        <th>TOTAL</th>
                        <th>DAILY AVERAGE</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-md-3">
            <canvas id="pieChart" height="150" width='200' style="margin:0 auto"></canvas>
        </div>
        <div class="table col-md-4" >
            <table class="table-hover topTable display nowrap" id="topTable" style="font-size:70%;width:100%;">
                <thead style="background-color:#00127f;color:white">
                    <tr>
                        <th valign="top" colspan="3"><center>TOP 3 ISSUE BY CATEGORY</center></th>
                    </tr>
                    <tr>
                        <th>STORE</th>
                        <th>OFFICE</th>
                        <th>PLANT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ( $StoreTop as $Store => $value)
                            @if ($loop->index == 0)
                                <td>{{$Store}}</td>
                            @endif
                        @endforeach
                        @foreach ( $OfficeTop as $Office => $value)
                            @if ($loop->index == 0)
                                <td>{{$Office}}</td>
                            @endif
                        @endforeach
                        @foreach ( $PlantTop as $Plant => $value)
                            @if ($loop->index == 0)
                                <td>{{$Plant}}</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ( $StoreTop as $Store => $value)
                            @if ($loop->index == 1)
                                <td>{{$Store}}</td>
                            @endif
                        @endforeach
                        @foreach ( $OfficeTop as $Office => $value)
                            @if ($loop->index == 1)
                                <td>{{$Office}}</td>
                            @endif
                        @endforeach
                        @foreach ( $PlantTop as $Plant => $value)
                            @if ($loop->index == 1)
                                <td>{{$Plant}}</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ( $StoreTop as $Store => $value)
                            @if ($loop->index == 2)
                                <td>{{$Store}}</td>
                            @endif
                        @endforeach
                        @foreach ( $OfficeTop as $Office => $value)
                            @if ($loop->index == 2)
                                <td>{{$Office}}</td>
                            @endif
                        @endforeach
                        @foreach ( $PlantTop as $Plant => $value)
                            @if ($loop->index == 2)
                                <td>{{$Plant}}</td>
                            @endif
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
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
    <div class="container-fluid row">
        <div class="col-lg-5">
            <input type="hidden" id="software" value='{{round(($TopSoft['Total']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}'>
            <input type="hidden" id="hardware" value='{{round(($TopHard['Total']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}'>
            <input type="hidden" id="infra" value='{{round(($TopInfra['Total']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}'>
            <input type="hidden" id="others" value='{{round(($TopOthers['Others']/($TopSoft['Total']+$TopHard['Total']+$TopInfra['Total']+$TopOthers['Others']))*100,2)}}'>
            <canvas id="pie1Chart" height="150" width='300' style="margin:0 auto"></canvas>
        </div>
        <div class="col-lg-5">
            <canvas id="dailyChart" height="350" width='600' style="margin:0 auto"></canvas>
        </div>
    </div>
    <hr>
    <div class="container-fluid row">
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
                            <td class="grand">{{$TickCount->TotalTicket}}</td>
                            <td>{{$TickCount->onedt+$TickCount->onet}}</td>
                            <td>{{$TickCount->twodt+$TickCount->twot}}</td>
                            <td>{{$TickCount->threedt+$TickCount->threet}}</td>
                            <td>{{$TickCount->fourdt+$TickCount->fourt}}</td>
                            <td>{{$TickCount->fivedt+$TickCount->fivet}}</td>
                            <td>{{$TickCount->morethanfivedt+$TickCount->morethanfivet}}</td>
                            <td class="closedt">{{$TickCount->onedt+$TickCount->onet+$TickCount->twodt+$TickCount->twot+$TickCount->threedt+$TickCount->threet+$TickCount->fourdt+$TickCount->fourt+$TickCount->fivedt+$TickCount->fivet+$TickCount->morethanfivedt+$TickCount->morethanfivet}}</td>
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
                        <td>{{$grandtot}}</td>
                        <td>{{$grandone}}</td>
                        <td>{{$grandtwo}}</td>
                        <td>{{$grandthree}}</td>
                        <td>{{$grandfour}}</td>
                        <td>{{$grandfive}}</td>
                        <td>{{$grandmorethanfive}}</td>
                        <td>{{$grandone+$grandtwo+$grandthree+$grandfour+$grandfive+$grandmorethanfive}}</td>
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
    <div class="container-fluid row">
        <div class="table col-lg-4" >
            <table class="table-hover ResolverTable display" id="ResolverTable" style="font-size:60%;width:70%;">
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
                            <td>{{$Resolver->Escalated}}</td>
                        @endforeach
                        <td><b>{{$EscalatedTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>L1</td>
                        <div style="display:none">{{$L1Total = 0}}</div>
                        @foreach ( $ResolverTickCount as $Resolver)
                            <div style="display:none">{{$L1Total = $Resolver->L1+$L1Total}}</div>
                            <td>{{$Resolver->L1}}</td>
                        @endforeach
                        <td><b>{{$L1Total}}</b></td>
                    </tr>
                    <tr>
                        <td>L2</td>
                        <div style="display:none">{{$L2Total = 0}}</div>
                        @foreach ( $ResolverTickCount as $Resolver)
                            <div style="display:none">{{$L2Total = $Resolver->L2+$L2Total}}</div>
                            <td>{{$Resolver->L2}}</td>
                        @endforeach
                        <td><b>{{$L2Total}}</b></td>
                    </tr>
                    <tr>
                        <td class="grand"><b>Grand Total</b></td>
                        <div style="display:none">{{$GrandTotal = 0}}</div>
                        @foreach ( $ResolverTickCount as $Resolver)
                            <div style="display:none">{{$GrandTotal = $Resolver->ResolverTotal+$GrandTotal}}</div>
                            <td>{{$Resolver->ResolverTotal}}</td>
                        @endforeach
                        <td class="grand"><b>{{$GrandTotal}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="container-fluid row">
        <div class="table col-lg-4" >
            <table class="table-hover SupportCountTable display" id="SupportCountTable" style="font-size:60%;width:70%;">
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
                            <td>{{$Restick->OfficeTotal}}</td>
                        @endforeach
                        <td><b>{{$OfficeTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>Onsite</td>
                        <div style="display:none">{{$OnsiteOfficeTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$OnsiteOfficeTotal = $Restick->OnsiteOffice+$OnsiteOfficeTotal}}</div>
                            <td>{{$Restick->OnsiteOffice}}</td>
                        @endforeach
                        <td><b>{{$OnsiteOfficeTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>Phone Assistance</td>
                        <div style="display:none">{{$PhoneAssistanceOfficeTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$PhoneAssistanceOfficeTotal = $Restick->PhoneAssistanceOffice+$PhoneAssistanceOfficeTotal}}</div>
                            <td>{{$Restick->PhoneAssistanceOffice}}</td>
                        @endforeach
                        <td><b>{{$PhoneAssistanceOfficeTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>Remote</td>
                        <div style="display:none">{{$RemoteOfficeTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$RemoteOfficeTotal = $Restick->RemoteOffice+$RemoteOfficeTotal}}</div>
                            <td>{{$Restick->RemoteOffice}}</td>
                        @endforeach
                        <td><b>{{$RemoteOfficeTotal}}</b></td>
                    </tr>
                    <tr>
                        <td><b>PLANT</b></td>
                        <div style="display:none">{{$PlantTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$PlantTotal = $Restick->PlantTotal+$PlantTotal}}</div>
                            <td>{{$Restick->PlantTotal}}</td>
                        @endforeach
                        <td><b>{{$PlantTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>Onsite</td>
                        <div style="display:none">{{$OnsitePlantTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$OnsitePlantTotal = $Restick->OnsitePlant+$OnsitePlantTotal}}</div>
                            <td>{{$Restick->OnsitePlant}}</td>
                        @endforeach
                        <td><b>{{$OnsitePlantTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>Phone Assistance</td>
                        <div style="display:none">{{$PhoneAssistancePlantTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$PhoneAssistancePlantTotal = $Restick->PhoneAssistancePlant+$PhoneAssistancePlantTotal}}</div>
                            <td>{{$Restick->PhoneAssistancePlant}}</td>
                        @endforeach
                        <td><b>{{$PhoneAssistancePlantTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>Remote</td>
                        <div style="display:none">{{$RemotePlantTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$RemotePlantTotal = $Restick->RemotePlant+$RemotePlantTotal}}</div>
                            <td>{{$Restick->RemotePlant}}</td>
                        @endforeach
                        <td><b>{{$RemotePlantTotal}}</b></td>
                    </tr>
                    <tr>
                        <td><b>STORE</b></td>
                        <div style="display:none">{{$StoreTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$StoreTotal = $Restick->StoreTotal+$StoreTotal}}</div>
                            <td>{{$Restick->StoreTotal}}</td>
                        @endforeach
                        <td><b>{{$StoreTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>Onsite</td>
                        <div style="display:none">{{$OnsiteStoreTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$OnsiteStoreTotal = $Restick->OnsiteStore+$OnsiteStoreTotal}}</div>
                            <td>{{$Restick->OnsiteStore}}</td>
                        @endforeach
                        <td><b>{{$OnsiteStoreTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>Phone Assistance</td>
                        <div style="display:none">{{$PhoneAssistanceStoreTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$PhoneAssistanceStoreTotal = $Restick->PhoneAssistanceStore+$PhoneAssistanceStoreTotal}}</div>
                            <td>{{$Restick->PhoneAssistanceStore}}</td>
                        @endforeach
                        <td><b>{{$PhoneAssistanceStoreTotal}}</b></td>
                    </tr>
                    <tr>
                        <td>Remote</td>
                        <div style="display:none">{{$RemoteStoreTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$RemoteStoreTotal = $Restick->RemoteStore+$RemoteStoreTotal}}</div>
                            <td>{{$Restick->RemoteStore}}</td>
                        @endforeach
                        <td><b>{{$RemoteStoreTotal}}</b></td>
                    </tr>
                    <tr>
                        <td><b>GRAND TOTAL</b></td>
                        <div style="display:none">{{$ResolutionTotal = 0}}</div>
                        @foreach ( $ResolutionTickCount as $Restick)
                            <div style="display:none">{{$ResolutionTotal = $Restick->ResolutionTotal+$ResolutionTotal}}</div>
                            <td>{{$Restick->ResolutionTotal}}</td>
                        @endforeach
                        <td><b>{{$ResolutionTotal}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="container-fluid row">
        <div class="table col-lg-4" >
            <table class="table-hover CountTable display" id="CountTable" style="font-size:60%;width:70%;">
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
                            <td><b>{{$Prior->OfficeP1+$Prior->OfficeP2+$Prior->OfficeP3}}</b></td>
                        @endforeach
                        <td><b>{{$OfficeTotal}}</b></td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$OfficeP1Total = 0}}</div>
                        <td>P1</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$OfficeP1Total = $Prior->OfficeP1+$OfficeP1Total}}</div>
                            <td>{{$Prior->OfficeP1}}</td>
                        @endforeach
                        <td>{{$OfficeP1Total}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$OfficeP2Total = 0}}</div>
                        <td>P2</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$OfficeP2Total = $Prior->OfficeP2+$OfficeP2Total}}</div>
                            <td>{{$Prior->OfficeP2}}</td>
                        @endforeach
                        <td>{{$OfficeP2Total}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$OfficeP3Total = 0}}</div>
                        <td>P3</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$OfficeP3Total = $Prior->OfficeP3+$OfficeP3Total}}</div>
                            <td>{{$Prior->OfficeP3}}</td>
                        @endforeach
                        <td>{{$OfficeP3Total}}</td>
                    </tr>
                    <tr>
                        <td><b>Plant</b></td>
                        <div style="display:none">{{$PlantTotal = 0}}</div>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$PlantTotal = $Prior->PlantP1+$Prior->PlantP2+$Prior->PlantP3+$PlantTotal}}</div>
                            <td><b>{{$Prior->PlantP1+$Prior->PlantP2+$Prior->PlantP3}}</b></td>
                        @endforeach
                        <td><b>{{$PlantTotal}}</b></td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$PlantP1Total = 0}}</div>
                        <td>P1</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$PlantP1Total = $Prior->PlantP1+$PlantP1Total}}</div>
                            <td>{{$Prior->PlantP1}}</td>
                        @endforeach
                        <td>{{$PlantP1Total}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$PlantP2Total = 0}}</div>
                        <td>P2</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$PlantP2Total = $Prior->PlantP2+$PlantP2Total}}</div>
                            <td>{{$Prior->PlantP2}}</td>
                        @endforeach
                        <td>{{$PlantP2Total}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$PlantP3Total = 0}}</div>
                        <td>P3</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$PlantP3Total = $Prior->PlantP3+$PlantP3Total}}</div>
                            <td>{{$Prior->PlantP3}}</td>
                        @endforeach
                        <td>{{$PlantP3Total}}</td>
                    </tr>
                    <tr>
                        <td><b>Store</b></td>
                        <div style="display:none">{{$StoreTotal = 0}}</div>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$StoreTotal = $Prior->StoreP1+$Prior->StoreP2+$Prior->StoreP3+$StoreTotal}}</div>
                            <td><b>{{$Prior->StoreP1+$Prior->StoreP2+$Prior->StoreP3}}</b></td>
                        @endforeach
                        <td><b>{{$StoreTotal}}</b></td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$StoreP1Total = 0}}</div>
                        <td>P1</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$StoreP1Total = $Prior->StoreP1+$StoreP1Total}}</div>
                            <td>{{$Prior->StoreP1}}</td>
                        @endforeach
                        <td>{{$StoreP1Total}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$StoreP2Total = 0}}</div>
                        <td>P2</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$StoreP2Total = $Prior->StoreP2+$StoreP2Total}}</div>
                            <td>{{$Prior->StoreP2}}</td>
                        @endforeach
                        <td>{{$StoreP2Total}}</td>
                    </tr>
                    <tr>
                        <div style="display:none">{{$StoreP3Total = 0}}</div>
                        <td>P3</td>
                        @foreach ( $PriorTickCount as $Prior)
                            <div style="display:none">{{$StoreP3Total = $Prior->StoreP3+$StoreP3Total}}</div>
                            <td>{{$Prior->StoreP3}}</td>
                        @endforeach
                        <td>{{$StoreP3Total}}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>

<br>
@endsection