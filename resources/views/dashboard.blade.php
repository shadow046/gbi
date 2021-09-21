@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">{{\Carbon\Carbon::now()->format('F d, Y')}}</a>
    <a class="navbar-brand" id="navtime" href="#"></a>
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="monthlyBtn" value="VIEW BY MONTH">&nbsp;&nbsp;
    <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor: pointer;" id="openticketsBtn" value="TICKETS">&nbsp;&nbsp;
    @if (auth()->user()->roles->first()->name == "Manager")
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer" id="userBtn" value="USERS">&nbsp;&nbsp;
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer" id="userLogsBtn" value="USER LOGS">&nbsp;&nbsp;
    @endif
    <a href="{{route('logout')}}" class="nav-link ml-auto"><input type="button" class="btn logoutBtn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="logoutBtn" value="LOGOUT"></a>
    
    {{-- @if (auth()->user()->roles->first()->name != "Agent")
            <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="dashboardBtn" value="HOME">&nbsp;&nbsp;
    @else
        <input type="button" class="btn" style="background-color: #0d1a80;color: white;cursor:pointer !important;" id="dashboardBtn" value="OPEN TICKETS">&nbsp;&nbsp;
    @endif --}}
</nav><hr>
<span class="container" style="font-size:130%;color:#00127f"><b>DASHBOARD</b></span>
<hr>
<div class="d-flex justify-content-center row">
    <div class="card mb-4 col-3" style="max-width: 20rem;">
        <h6 class="card-header text-center" style="color:white;background-color:#00127f;">TOTAL TICKETS</h6>
        <div class="card-body bg-light">
            @if (auth()->user()->hasrole('Manager'))
                <a href="#" id="OpenTicketDiv"><div class="card-text" style="float:left;">Open Tickets</div><div class="card-text" style="float:right;">{{number_format($open)}}</div></a><br>
                <a href="#" id="CloseTicketDiv"><div class="card-text" style="float:left;">Closed Tickets</div><div class="card-text" style="float:right;">{{number_format($closed)}}</div></a><br>
            @else
                <div class="card-text" style="float:left;">Open Tickets</div><div class="card-text" style="float:right;">{{number_format($open)}}</div><br>
                <div class="card-text" style="float:left;">Closed Tickets</div><div class="card-text" style="float:right;">{{number_format($closed)}}</div><br>
                <div class="card-text" style="float:left;">Cancelled Tickets</div><div class="card-text" style="float:right;">{{number_format($cancelled)}}</div><br>
            @endif
            <hr>
            <a href="{{route('opentickets')}}"><div class="card-text" style="float:left;"><b>Total Tickets</b></div><div class="card-text" style="float:right;"><b>{{number_format($open+$closed+$cancelled)}}</b></div></a><br>
        </div>
    </div>
    @if (auth()->user()->hasrole('Manager'))
        <div class="card mb-4 col-3" style="max-width: 20rem;">
            <h6 class="card-header text-center" style="color:white;background-color:#00127f;">TOP AGENT</h6>
            <div class="card-body bg-light">
                @foreach ($filtereda as $key => $value)
                    @if ($loop->index < 5)
                        <div class="card-text" style="float:left;">{{$key}}</div><div class="card-text" style="float:right;">{{$value}}</div><br>
                    @endif
                @endforeach
                <p></p>
                {{-- <div class="text-center" style="height:20px;"><a href="#" class="btn btn-secondary" id="TopIssueMore" style="height:25px;margin-bottom:5px;padding-top:1px;font-size:14px;">More</a></div> --}}
            </div>
        </div>
    @endif
    <div class="card mb-4 col-3" style="max-width: 20rem;">
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
    <div class="card mb-4 col-3" style="max-width: 20rem;">
        <h6 class="card-header text-center" style="color:white;background-color:#00127f;">AGING TICKETS</h6>
        <div class="card-body bg-light">
            <div class="card-text" style="float:left;"></div>Less than 5 days<div class="card-text" style="float:right;">{{$lessthan5}}</div><br>
            <div class="card-text" style="float:left;"></div>6 to 10 days<div class="card-text" style="float:right;">{{$sixto10}}</div><br>
            <div class="card-text" style="float:left;"></div>11 to 15 days<div class="card-text" style="float:right;">{{$elevento15}}</div><br>
            <div class="card-text" style="float:left;"></div>16 to 20 days<div class="card-text" style="float:right;">{{$sixteento20}}</div><br>
            <div class="card-text" style="float:left;"></div>More than 20 days<div class="card-text" style="float:right;">{{$greaterthan20}}</div><br>
            <p></p>
            <div class="text-center" style="height:20px;"><a href="#" class="btn btn-secondary" id="AgingMore" style="height:25px;margin-bottom:5px;padding-top:1px;font-size:14px;">More</a></div>
        </div>
    </div>
</div>
{{-- <div class="d-flex justify-content-center row">
    <div class="card mb-4 col-3" style="max-width: 20rem;">
        <h6 class="card-header text-center" style="color:white;background-color:#00127f;">CALL TYPE</h6>
        <div class="card-body bg-light">
            @foreach ($filteredi as $key => $value)
                @if ($loop->index < 5)
                    <div class="card-text" style="float:left;">{{$key}}</div><div class="card-text" style="float:right;">{{$value}}</div><br>
                @endif
            @endforeach
            <p></p>
        </div>
    </div>
    <div class="card mb-4 col-3" style="max-width: 20rem;">
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
    <div class="card mb-4 col-3" style="max-width: 20rem;">
        <h6 class="card-header text-center" style="color:white;background-color:#00127f;">AGING TICKETS</h6>
        <div class="card-body bg-light">
            <div class="card-text" style="float:left;"></div>Less than 5 days<div class="card-text" style="float:right;">{{$lessthan5}}</div><br>
            <div class="card-text" style="float:left;"></div>6 to 10 days<div class="card-text" style="float:right;">{{$sixto10}}</div><br>
            <div class="card-text" style="float:left;"></div>11 to 15 days<div class="card-text" style="float:right;">{{$elevento15}}</div><br>
            <div class="card-text" style="float:left;"></div>16 to 20 days<div class="card-text" style="float:right;">{{$sixteento20}}</div><br>
            <div class="card-text" style="float:left;"></div>More than 20 days<div class="card-text" style="float:right;">{{$greaterthan20}}</div><br>
            <p></p>
            <div class="text-center" style="height:20px;"><a href="#" class="btn btn-secondary" id="AgingMore" style="height:25px;margin-bottom:5px;padding-top:1px;font-size:14px;">More</a></div>
        </div>
    </div>
</div> --}}
<div class="container-fluid text-center" style="width:100%;height:100%">
        <span><b>LAST 30 DAYS</b></span>
</div>
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
        <canvas id="dailyChart" height="250" width='900' style="margin:0 auto"></canvas>
    </div>
</div>
@endsection