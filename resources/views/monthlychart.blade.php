@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="dashboardBtn" value="LAST 30 DAYS">&nbsp;&nbsp;
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="openticketsBtn" value="TICKETS">&nbsp;&nbsp;
    <a href="{{route('logout')}}" class="nav-link ml-auto"><input type="button" class="btn logoutBtn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="logoutBtn" value="LOGOUT"></a>
</nav><hr>
<div class="container-fluid ptext text-center" style="width:100%;height:100%;">
    <p class="ml-auto" id="ptext"><b>MONTHLY / WEEKLY VIEW</b></p>
</div>
<hr>
<div class="container-fluid">
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
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;display:none;" id="exportBtn" value="EXPORT" >
    </div>
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
    <div id="chart1"></div>
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
    <div id="chart2"></div>
</div>
<hr>
@endsection