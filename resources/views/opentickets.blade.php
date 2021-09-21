@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    @if (!auth()->user()->hasrole("Agent"))
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="dashboardBtn" value="DASHBOARD">&nbsp;&nbsp;
    @endif
    @if (!auth()->user()->hasrole( "Client"))
        <input type="button" class="btn createBtn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" value="CREATE TICKET">&nbsp;&nbsp;
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
<div class="table container-fluid" style="overflow-x: auto;white-space: nowrap;">
    @if (!auth()->user()->hasrole("Agent"))
    @endif
    <table class="table-hover gbiTable display nowrap" id="gbiTable" style="font-size:70%;width:100%">
        <thead style="background-color:#00127f;color:white">
            <tr>
                <th>DATE</th>
                <th>TICKET NUMBER</th>
                <th>AGE</th>
                <th>CALL TYPE</th>
                <th>CATEGORY</th>
                <th>ISSUE</th>
                <th>STORE CODE</th>
                <th>STORE NAME</th>
                @if (auth()->user()->hasrole('Manager'))
                    <th>PF STATUS</th>
                @endif
                <th>SYSTEM STATUS</th>
                <th>INCIDENT STATUS</th>
            </tr>
            <tr>
                <th>DATE</th>
                <th>TICKET NUMBER</th>
                <th>AGE</th>
                <th>CALL TYPE</th>
                <th>CATEGORY</th>
                <th>ISSUE</th>
                <th>STORE CODE</th>
                <th>STORE NAME</th>
                @if (auth()->user()->hasrole('Manager'))
                    <th>PF STATUS</th>
                @endif
                <th>SYSTEM STATUS</th>
                <th>INCIDENT STATUS</th>
            </tr>
        </thead>
    </table>
</div>
<br>
@endsection