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
        $this->middleware(['auth', 'verified', 'CheckPassword']);
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

    public function dailytickets()
    {
        $Store = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Store')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $Plant = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Plant')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $Office = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Office')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $date = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $dates = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"),)
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
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
            ->where('TaskNumber', 'LIKE', 'GBI%')
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
        return view('opentickets', compact('filtered', 'lessthan5', 'sixto10', 'elevento15', 'sixteento20','greaterthan20'));
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
