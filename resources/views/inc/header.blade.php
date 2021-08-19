<div class="d-flex p-2">
    <a href="{{route('home.index')}}"><img class="align-self-end" src="/idsi.png" alt="idsi.png" style="width: auto; height: 85px;top:10px"></a>
    <h4 class="p-1 align-self-end" style="color: #0d1a80; font-family: arial; font-weight: bold;">TICKET MONITORING SYSTEM</h4>
    @auth
    <div class="p-2 ml-auto align-self-end d-flex" id="branchid" >
        <a href="#">
            <div class="p-2 ml-auto" style="text-align: right;">
                <p style="color: #0d1a80">{{ auth()->user()->name}} {{ auth()->user()->lastname}}<br>{{Carbon\Carbon::now()->toDayDateTimeString()}}</p>
            </div>
        </a>
        <i class="fa fa-user-circle fa-3x p-2"></i>
    </div>
    @endauth
</div>