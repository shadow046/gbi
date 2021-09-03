@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    @if (auth()->user()->roles->first()->name != "Agent")
        @if (auth()->user()->roles->first()->name == "Manager")
            <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="logsBtn" value="USER LOGS">&nbsp;&nbsp;
        @endif
        <a href="{{route('closed')}}" class="nav-link"><input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="closeTicketBtn" value="CLOSED TICKETS"></a>&nbsp;&nbsp;
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="graphBtn" value="GRAPHS">&nbsp;&nbsp;
    @endif
    @if (auth()->user()->roles->first()->name != "Client")
        <input type="button" class="btn createBtn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" value="CREATE TICKET">&nbsp;&nbsp;
    @endif
    @if (auth()->user()->roles->first()->name == "Manager")
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="userBtn" value="USERS">&nbsp;&nbsp;
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
    <span style="font-size:130%;color:#00127f"><b>DASHBOARD</b></span>
    <table class="table-hover gbiTable display nowrap" id="gbiTable" style="font-size:70%;width:100%">
        <thead style="background-color:#00127f;color:white">
            <tr>
                <th>DATE</th>
                <th>TICKET NUMBER</th>
                <th>AGE</th>
                <th>CATEGORY</th>
                <th>ISSUE</th>
                {{-- <th>Store Type</th> --}}
                <th>STORE CODE</th>
                <th>STORE NAME</th>
                <th>INCIDENT STATUS</th>
                <th>LATEST NOTES</th>
            </tr>
            <tr>
                <th>DATE</th>
                <th>TICKET NUMBER</th>
                <th>AGE</th>
                <th>CATEGORY</th>
                <th>ISSUE</th>
                {{-- <th>Store Type</th> --}}
                <th>STORE CODE</th>
                <th>STORE NAME</th>
                <th>INCIDENT STATUS</th>
                <th>LATEST NOTES</th>
            </tr>
        </thead>
    </table>
</div>
<br>
@if (auth()->user()->roles->first()->name != "Agent")
    <div class="d-flex justify-content-center row">
        <div class="card mb-4 col-6" style="max-width: 20rem;">
            <h6 class="card-header text-center" style="color:white;background-color:#00127f;">TOP ISSUES</h6>
            <div class="card-body bg-light">
                @foreach ($filtered as $key => $value)
                    @if ($loop->index < 5)
                        <div class="card-text" style="float:left;">{{$key}}</div><div class="card-text" style="float:right;">{{$value}}</div><br>
                    @endif
                @endforeach
                <p></p>
                <div class="text-center" style="height:20px;"><a href="#" class="btn btn-secondary" id="TopIssueMore" style="height:25px;margin-bottom:5px;padding-top:1px;font-size:14px;">More</a></div>
            </div>
        </div>
        <div class="card mb-4 col-6" style="max-width: 20rem;">
            <h6 class="card-header text-center" style="color:white;background-color:#00127f;">AGING TICKETS</h6>
            <div class="card-body bg-light">
                <div class="card-text" style="float:left;"></div>Less than 5 days<div class="card-text" style="float:right;">{{$lessthan5}}</div><br>
                <div class="card-text" style="float:left;"></div>6 to 10 days<div class="card-text" style="float:right;">{{$sixto10}}</div><br>
                <div class="card-text" style="float:left;"></div>11 to 15 days<div class="card-text" style="float:right;">{{$elevento15}}</div><br>
                <div class="card-text" style="float:left;"></div>16 to 20 days<div class="card-text" style="float:right;">{{$sixteento20}}</div><br>
                <div class="card-text" style="float:left;"></div>More than 20 days<div class="card-text" style="float:right;">{{$greaterthan20}}</div><br>
                <p></p>
                {{-- <div class="text-center" style="height:20px;"><a href="#" class="btn btn-secondary" style="height:25px;margin-bottom:5px;padding-top:1px;font-size:14px;">More</a></div> --}}
            </div>
        </div>
        <div class="card mb-3 col" style="max-width: 20rem;display:none">
            <h6 class="card-header text-center" style="color:white;background-color:#00127f;">RESOLVER GROUP</h6>
            <div class="card-body bg-light">
                @foreach ($filtered as $key => $value)
                    @if ($loop->index < 5)
                        <div class="card-text" style="float:left;">{{$key}}</div><div class="card-text" style="float:right;">{{$value}}</div><br>
                    @endif
                @endforeach
                <p></p>
                <div class="text-center" style="height:20px;"><a href="#" class="btn btn-secondary" style="height:25px;margin-bottom:5px;padding-top:1px;font-size:14px;">More</a></div>
            </div>
        </div>
    </div>
@endif
@endsection