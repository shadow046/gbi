@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="graphBtn" value="DASHBOARD">&nbsp;&nbsp;
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="dailyBtn" value="VIEW LAST 30 DAYS">&nbsp;&nbsp;
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;display:none" id="exportBtn" value="EXPORT" >
    <div class="ml-auto"id="search"></div>
</nav><hr>
<div class="container-fluid ptext text-center" style="width:100%;height:100%;display:none">
    <p class="ml-auto" id="ptext"></p>
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
    </div>
    <canvas id="dailyChart" height="250" width='900' style="margin:0 auto"></canvas>
</div>
<hr>
<div class="text-center" id="byweeks" style="display:none">
    <div class="container-fluid" style="width:100%;height:100%">
        <table id="dataW" style="margin: 0 auto;">
            <thead id="dataheadW" style="background-color:#00127f;color:white">
            </thead>
            <tbody id="databodyW">
            </tbody>
            <tfoot id="datafootW">
            </tfoot>
        </table>
    </div>
    <canvas id="dailyChartW" height="250" width='900' style="margin:0 auto"></canvas>
</div>
<hr>
@endsection