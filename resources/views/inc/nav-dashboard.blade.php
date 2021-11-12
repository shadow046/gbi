<div>
    <div class="container-fluid d-flex">
        <a href="/"><input type="button" class="btn" style="margin-left:0px;margin-right:0px;border-radius: 0px;background-color: gray;color: white;cursor: pointer;" id="dashboardBtn" value="DASHBOARD"></a>&nbsp;&nbsp;
        <input type="button" class="btn" style="margin-left:-5px;margin-right:0px;border-radius: 0px;background-color: #0d1a80;color: white;cursor: pointer;" id="dashboardBtn" value="REPORTS">&nbsp;&nbsp;
        <input type="button" class="btn logoutBtn ml-auto" style="border-radius: 0px;background-color: #0d1a80;color: white;cursor:pointer !important;" id="logoutBtn" value="LOGOUT">
    </div>
    <ul class="nav mr-auto list-inline" style="font-size: 14px">
        <li class="nav-item" style="margin-left:50px;margin-right:0px;padding-top: 5px;padding-bottom: 5px;">
            <a class="nav-link {{ Request::is('dash/totalticket/*/*') ? 'active' : '' }}" href="#" id="TotalTicket">TOTAL TICKETS</a>
        </li>
        <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
            <a class="nav-link {{ Request::is('dash/pcategory/*/*') ? 'active' : '' }}" href="#" id="ProblemCategory">PROBLEM CATEGORY</a>
        </li>
        {{-- <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
            <a class="nav-link {{ Request::is('dash/statsum') ? 'active' : '' }}" href="{{ url('/dash/statsum') }}">STATUS SUMMARY</a>
        </li> --}}
        <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
            <a class="nav-link {{ Request::is('dash/resolvetick/*/*') ? 'active' : '' }}" href="#" id="ResolveTick">RESOLVED TICKETS</a>
        </li>
        <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
            <a class="nav-link {{ Request::is('dash/priorstatus/*/*') ? 'active' : '' }}" href="#" id="PriorStatus">PRIORITY STATUS</a>
        </li>
        <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
            <a class="nav-link {{ Request::is('dash/resolverstatus/*/*') ? 'active' : '' }}" href="#" id="ResolverStatus">RESOLVER STATUS</a>
        </li>
        <li class="nav-item" style="margin-left:0px;margin-right:0px;padding-top: 5px;">
            <a class="nav-link {{ Request::is('dash/dependencies/*/*') ? 'active' : '' }}" href="#" id="Dependencies">DEPENDENCIES</a>
        </li>
    </ul>
    <br>
    <div class="container d-flex">
        <label class="ml-auto">SELECT DATE RANGE :</label>&nbsp;&nbsp;&nbsp;
        <input type="text" style="color: black" class="form-control-sm datepicker" value="from" name="datefrom" id="datefrom" readonly="readonly" autocomplete="off">&nbsp;&nbsp;&nbsp;
        <input type="text" style="color: black" class="form-control-sm datepicker2" value="to" name="dateto" id="dateto" readonly="readonly" autocomplete="off">&nbsp;&nbsp;&nbsp;
        <input type="button" class="btn" style="margin-top:-5px;margin-right:0px;border-radius: 20px;background-color: #0d1a80;color: white;cursor: pointer;" id="goBtn" value="Go" disabled>
    </div>
</div>