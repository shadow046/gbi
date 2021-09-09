@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="dashboardBtn" value="DASHBOARD">&nbsp;&nbsp;
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="openticketsBtn" value="OPEN TICKETS">&nbsp;&nbsp;
    @if (auth()->user()->roles->first()->name != "Client")
        <input type="button" class="btn createBtn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" value="CREATE TICKET">&nbsp;&nbsp;
    @endif
    <a href="{{route('logout')}}" class="nav-link ml-auto"><input type="button" class="btn logoutBtn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="logoutBtn" value="LOGOUT"></a>
</nav>

<div class="table-responsive container-fluid">
    <span style="font-size:130%;color:#00127f"><b>CLOSED TICKETS</b></span>
    <table class="table-hover table gbiTable" id="gbiTable" style="font-size:70%;width:100%">
        <thead style="background-color:#00127f;color:white">
            <tr>
                <th>Date</th>
                <th>TICKET NUMBER</th>
                <th>CATEGORY</th>
                <th>ISSUE</th>
                {{-- <th>Store Type</th> --}}
                <th>STORE CODE</th>
                <th>STORE NAME</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>TICKET NUMBER</th>
                <th>CATEGORY</th>
                <th>ISSUE</th>
                {{-- <th>Store Type</th> --}}
                <th>STORE CODE</th>
                <th>STORE NAME</th>
            </tr>
        </thead>
    </table>
</div>
@endsection