@extends('layouts.app')
@section('content')
@include('inc.nav-dashboard')
<br>
    <div class="container-fluid row" id="TotalTicketDiv">
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
                <tfoot>
                    <tr><th>GRAND TOTAL</th><th id="TStore"></th><th id="TOffice"></th><th id="TPlant"></th><th></th><th></th></tr>
                </tfoot>
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
                <tbody id="topTableBody">
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
    <div class="container-fluid">
        <canvas id="dailyChart" height="70" width='auto' style="margin:0 auto"></canvas>
    </div>
    <hr>
@endsection