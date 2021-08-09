@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    <input type="button" class="ml-auto btn btn-secondary" id="graphBtn" value="DASHBOARD">
    <div class="ml-auto"id="search"></div>
</nav><hr>
<div class="container-fluid text-center" style="width:100%;height:100%">
        <input type="button" id="dailyBtn" value="LAST 30 DAYS">
</div>
<hr>
<div class="container-fluid text-center">
    <select id="yearselect" style="color: black">
        <option selected disabled>select year</option>
    </select>
    <select id="monthselect" style="color: black" disabled>
        <option selected disabled>select month</option>
    </select>
    <select id="groupselect" style="color: black;display:none">
        <option selected value="day">DAILY</option>
        <option value="week">WEEKLY</option>
    </select>
</div>
<hr>
<div class="text-center" id="bydays">
    <div class="container-fluid" style="width:100%;height:100%">
        <table id="data" style="margin: 0 auto;">
            <thead id="datahead" style="background-color:#00127f;color:white">
            </thead>
            <tbody id="databody">
            </tbody>
            <tfoot id="datafoot">
            </tfoot>
        </table>
        <canvas id="dailyChart" height="200" width='900' style="margin:0 auto"></canvas>
    </div>
</div>
<hr>
{{-- <div class="text-center" id="byweeks">
    <div class="container-fluid" style="width:100%;height:100%">
        <table id="dataW" style="margin: 0 auto;">
            <thead id="dataheadW" style="background-color:#00127f;color:white">
            </thead>
            <tbody id="databodyW">
            </tbody>
            <tfoot id="datafootW">
            </tfoot>
        </table>
        <canvas id="dailyChartW" height="200" width='900' style="margin:0 auto"></canvas>
    </div>
</div> --}}
<hr>
@endsection