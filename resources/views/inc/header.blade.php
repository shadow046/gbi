<div class="d-flex">
    <a href="{{route('home.index')}}"><img class="align-self-end" src="/idsi.png" alt="idsi.png" style="width: auto; height: 90px;"></a>
    <h4 class="p-3 align-self-end" style="color: #0d1a80; font-family: arial; font-weight: bold;">TICKET MONITORING SYSTEM</h4>
    {{-- <a href="{{route('home.index')}}"><img class="align-self-end" src="/idsi.png" alt="idsi.png" style="width: auto; height: 90px;"></a>
    <h3 class="p-2 align-self-end" style="color: #0d1a80; font-family: arial; font-weight: bold;">TICKET MONITORING SYSTEM</h4> --}}
    @auth
    <div class="p-1 ml-auto align-self-end d-flex" id="branchid" >
        <a href="{{route('change.password')}}">
            <div class="p-2 ml-auto" style="text-align: right;">
                <p style="color: #0d1a80">{{ auth()->user()->name}} {{ auth()->user()->lastname}}
                <br>{{auth()->user()->roles->first()->name}}
                <br>{{Carbon\Carbon::now()->toDayDateTimeString()}}
                </p>
            </div>
        </a>
        <i class="fa fa-user-circle fa-4x p-2"></i>
    </div>
    <input type="hidden" id="role">
    @endauth
</div>