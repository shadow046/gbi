<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\Task;
use DB;

class ViewController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'CheckPassword']);
    }

    public function users()
    {
        return view('users');
    }
    
    public function monthlytickets()
    {
        return view('monthlychart');
    }

    public function weeklytickets()
    {
        return view('weeklychart');
    }
    public function dashboard()
    {
        if (auth()->user()->hasrole('Agent')) {
            return redirect()->to('/openticket');
        }
        $open = Ticket::query()
            ->whereNotIN('TaskStatus',['Closed'])
            ->whereNotIN('IncidentStatus', ['Closed', 'Resolved'])
            // ->whereNotIn('Status',['Closed'])
            ->count();
        $closed = Ticket::query()
            // ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->where('Status','Closed')
            ->count();

        $TopIssues = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->whereIN('Status', ['CLosed', 'Open'])
            ->groupBy('SubCategory')
            ->get();
        $top = collect([]);
        foreach ($TopIssues as $issue) {
            if ($issue->SubCategory != Null) {
                // return $issue->Total;
                $top->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $filtered = $top->sortDesc();
        $fivedaysStore = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $fivedaysPlant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $fivedaysOffice = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $sixto10Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $sixto10Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $sixto10Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $elevento15Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $elevento15Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $elevento15Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $sixteento20Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $sixteento20Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $sixteento20Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $greaterthan20Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        $greaterthan20Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        $greaterthan20Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        $fivedays = $fivedaysOffice+$fivedaysPlant+$fivedaysStore;
        $sixto10 = $sixto10Office+$sixto10Plant+$sixto10Store;
        $elevento15 = $elevento15Office+$elevento15Plant+$elevento15Store;
        $sixteento20 = $sixteento20Office+$sixteento20Plant+$sixteento20Store;
        $greaterthan20 = $greaterthan20Office+$greaterthan20Plant+$greaterthan20Store;
        $lessthan5 = $fivedaysOffice+$fivedaysPlant+$fivedaysStore;
        return view('dashboard',
            compact(
                'filtered',
                'lessthan5',
                'greaterthan20',
                'greaterthan20Office',
                'greaterthan20Plant',
                'greaterthan20Store',
                'sixteento20',
                'sixteento20Office',
                'sixteento20Plant',
                'sixteento20Store',
                'elevento15',
                'sixto10Office',
                'elevento15Plant',
                'elevento15Office',
                'elevento15Store',
                'sixto10',
                'sixto10Office',
                'sixto10Plant',
                'sixto10Store',
                'fivedays',
                'fivedaysOffice',
                'fivedaysPlant',
                'fivedaysStore',
                'open',
                'closed')
            );
    }

    public function dailytickets()
    {
        $Store = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->join('Data','Code','StoreCode')
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->where('SBU', 'Store')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $Plant = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->join('Data','Code','StoreCode')
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->where('SBU', 'Plant')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $Office = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->join('Data','Code','StoreCode')
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->where('SBU', 'Office')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $date = Ticket::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $dates = Ticket::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"),)
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->pluck('date');
        $ofc = [];
        $plnt = [];
        $str = [];
        foreach ($date as $key) {
            if ($Office->where('Date', $key->date)->first()) {
                array_push($ofc, $Office->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($ofc, 0);
            }
            if ($Plant->where('Date', $key->date)->first()) {
                array_push($plnt, $Plant->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($plnt, 0);
            }
            if ($Store->where('Date', $key->date)->first()) {
                array_push($str, $Store->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($str, '0');
            }
        }

        $plnt = collect($plnt);
        $str = collect($str);
        $ofc = collect($ofc);
        $strtotal = $str->sum(function($item) {
            return $item;
        });
        $plnttotal = $plnt->sum(function($item) {
            return $item;
        });
        $ofctotal = $ofc->sum(function($item) {
            return $item;
        });
        $grandtotal = array();
        foreach ($str as $key => $value) {
            array_push($grandtotal, $str[$key]+$plnt[$key]+$ofc[$key]);
        }
        array_push($grandtotal, $strtotal+$plnttotal+$ofctotal);
        return view('dailychart', compact('dates', 'plnt', 'str', 'ofc', 'strtotal', 'plnttotal', 'ofctotal', 'grandtotal'));
    }

    public function open()
    {
        $TopIssues = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'))
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->groupBy('SubCategory')
            ->get();
        $top = collect([]);
        foreach ($TopIssues as $issue) {
            if ($issue->SubCategory != Null) {
                // return $issue->Total;
                $top->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $filtered = $top->sortDesc();
        $fivedaysStore = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $fivedaysPlant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $fivedaysOffice = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $sixto10Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $sixto10Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $sixto10Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $elevento15Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $elevento15Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $elevento15Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $sixteento20Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $sixteento20Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $sixteento20Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $greaterthan20Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        $greaterthan20Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        $greaterthan20Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        $fivedays = $fivedaysOffice+$fivedaysPlant+$fivedaysStore;
        $sixto10 = $sixto10Office+$sixto10Plant+$sixto10Store;
        $elevento15 = $elevento15Office+$elevento15Plant+$elevento15Store;
        $sixteento20 = $sixteento20Office+$sixteento20Plant+$sixteento20Store;
        $greaterthan20 = $greaterthan20Office+$greaterthan20Plant+$greaterthan20Store;
        $lessthan5 = $fivedaysOffice+$fivedaysPlant+$fivedaysStore;
        return view('opentickets',
            compact(
                'filtered',
                'lessthan5',
                'greaterthan20',
                'greaterthan20Office',
                'greaterthan20Plant',
                'greaterthan20Store',
                'sixteento20',
                'sixteento20Office',
                'sixteento20Plant',
                'sixteento20Store',
                'elevento15',
                'sixto10Office',
                'elevento15Plant',
                'elevento15Office',
                'elevento15Store',
                'sixto10',
                'sixto10Office',
                'sixto10Plant',
                'sixto10Store',
                'fivedays',
                'fivedaysOffice',
                'fivedaysPlant',
                'fivedaysStore'
            )
        );
    }

    public function closed(Request $request)
    {
        if (auth()->user()->hasanyrole('Agent')) {
            return redirect('/');
        }
        $TopIssues = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'))->groupBy('SubCategory')->get();
        $top = collect([]);
        foreach ($TopIssues as $issue) {
            if ($issue->SubCategory != Null) {
                // return $issue->Total;
                $top->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $filtered = $top->sortDesc();

        $lessthan5 = Ticket::query()->select('TaskNumber')
        ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
        ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
        ->whereDate('DateCreated', '<=', Carbon::now())
        ->count();
        $sixto10 = Ticket::query()->select('TaskNumber')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $elevento15 = Ticket::query()->select('TaskNumber')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $sixteento20 = Ticket::query()->select('TaskNumber')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $greaterthan20 = Ticket::query()->select('TaskNumber')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        return view('closedtickets', compact('filtered', 'lessthan5', 'sixto10', 'elevento15', 'sixteento20','greaterthan20'));
    }
}
