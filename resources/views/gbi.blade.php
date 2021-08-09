@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Daily Activities</a>
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    <input type="button" class="ml-auto btn btn-secondary" id="graphBtn" value="GRAPHS">
    <div class="ml-auto"id="search"></div>
    
</nav>
<div class="table-responsive container-fluid">
    <table class="table-hover table gbiTable" id="gbiTable" style="font-size:70%;width:100%">
        <thead style="background-color:#00127f;color:white">
            <tr>
                <th>DATE CREATED</th>
                <th>TICKET NUMBER</th>
                <th>ISSUE</th>
                {{-- <th>Store Type</th> --}}
                <th>GBI SBU</th> 
                <th>INCIDENT STATUS</th>
            </tr>
        </thead>
    </table>
</div>
<br>
<div class="container row">
    <div class="card mb-3 col" style="max-width: 20rem;">
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
    <div class="card mb-3 col" style="max-width: 20rem;">
        <h6 class="card-header text-center" style="color:white;background-color:#00127f;">AGING TICKETS</h6>
        <div class="card-body bg-light">
            <div class="card-text" style="float:left;"></div>Less than 5 days<div class="card-text" style="float:right;">{{$lessthan5}}</div><br>
            <div class="card-text" style="float:left;"></div>More than 6 to 10 days<div class="card-text" style="float:right;">{{$sixto10}}</div><br>
            <div class="card-text" style="float:left;"></div>More than 11 to 15 days<div class="card-text" style="float:right;">{{$elevento15}}</div><br>
            <div class="card-text" style="float:left;"></div>More than 16 to 20 days<div class="card-text" style="float:right;">{{$sixteento20}}</div><br>
            <div class="card-text" style="float:left;"></div>More than 20 days<div class="card-text" style="float:right;">{{$greaterthan20}}</div><br>
            <p></p>
            <div class="text-center" style="height:20px;"><a href="#" class="btn btn-secondary" style="height:25px;margin-bottom:5px;padding-top:1px;font-size:14px;">More</a></div>
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
@endsection