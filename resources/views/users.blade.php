@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="openTicketBtn" value="DASHBOARD">&nbsp;&nbsp;
    @if (auth()->user()->roles->first()->name != "Agent")
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="closeTicketBtn" value="CLOSED TICKETS">&nbsp;&nbsp;
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="graphBtn" value="GRAPHS">&nbsp;&nbsp;
    @endif
    <a href="{{route('logout')}}" class="nav-link ml-auto"><input type="button" class="btn logoutBtn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="logoutBtn" value="LOGOUT"></a>
</nav>
<div class="table-responsive container-fluid">
    <table class="table-hover table" id="userTable" style="font-size:80%">
        <thead style="background-color:#00127f;color:white">
            <tr>
                <th>FULL NAME</th>
                <th>EMAIL</th>
                <th>LEVEL</th>
                <th>STATUS</th>
            </tr>
            <tr>
                <th>FULL NAME</th>
                <th>EMAIL</th>
                <th>LEVEL</th>
                <th>STATUS</th>
            </tr>
        </thead>
    </table>
</div>
<br>
<div class="container-fluid">
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="addBtn" value="ADD USER">
</div>
@endsection