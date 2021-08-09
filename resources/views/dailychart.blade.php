@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    <input type="button" class="ml-auto btn btn-secondary" id="dashboardBtn" value="DASHBOARD">
    <div class="ml-auto"id="search"></div>
</nav><hr>
<div class="container-fluid text-center" style="width:100%;height:100%">
        <input type="button" id="monthlyBtn" value="Monthly">
</div>
<hr>
<div class="text-center">
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
@endsection