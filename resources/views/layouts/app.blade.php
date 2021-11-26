<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Expires" content="0" />
        {{-- <meta http-equiv="refresh" content="300"> --}}
        <script src="https://unpkg.com/jquery@2.2.4/dist/jquery.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        {{-- <link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ url('/css/styles.css') }}" /> --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.css" rel="stylesheet" type="text/css" />
        <link href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" rel="Stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-print-1.6.5/sl-1.3.1/datatables.min.css"/>
        
        <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon" />
        <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon" />
       
        <style>
            #loading {
                display: none;
                position: absolute;
                top: 0;
                left: 0;
                z-index: 100;
                width: 100vw;
                height: 600vh;
                background-color: rgba(192, 192, 192, 0.5);
                background-image: url("{{asset('loading.gif')}}");
                background-repeat: no-repeat;
                background-position: center;
                }
            input, select, textarea{
                color: black;
            }
            .legend { list-style: none; }
            .legend li { float: left; margin-right: 10px; }
            .legend span { border: 1px solid #ccc; float: left; width: 12px; height: 12px; margin: 2px; }
            /* your colors */
            .legend .BLUE { background-color: blue; }
            .legend .GREEN { background-color: green; }
            .legend .MAGENTA { background-color: darkmagenta; }
            .legend .GRAYROW { background-color: gray; }
            .legend .RED { background-color: #F1423A; }
            {{-- li:hover {
                background-color: #4285f4;
                color: white !important; 
                border-radius: 4px !important;
            } --}}
            .nohover:hover {
                background-color: transparent !important;
            }
            .nav-link{
                border: 0px solid #88f2fa !important;
                border-radius: 4px !important;
                padding-right:8px !important;
                padding-left:8px !important;
                padding: 4px
            }
            .active{
                padding: 4px 0;
            }
            .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
                background-color: #DCDCDC;
            }
            .dataTables_filter input { height: 20px }
            .label, .input {
                display: block;
                margin-bottom:2px;
            }
            .input{
                height:25px;
            }
            .label{
                font-size:11px;
                font-weight: bold;
                font-family: 'Arial';
            }
            {{-- button[type=button], button[type=submit], button[type=reset], input[type=button], input[type=submit], input[type=reset] {
                background-color: #0d1a80;
            } --}}
            .bg-blue{
                background-color: #0d1a80;
                color:white;
            }
            .bg-blue:hover{
                background-color: #0d1a80;
                color:white;
            }
            table, th, td {
                border: 1px solid  #d3d3d3;;
            }
            tfoot {
                background-color:#00127f;
                color:white
            }
            div#gbiTable_wrapper {
                margin: 0 auto;
                float: left;
            }
            #ResTickCountTable tr {
                line-height: 2px;
                min-height: 2px;
                height: 2px;
            }
            #CountTable tr {
                line-height: 2px;
                min-height: 2px;
                height: 2px;
            }
            .month{
                min-width: 80px;
            }
            .range{
                min-width: 120px;
            }
            .grand{
                min-width: 110px;
            }.closedt{
                min-width: 110px;
            }
            .nav {
                background: #0d1a80;
                border: 0;
            }
            .nav li a {
                color: white;
            }
            .nav .active{
                color: white;
                background: gray;
            }
            .nav li a:hover {
                background-color: #4285f4;
                color: white !important; 
                border-radius: 4px !important;
            }
            .center {
                margin: auto;
                width: 60%;
                padding: 10px;
            }
        </style>
    </head>
    <body style="overflow-x: hidden;">
        @include('inc.header')
        @yield('content')

        @if(Request::is('/'))
            @include('modal.topissue')
            @include('modal.aging')
            @include('modal.userlogs')
            @include('modal.user')
        @endif

        @if(Request::is('openticket'))
            @include('modal.ticketdetails')
        @endif

        @if(Request::is('users'))
            @include('modal.user')
        @endif

        @if(Request::is('closedticket'))
            @include('modal.ticketdetails')
        @endif

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script type="application/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.js"></script-->
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-print-1.6.5/sl-1.3.1/datatables.min.js"></script>
        {{-- <script type="text/javascript" src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}
        {{-- <script type="text/javascript" src="{{asset('js/moment.min.js')}}"></script> --}}
        {{-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
        <script type="text/javascript" src="{{asset('js/moment.min.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0-rc/dist/chartjs-plugin-datalabels.min.js"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js" charset="utf-8"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
        
        @if (Request::is('openticket'))
            <script src="{{asset('js/opentickets.js')}}"></script>
        @endif
        @if (Request::is('dash/totalticket/*'))
            <script src="{{asset('js/dashtotalticket.js')}}"></script>
        @endif
        @if (Request::is('dash/*'))
            <script src="{{asset('js/calendar.js')}}"></script>
        @endif
        @if (Request::is('dash/pcategory/*'))
            <script src="{{asset('js/ProblemCategory.js')}}"></script>
        @endif
        @if (Request::is('dash/resolvetick/*'))
            <script src="{{asset('js/resolvetick.js')}}"></script>
        @endif
        @if (Request::is('dash/priorstatus/*'))
            <script src="{{asset('js/priorstatus.js')}}"></script>
        @endif
        @if (Request::is('dash/resolverstatus/*'))
            <script src="{{asset('js/resolverstatus.js')}}"></script>
        @endif
        @if (Request::is('dash/dependencies/*'))
            <script src="{{asset('js/dependencies.js')}}"></script>
        @endif
        @if (Request::is('/'))
            <script src="{{asset('js/dashboard.js')}}"></script>
        @endif
        @if (Request::is('closedticket'))
            <script src="{{asset('js/closedtickets.js')}}"></script>
        @endif
        @if (Request::is('dailytickets'))
            <script src="{{asset('js/dailycharts.js')}}"></script>
        @endif
        @if (Request::is('dashs'))
            <script src="{{asset('js/dash.js')}}"></script>
        @endif
        @if (Request::is('dashboard'))
            <script src="{{asset('js/dashboard.js')}}"></script>
        @endif
        @if (Request::is('monthlytickets'))
            <script src="{{asset('js/monthlycharts.js')}}"></script>
        @endif
        @if (Request::is('weeklytickets'))
            <script src="{{asset('js/weeklycharts.js')}}"></script>
        @endif
        @if (Request::is('users'))
            <script src="{{asset('js/users.js')}}"></script>
        @endif
        @if (Request::is('email/verify'))
            <script src="{{asset('js/verify.js')}}"></script>
        @endif
        <div id="loading"></div>
    </body>
</html>