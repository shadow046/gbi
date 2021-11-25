<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\Task;
use App\Models\Data;
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

    public function email()
    {
        $datas = Data::select('Code', 'Email')
            ->where('Email', "")
            ->where('Code', 'regexp', '^[0-9]')
            ->get();
        foreach ($datas as $data) {
            Data::where('Code', $data->Code)->update(['Email'=>'store.'.$data->Code.'@goldilocks.com']);
        }
        return "done";
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
            ->join('Data', 'Code', 'StoreCode')
            ->whereNotIn('IncidentStatus', ['Closed','Resolved'])
            ->whereIn('Status',['Open', 'Re Open', 'Closed'])
            ->count();
        $closed = Ticket::query()
            ->join('Data', 'Code', 'StoreCode')
            ->where('TaskStatus','Submitted')
            ->whereIN('IncidentStatus', ['Closed','Resolved'])
            ->where('Status', 'Closed')
            ->count();
        $cancelled = Ticket::query()
            ->join('Data', 'Code', 'StoreCode')
            ->where('Status', 'Cancelled')
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
        $TopAgent = Ticket::select('CreatedBy', DB::raw('Count(CreatedBy) as Total'))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->groupBy('CreatedBy')
            ->get();
        $topa = collect([]);
        foreach ($TopAgent as $Agent) {
            if ($Agent->CreatedBy != Null) {
                // return $issue->Total;
                $topa->offsetSet(str_replace("Gbi","",$Agent->CreatedBy),$Agent->Total);
            }
        }
        $filtereda = $topa->sortDesc();

        $incidents = Ticket::select('CallType', DB::raw('Count(CallType) as Total'))
            ->where('TaskStatus', '!=', 'Submitted')
            ->where('IncidentStatus', '!=', 'Resolved')
            ->groupBy('CallType')
            ->get();
        $inc = collect([]);
        foreach ($incidents as $incident) {
            if ($incident->CallType != Null) {
                // return $issue->Total;
                $inc->offsetSet(str_replace("Gbi","",$incident->CallType),$incident->Total);
            }
        }
        $filteredi = $inc->sortDesc();
        $fivedaysStore = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        
        $fivedaysPlant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $fivedaysOffice = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $sixto10Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Office')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $sixto10Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Store')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $sixto10Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Plant')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $elevento15Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $elevento15Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $elevento15Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $sixteento20Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Office')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $sixteento20Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $sixteento20Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $greaterthan20Plant = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Plant')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        $greaterthan20Store = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Store')
            ->whereNotIN('IncidentStatus', ['Resolved','Closed'])
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        $greaterthan20Office = Ticket::query()->select('TaskNumber')
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Office')
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
                'cancelled',
                'filtered',
                'filtereda',
                'filteredi',
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
        return view('opentickets');
    }
    public function monthCompare($a, $b)
    {
        $months = array('JAN' => 1, 'FEB' =>2,'MAR' => 3,'APR' => 4,'MAY' => 5,'JUN' => 6,'JULY' => 7,'AUG' => 8, 'SEP' => 9, 'OCT' => 10, 'NOV' => 11, 'DEC' => 12);
        if($a[0] == $b[0])
        {
            return 0;
        }
        return ($months[$a[0]] > $months[$b[0]]) ? 1 : -1;
    
    }
    
    public function dash()
    {
        
        // return $TopIssues;

        // usort($TopIssues, function($a, $b) {
        //     // if ($a['ProblemCategory'] != $b['ProblemCategory']) {
        //     //     return $b['ProblemCategory'] <=> $a['ProblemCategory'];
        //     // }
        //     return $b['Total'] <=> $a['Total'];
        // });
        $TopIssuesSoftware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from Ticket)*100),2) as percentage'))
            ->join('Data','Code','StoreCode')
            ->whereNotNull('SubCategory')
            ->where('ProblemCategory', 'Software/ Application')
            ->groupBy('SubCategory')
            ->get();
            
        $TopSoftware = collect([]);
        foreach ($TopIssuesSoftware as $issue) {
            if ($issue->SubCategory != Null) {
                $TopSoftware->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $TopSoftware = $TopSoftware->sortDesc();
        $TopSoft = collect([]);
        $SoftCount = 1;
        $TotalSoft = 0;
        foreach ($TopSoftware as $key => $value) {
            $TotalSoft = $TotalSoft+$value;
            if ($SoftCount < 4 && $key != "Others") {
                $SoftCount++;
                $TopSoft[$key] = $value;
            }else if ($SoftCount >= 4) {
                if ($key != "Others") {
                    if (isset($TopSoft['Others'])) {
                        $TopSoft['Others'] = $TopSoft['Others']+$value;
                    }else{
                        $TopSoft['Others'] = $value;
                    }
                }else{
                    $TopSoft[$key] = $value;
                }
            }else if ($key == "Others") {
                if (isset($TopSoft['Others'])) {
                    $TopSoft['Others'] = $TopSoft['Others']+$value;
                }else{
                    $TopSoft['Others'] = $value;
                }
            }
        }
        $TopSoft['Total'] = $TotalSoft;

        $TopIssuesHardware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from Ticket)*100),2) as percentage'))
            ->join('Data','Code','StoreCode')
            ->whereNotNull('SubCategory')
            ->where('ProblemCategory', 'Hardware')
            ->groupBy('SubCategory')
            ->get();
            
        $TopHardware = collect([]);
        foreach ($TopIssuesHardware as $issue) {
            if ($issue->SubCategory != Null) {
                $TopHardware->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $TopHardware = $TopHardware->sortDesc();
        $TopHard = collect([]);
        $HardCount = 1;
        $TotalHard = 0;
        foreach ($TopHardware as $key => $value) {
            $TotalHard = $TotalHard+$value;
            if ($HardCount < 4 && $key != "Others") {
                $HardCount++;
                $TopHard[$key] = $value;
            }else if ($HardCount >= 4) {
                if ($key != "Others") {
                    if (isset($TopHard['Others'])) {
                        $TopHard['Others'] = $TopHard['Others']+$value;
                    }else{
                        $TopHard['Others'] = $value;
                    }
                }else{
                    $TopHard[$key] = $value;
                }
            }else if ($key == "Others") {
                if (isset($TopHard['Others'])) {
                    $TopHard['Others'] = $TopHard['Others']+$value;
                }else{
                    $TopHard['Others'] = $value;
                }
            }
        }
        $TopHard['Total'] = $TotalHard;

        $TopIssuesInfrastructure = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from Ticket)*100),2) as percentage'))
            ->join('Data','Code','StoreCode')
            ->whereNotNull('SubCategory')
            ->where('ProblemCategory', 'Infrastructure')
            ->groupBy('SubCategory')
            ->get();
            
        $TopInfrastructure = collect([]);
        foreach ($TopIssuesInfrastructure as $issue) {
            if ($issue->SubCategory != Null) {
                $TopInfrastructure->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $TopInfrastructure = $TopInfrastructure->sortDesc();
        $TopInfra = collect([]);
        $InfraCount = 1;
        $TotalInfra = 0;
        foreach ($TopInfrastructure as $key => $value) {
            $TotalInfra = $TotalInfra+$value;
            if ($InfraCount < 4 && $key != "Others") {
                $InfraCount++;
                $TopInfra[$key] = $value;
            }else if ($InfraCount >= 4) {
                if ($key != "Others") {
                    if (isset($TopInfra['Others'])) {
                        $TopInfra['Others'] = $TopInfra['Others']+$value;
                    }else{
                        $TopInfra['Others'] = $value;
                    }
                }else{
                    $TopInfra[$key] = $value;
                }
            }else if ($key == "Others") {
                if (isset($TopInfra['Others'])) {
                    $TopInfra['Others'] = $TopInfra['Others']+$value;
                }else{
                    $TopInfra['Others'] = $value;
                }
            }
        }
        $TopInfra['Total'] = $TotalInfra;

        $TopIssuesOthers = Ticket::select('ProblemCategory', DB::raw('Count(ProblemCategory) as Total'))
            ->join('Data','Code','StoreCode')
            ->where('ProblemCategory', 'Others')
            ->groupBy('ProblemCategory')
            ->get();
            
        $TopOthers = collect([]);
        foreach ($TopIssuesOthers as $issue) {
            if ($issue->ProblemCategory != Null) {
                $TopOthers->offsetSet($issue->ProblemCategory,$issue->Total);
            }
        }
        $TopOthers = $TopOthers->sortDesc();
        // return $TopOthers;
        $softwarekey = [];
        foreach ($TopSoft as $key => $value) {
            if (count($softwarekey) < 3) {
                if ($key != "Others") {
                    array_push($softwarekey, $key);
                }
            }else if (count($softwarekey) < 4) {
                array_push($softwarekey, 'Others');
                array_push($softwarekey, 'Total');
            }
        }
        $softwareval = [];
        foreach ($TopSoft as $key => $value) {
            if (count($softwareval) < 3) {
                if ($key != "Others") {
                    array_push($softwareval, $value);
                }
            }else if (count($softwareval) < 4) {
                array_push($softwareval, $TopSoft['Others']);
                array_push($softwareval, $TopSoft['Total']);
            }
        }
        $Hardwarekey = [];
        foreach ($TopHard as $key => $value) {
            if (count($Hardwarekey) < 3) {
                if ($key != "Others") {
                    array_push($Hardwarekey, $key);
                }
            }else if (count($Hardwarekey) < 4) {
                array_push($Hardwarekey, 'Others');
                array_push($Hardwarekey, 'Total');
            }
        }
        $Hardwareval = [];
        foreach ($TopHard as $key => $value) {
            if (count($Hardwareval) < 3) {
                if ($key != "Others") {
                    array_push($Hardwareval, $value);
                }
            }else if (count($Hardwareval) < 4) {
                array_push($Hardwareval, $TopHard['Others']);
                array_push($Hardwareval, $TopHard['Total']);
            }
        }
        $Infrawarekey = [];
        foreach ($TopInfra as $key => $value) {
            if (count($Infrawarekey) < 3) {
                if ($key != "Others") {
                    array_push($Infrawarekey, $key);
                }
            }else if (count($Infrawarekey) < 4) {
                array_push($Infrawarekey, 'Others');
                array_push($Infrawarekey, 'Total');
            }
        }
        $Infrawareval = [];
        foreach ($TopInfra as $key => $value) {
            if (count($Infrawareval) < 3) {
                if ($key != "Others") {
                    array_push($Infrawareval, $value);
                }
            }else if (count($Infrawareval) < 4) {
                array_push($Infrawareval, $TopInfra['Others']);
                array_push($Infrawareval, $TopInfra['Total']);
            }
        }

        
        $ResTickCount = Ticket::query()
            ->select(
                DB::raw("monthname(DateCreated) as Month"),
                DB::raw("max(Year(DateCreated)) as Year"),
                DB::raw('max(DateCreated) as DateCreated'),
                // DB::raw('DATEDIFF(DateandTimeFinished,DateCreated) = 1 as oneday')
                // DB::raw('DATEDIFF(DateandTimeFinished,DateCreated) = 2 as twoday')
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) <= 1 and TimeFinished is Null THEN 1 ELSE 0 END) as onedt'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) <= 1 and TimeFinished is not Null THEN 1 ELSE 0 END) as onet'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 2 and TimeFinished is Null THEN 1 ELSE 0 END) as twodt'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 2 and TimeFinished is not Null THEN 1 ELSE 0 END) as twot'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 3 and TimeFinished is Null THEN 1 ELSE 0 END) as threedt'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 3 and TimeFinished is not Null THEN 1 ELSE 0 END) as threet'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 4 and TimeFinished is Null THEN 1 ELSE 0 END) as fourdt'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 4 and TimeFinished is not Null THEN 1 ELSE 0 END) as fourt'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 5 and TimeFinished is Null THEN 1 ELSE 0 END) as fivedt'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 5 and TimeFinished is not Null THEN 1 ELSE 0 END) as fivet'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) > 5 and TimeFinished is Null THEN 1 ELSE 0 END) as morethanfivedt'
                ),
                DB::raw(
                    'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) > 5 and TimeFinished is not Null THEN 1 ELSE 0 END) as morethanfivet'
                ),
                DB::raw(
                    'count(*) as TotalTicket'
                )
            )
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Store')
            ->where('TaskStatus', 'Submitted')
            // ->whereNull('TimeFinished')
            // ->whereRaw('DATEDIFF(DateandTimeFinished,DateCreated) = 1')
            ->orderBy('DateCreated', 'asc')
            ->groupBy('Month')
            ->get();

            $PriorTickCount = Ticket::query()
            ->select(
                DB::raw("monthname(DateCreated) as Month"),
                DB::raw("max(Year(DateCreated)) as Year"),
                DB::raw('max(DateCreated) as DateCreated'),
                // DB::raw('DATEDIFF(DateandTimeFinished,DateCreated) = 1 as oneday')
                // DB::raw('DATEDIFF(DateandTimeFinished,DateCreated) = 2 as twoday')
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P1\' and SBU = \'Office\' THEN 1 ELSE 0 END) as OfficeP1'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P2\' and SBU = \'Offce\' THEN 1 ELSE 0 END) as OfficeP2'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P3\' and SBU = \'Office\' THEN 1 ELSE 0 END) as OfficeP3'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P1\' and SBU = \'Store\' THEN 1 ELSE 0 END) as StoreP1'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P2\' and SBU = \'Store\' THEN 1 ELSE 0 END) as StoreP2'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P3\' and SBU = \'Store\' THEN 1 ELSE 0 END) as StoreP3'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P1\' and SBU = \'Plant\' THEN 1 ELSE 0 END) as PlantP1'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P2\' and SBU = \'Plant\' THEN 1 ELSE 0 END) as PlantP2'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P3\' and SBU = \'Plant\' THEN 1 ELSE 0 END) as PlantP3'
                )
            )
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Store')
            ->where('TaskStatus', 'Submitted')
            ->orderBy('DateCreated', 'asc')
            ->groupBy('Month')
            ->get();

        $ResolverTickCount = Ticket::select(
            DB::raw("monthname(DateCreated) as Month"),
            DB::raw("max(Year(DateCreated)) as Year"),
            DB::raw('max(DateCreated) as DateCreated'),
            DB::raw(
                'SUM(CASE WHEN TicketType = \'["L1"]\' THEN 1 ELSE 0 END) as L1'
            ),
            DB::raw(
                'SUM(CASE WHEN TicketType = \'["L2"]\' THEN 1 ELSE 0 END) as L2'
            ),
            DB::raw(
                'SUM(CASE WHEN TicketType LIKE \'%["Escalated L2"]\' THEN 1 ELSE 0 END) as Escalated'
            ),
            DB::raw(
                'SUM(CASE WHEN TicketType is not null THEN 1 ELSE 0 END) as ResolverTotal'
            )
        )
        ->join('Data','Code','StoreCode')
        ->where('TaskStatus', 'Submitted')
        ->orderBy('DateCreated', 'asc')
        ->groupBy('Month')
        ->get();
    // return $ResolverTickCount;

    $ResolutionTickCount = Ticket::select(
        DB::raw("monthname(DateCreated) as Month"),
        DB::raw("max(Year(DateCreated)) as Year"),
        DB::raw('max(DateCreated) as DateCreated'),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution Is Not Null and SBU = \'Office\'  THEN 1 ELSE 0 END) as OfficeTotal'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution Is Not Null and SBU = \'Plant\'  THEN 1 ELSE 0 END) as PlantTotal'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution Is Not Null and SBU = \'Store\'  THEN 1 ELSE 0 END) as StoreTotal'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution = \'Onsite\' and SBU = \'Office\'  THEN 1 ELSE 0 END) as OnsiteOffice'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution = \'Phone Assistance\' and SBU = \'Office\'  THEN 1 ELSE 0 END) as PhoneAssistanceOffice'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution = \'Remote\' and SBU = \'Office\'  THEN 1 ELSE 0 END) as RemoteOffice'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution = \'Onsite\' and SBU = \'Plant\'  THEN 1 ELSE 0 END) as OnsitePlant'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution = \'Phone Assistance\' and SBU = \'Plant\'  THEN 1 ELSE 0 END) as PhoneAssistancePlant'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution = \'Remote\' and SBU = \'Plant\'  THEN 1 ELSE 0 END) as RemotePlant'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution = \'Onsite\' and SBU = \'Store\'  THEN 1 ELSE 0 END) as OnsiteStore'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution = \'Phone Assistance\' and SBU = \'Store\'  THEN 1 ELSE 0 END) as PhoneAssistanceStore'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution = \'Remote\' and SBU = \'Store\'  THEN 1 ELSE 0 END) as RemoteStore'
        ),
        DB::raw(
            'SUM(CASE WHEN TypeofResolution Is Not Null and SBU Is Not Null THEN 1 ELSE 0 END) as ResolutionTotal'
        )
    )
    ->join('Data','Code','StoreCode')
    ->where('TaskStatus', 'Submitted')
    ->orderBy('DateCreated', 'asc')
    ->groupBy('Month')
    ->get();




    $StoreTopIssues = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Store')
            ->groupBy('SubCategory')
            ->get();
        $Stop = collect([]);
        foreach ($StoreTopIssues as $issue) {
            if ($issue->SubCategory != Null) {
                $Stop->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $StoreTop = $Stop->sortDesc();
        $OfficeTopIssues = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Office')
            ->groupBy('SubCategory')
            ->get();
        $Otop = collect([]);
        foreach ($OfficeTopIssues as $issue) {
            if ($issue->SubCategory != Null) {
                $Otop->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $OfficeTop = $Otop->sortDesc();
        $PlantTopIssues = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Plant')
            ->groupBy('SubCategory')
            ->get();
        $Ptop = collect([]);
        foreach ($PlantTopIssues as $issue) {
            if ($issue->SubCategory != Null) {
                $Ptop->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $PlantTop = $Ptop->sortDesc();
    // return $ResolutionTickCount;
        return view('dash', compact(
            'StoreTop',
            'OfficeTop',
            'PlantTop',
            'TopSoft',
            'TopHard',
            'TopInfra',
            'softwarekey',
            'softwareval',
            'Hardwareval',
            'Hardwarekey',
            'Infrawareval',
            'Infrawarekey',
            'TopOthers',
            'ResTickCount',
            'PriorTickCount',
            'ResolverTickCount',
            'ResolutionTickCount'
        ));
    }

    public function priorstatus(Request $request, $datefrom, $dateto)
    {   
        if ($datefrom == 'default') {
            $PriorTickCount = Ticket::query()
            ->select(
                DB::raw("monthname(DateCreated) as Month"),
                DB::raw("max(Year(DateCreated)) as Year"),
                DB::raw('max(DateCreated) as DateCreated'),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P1\' and SBU = \'Office\' THEN 1 ELSE 0 END) as OfficeP1'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P2\' and SBU = \'Offce\' THEN 1 ELSE 0 END) as OfficeP2'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P3\' and SBU = \'Office\' THEN 1 ELSE 0 END) as OfficeP3'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P1\' and SBU = \'Store\' THEN 1 ELSE 0 END) as StoreP1'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P2\' and SBU = \'Store\' THEN 1 ELSE 0 END) as StoreP2'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P3\' and SBU = \'Store\' THEN 1 ELSE 0 END) as StoreP3'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P1\' and SBU = \'Plant\' THEN 1 ELSE 0 END) as PlantP1'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P2\' and SBU = \'Plant\' THEN 1 ELSE 0 END) as PlantP2'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P3\' and SBU = \'Plant\' THEN 1 ELSE 0 END) as PlantP3'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority is not null and SBU is not null THEN 1 ELSE 0 END) as GrandTotal'
                )
            )
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Store')
            ->where('TaskStatus', 'Submitted')
            ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
            ->whereMonth('DateCreated', '<', Carbon::now())
            ->orderBy('DateCreated', 'asc')
            ->groupBy('Month')
            ->get();
        }else{
            $PriorTickCount = Ticket::query()
            ->select(
                DB::raw("monthname(DateCreated) as Month"),
                DB::raw("max(Year(DateCreated)) as Year"),
                DB::raw('max(DateCreated) as DateCreated'),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P1\' and SBU = \'Office\' THEN 1 ELSE 0 END) as OfficeP1'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P2\' and SBU = \'Offce\' THEN 1 ELSE 0 END) as OfficeP2'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P3\' and SBU = \'Office\' THEN 1 ELSE 0 END) as OfficeP3'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P1\' and SBU = \'Store\' THEN 1 ELSE 0 END) as StoreP1'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P2\' and SBU = \'Store\' THEN 1 ELSE 0 END) as StoreP2'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P3\' and SBU = \'Store\' THEN 1 ELSE 0 END) as StoreP3'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P1\' and SBU = \'Plant\' THEN 1 ELSE 0 END) as PlantP1'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P2\' and SBU = \'Plant\' THEN 1 ELSE 0 END) as PlantP2'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority = \'P3\' and SBU = \'Plant\' THEN 1 ELSE 0 END) as PlantP3'
                ),
                DB::raw(
                    'SUM(CASE WHEN Priority is not null and SBU is not null THEN 1 ELSE 0 END) as GrandTotal'
                )
            )
            ->join('Data','Code','StoreCode')
            ->where('Data.SBU','Store')
            ->where('TaskStatus', 'Submitted')
            ->whereDate('DateCreated', '>=', Carbon::parse($datefrom))
            ->whereDate('DateCreated', '<=', Carbon::parse($dateto))
            ->orderBy('DateCreated', 'asc')
            ->groupBy('Month')
            ->get();
        }
        return view('dashpriorstatus', compact(
            'PriorTickCount'
        ));
    }

    public function resolverstatus(Request $request, $datefrom, $dateto)
    {
        if ($datefrom == 'default') {
            $ResolverTickCount = Ticket::select(
                DB::raw("monthname(DateCreated) as Month"),
                DB::raw("max(Year(DateCreated)) as Year"),
                DB::raw('max(DateCreated) as DateCreated'),
                DB::raw(
                    'SUM(CASE WHEN TicketType = \'["L1"]\' THEN 1 ELSE 0 END) as L1'
                ),
                DB::raw(
                    'SUM(CASE WHEN TicketType = \'["L2"]\' THEN 1 ELSE 0 END) as L2'
                ),
                DB::raw(
                    'SUM(CASE WHEN TicketType LIKE \'%["Escalated L2"]\' THEN 1 ELSE 0 END) as Escalated'
                ),
                DB::raw(
                    'SUM(CASE WHEN TicketType is not null THEN 1 ELSE 0 END) as ResolverTotal'
                )
            )
            ->join('Data','Code','StoreCode')
            ->where('TaskStatus', 'Submitted')
            ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
            ->whereMonth('DateCreated', '<', Carbon::now())
            ->orderBy('DateCreated', 'asc')
            ->groupBy('Month')
            ->get();
        }else{
            $ResolverTickCount = Ticket::select(
                DB::raw("monthname(DateCreated) as Month"),
                DB::raw("max(Year(DateCreated)) as Year"),
                DB::raw('max(DateCreated) as DateCreated'),
                DB::raw(
                    'SUM(CASE WHEN TicketType = \'["L1"]\' THEN 1 ELSE 0 END) as L1'
                ),
                DB::raw(
                    'SUM(CASE WHEN TicketType = \'["L2"]\' THEN 1 ELSE 0 END) as L2'
                ),
                DB::raw(
                    'SUM(CASE WHEN TicketType LIKE \'%["Escalated L2"]\' THEN 1 ELSE 0 END) as Escalated'
                ),
                DB::raw(
                    'SUM(CASE WHEN TicketType is not null THEN 1 ELSE 0 END) as ResolverTotal'
                )
            )
            ->join('Data','Code','StoreCode')
            ->where('TaskStatus', 'Submitted')
            ->whereDate('DateCreated', '>=', Carbon::parse($datefrom))
            ->whereDate('DateCreated', '<=', Carbon::parse($dateto))
            ->orderBy('DateCreated', 'asc')
            ->groupBy('Month')
            ->get();
        }
        return view('dashresolvestatus', compact(
            'ResolverTickCount'
        ));
    }
    
    public function dependencies(Request $request, $datefrom, $dateto)
    {
        if ($datefrom == 'default') {
            $ResolutionTickCount = Ticket::select(
                DB::raw("monthname(DateCreated) as Month"),
                DB::raw("max(Year(DateCreated)) as Year"),
                DB::raw('max(DateCreated) as DateCreated'),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution Is Not Null and SBU = \'Office\'  THEN 1 ELSE 0 END) as OfficeTotal'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution Is Not Null and SBU = \'Plant\'  THEN 1 ELSE 0 END) as PlantTotal'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution Is Not Null and SBU = \'Store\'  THEN 1 ELSE 0 END) as StoreTotal'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Onsite\' and SBU = \'Office\'  THEN 1 ELSE 0 END) as OnsiteOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Phone Assistance\' and SBU = \'Office\'  THEN 1 ELSE 0 END) as PhoneAssistanceOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Remote\' and SBU = \'Office\'  THEN 1 ELSE 0 END) as RemoteOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Onsite\' and SBU = \'Plant\'  THEN 1 ELSE 0 END) as OnsitePlant'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Phone Assistance\' and SBU = \'Plant\'  THEN 1 ELSE 0 END) as PhoneAssistancePlant'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Remote\' and SBU = \'Plant\'  THEN 1 ELSE 0 END) as RemotePlant'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Onsite\' and SBU = \'Store\'  THEN 1 ELSE 0 END) as OnsiteStore'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Phone Assistance\' and SBU = \'Store\'  THEN 1 ELSE 0 END) as PhoneAssistanceStore'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Remote\' and SBU = \'Store\'  THEN 1 ELSE 0 END) as RemoteStore'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution Is Not Null and SBU Is Not Null THEN 1 ELSE 0 END) as ResolutionTotal'
                )
            )
            ->join('Data','Code','StoreCode')
            ->where('TaskStatus', 'Submitted')
            ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
            ->whereMonth('DateCreated', '<', Carbon::now())
            ->orderBy('DateCreated', 'asc')
            ->groupBy('Month')
            ->get();
        }else{
            $ResolutionTickCount = Ticket::select(
                DB::raw("monthname(DateCreated) as Month"),
                DB::raw("max(Year(DateCreated)) as Year"),
                DB::raw('max(DateCreated) as DateCreated'),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution Is Not Null and SBU = \'Office\'  THEN 1 ELSE 0 END) as OfficeTotal'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution Is Not Null and SBU = \'Plant\'  THEN 1 ELSE 0 END) as PlantTotal'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution Is Not Null and SBU = \'Store\'  THEN 1 ELSE 0 END) as StoreTotal'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Onsite\' and SBU = \'Office\'  THEN 1 ELSE 0 END) as OnsiteOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Phone Assistance\' and SBU = \'Office\'  THEN 1 ELSE 0 END) as PhoneAssistanceOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Remote\' and SBU = \'Office\'  THEN 1 ELSE 0 END) as RemoteOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Onsite\' and SBU = \'Plant\'  THEN 1 ELSE 0 END) as OnsitePlant'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Phone Assistance\' and SBU = \'Plant\'  THEN 1 ELSE 0 END) as PhoneAssistancePlant'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Remote\' and SBU = \'Plant\'  THEN 1 ELSE 0 END) as RemotePlant'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Onsite\' and SBU = \'Store\'  THEN 1 ELSE 0 END) as OnsiteStore'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Phone Assistance\' and SBU = \'Store\'  THEN 1 ELSE 0 END) as PhoneAssistanceStore'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution = \'Remote\' and SBU = \'Store\'  THEN 1 ELSE 0 END) as RemoteStore'
                ),
                DB::raw(
                    'SUM(CASE WHEN TypeofResolution Is Not Null and SBU Is Not Null THEN 1 ELSE 0 END) as ResolutionTotal'
                )
            )
            ->join('Data','Code','StoreCode')
            ->where('TaskStatus', 'Submitted')
            ->whereDate('DateCreated', '>=', Carbon::parse($datefrom))
            ->whereDate('DateCreated', '<=', Carbon::parse($dateto))
            ->orderBy('DateCreated', 'asc')
            ->groupBy('Month')
            ->get();
        }
        return view('dashdependencies', compact(
            'ResolutionTickCount'
        ));
    }
    
    public function statsum()
    {
        return view('dashstatsum', compact(
            'ResTickCount',
            'PriorTickCount',
            'ResolverTickCount',
            'ResolutionTickCount'
        ));
    }

    public function resolvetick(Request $request, $datefrom, $dateto)
    {
        if ($datefrom == 'default') {
            $ResTickCount = Ticket::query()
                ->select(
                    DB::raw("monthname(DateCreated) as Month"),
                    DB::raw("max(Year(DateCreated)) as Year"),
                    DB::raw('max(DateCreated) as DateCreated'),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) <= 1 and TimeFinished is Null THEN 1 ELSE 0 END) as onedt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) <= 1 and TimeFinished is not Null THEN 1 ELSE 0 END) as onet'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 2 and TimeFinished is Null THEN 1 ELSE 0 END) as twodt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 2 and TimeFinished is not Null THEN 1 ELSE 0 END) as twot'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 3 and TimeFinished is Null THEN 1 ELSE 0 END) as threedt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 3 and TimeFinished is not Null THEN 1 ELSE 0 END) as threet'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 4 and TimeFinished is Null THEN 1 ELSE 0 END) as fourdt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 4 and TimeFinished is not Null THEN 1 ELSE 0 END) as fourt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 5 and TimeFinished is Null THEN 1 ELSE 0 END) as fivedt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 5 and TimeFinished is not Null THEN 1 ELSE 0 END) as fivet'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) > 5 and TimeFinished is Null THEN 1 ELSE 0 END) as morethanfivedt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) > 5 and TimeFinished is not Null THEN 1 ELSE 0 END) as morethanfivet'
                    ),
                    DB::raw(
                        'count(*) as TotalTicket'
                    )
                )
                ->join('Data','Code','StoreCode')
                ->where('Data.SBU','Store')
                ->where('TaskStatus', 'Submitted')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
                ->orderBy('DateCreated', 'asc')
                ->groupBy('Month')
                ->get();
        }else{
            $ResTickCount = Ticket::query()
                ->select(
                    DB::raw("monthname(DateCreated) as Month"),
                    DB::raw("max(Year(DateCreated)) as Year"),
                    DB::raw('max(DateCreated) as DateCreated'),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) <= 1 and TimeFinished is Null THEN 1 ELSE 0 END) as onedt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) <= 1 and TimeFinished is not Null THEN 1 ELSE 0 END) as onet'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 2 and TimeFinished is Null THEN 1 ELSE 0 END) as twodt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 2 and TimeFinished is not Null THEN 1 ELSE 0 END) as twot'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 3 and TimeFinished is Null THEN 1 ELSE 0 END) as threedt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 3 and TimeFinished is not Null THEN 1 ELSE 0 END) as threet'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 4 and TimeFinished is Null THEN 1 ELSE 0 END) as fourdt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 4 and TimeFinished is not Null THEN 1 ELSE 0 END) as fourt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) = 5 and TimeFinished is Null THEN 1 ELSE 0 END) as fivedt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) = 5 and TimeFinished is not Null THEN 1 ELSE 0 END) as fivet'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(DateandTimeFinished,DateCreated) > 5 and TimeFinished is Null THEN 1 ELSE 0 END) as morethanfivedt'
                    ),
                    DB::raw(
                        'SUM(CASE WHEN DATEDIFF(TimeFinished,DateCreated) > 5 and TimeFinished is not Null THEN 1 ELSE 0 END) as morethanfivet'
                    ),
                    DB::raw(
                        'count(*) as TotalTicket'
                    )
                )
                ->join('Data','Code','StoreCode')
                ->where('Data.SBU','Store')
                ->where('TaskStatus', 'Submitted')
                ->whereDate('DateCreated', '>=', Carbon::parse($datefrom))
                ->whereDate('DateCreated', '<=', Carbon::parse($dateto))
                ->orderBy('DateCreated', 'asc')
                ->groupBy('Month')
                ->get();
        }

        return view('dashresolvetick', compact(
            'ResTickCount'
        ));
    }

    public function pcategory(Request $request, $datefrom, $dateto)
    {   

        if ($datefrom != "default") {
            if (strtotime($datefrom) == false || strtotime($dateto) == false) {
                return view('errors.404');
            }
        }else{
            if ($dateto != 1) {
                return view('errors.404');
            }
        }

        if ($datefrom != "default") {
            $TopIssuesSoftware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from Ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Software/ Application')
                ->whereMonth('DateCreated', '>=', Carbon::parse($datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($dateto))
                ->groupBy('SubCategory')
                ->get();
                
            $TopSoftware = collect([]);
            foreach ($TopIssuesSoftware as $issue) {
                if ($issue->SubCategory != Null) {
                    $TopSoftware->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $TopSoftware = $TopSoftware->sortDesc();
            $TopSoft = collect([]);
            $SoftCount = 1;
            $TotalSoft = 0;
            foreach ($TopSoftware as $key => $value) {
                $TotalSoft = $TotalSoft+$value;
                if ($SoftCount < 4 && $key != "Others") {
                    $SoftCount++;
                    $TopSoft[$key] = $value;
                }else if ($SoftCount >= 4) {
                    if ($key != "Others") {
                        if (isset($TopSoft['Others'])) {
                            $TopSoft['Others'] = $TopSoft['Others']+$value;
                        }else{
                            $TopSoft['Others'] = $value;
                        }
                    }else{
                        $TopSoft[$key] = $value;
                    }
                }else if ($key == "Others") {
                    if (isset($TopSoft['Others'])) {
                        $TopSoft['Others'] = $TopSoft['Others']+$value;
                    }else{
                        $TopSoft['Others'] = $value;
                    }
                }
            }
            $TopSoft['Total'] = $TotalSoft;

            $TopIssuesHardware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from Ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Hardware')
                ->whereMonth('DateCreated', '>=', Carbon::parse($datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($dateto))
                ->groupBy('SubCategory')
                ->get();
                
            $TopHardware = collect([]);
            foreach ($TopIssuesHardware as $issue) {
                if ($issue->SubCategory != Null) {
                    $TopHardware->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $TopHardware = $TopHardware->sortDesc();
            $TopHard = collect([]);
            $HardCount = 1;
            $TotalHard = 0;
            foreach ($TopHardware as $key => $value) {
                $TotalHard = $TotalHard+$value;
                if ($HardCount < 4 && $key != "Others") {
                    $HardCount++;
                    $TopHard[$key] = $value;
                }else if ($HardCount >= 4) {
                    if ($key != "Others") {
                        if (isset($TopHard['Others'])) {
                            $TopHard['Others'] = $TopHard['Others']+$value;
                        }else{
                            $TopHard['Others'] = $value;
                        }
                    }else{
                        $TopHard[$key] = $value;
                    }
                }else if ($key == "Others") {
                    if (isset($TopHard['Others'])) {
                        $TopHard['Others'] = $TopHard['Others']+$value;
                    }else{
                        $TopHard['Others'] = $value;
                    }
                }
            }
            $TopHard['Total'] = $TotalHard;

            $TopIssuesInfrastructure = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from Ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Infrastructure')
                ->whereMonth('DateCreated', '>=', Carbon::parse($datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($dateto))
                ->groupBy('SubCategory')
                ->get();
                
            $TopInfrastructure = collect([]);
            foreach ($TopIssuesInfrastructure as $issue) {
                if ($issue->SubCategory != Null) {
                    $TopInfrastructure->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $TopInfrastructure = $TopInfrastructure->sortDesc();
            $TopInfra = collect([]);
            $InfraCount = 1;
            $TotalInfra = 0;
            foreach ($TopInfrastructure as $key => $value) {
                $TotalInfra = $TotalInfra+$value;
                if ($InfraCount < 4 && $key != "Others") {
                    $InfraCount++;
                    $TopInfra[$key] = $value;
                }else if ($InfraCount >= 4) {
                    if ($key != "Others") {
                        if (isset($TopInfra['Others'])) {
                            $TopInfra['Others'] = $TopInfra['Others']+$value;
                        }else{
                            $TopInfra['Others'] = $value;
                        }
                    }else{
                        $TopInfra[$key] = $value;
                    }
                }else if ($key == "Others") {
                    if (isset($TopInfra['Others'])) {
                        $TopInfra['Others'] = $TopInfra['Others']+$value;
                    }else{
                        $TopInfra['Others'] = $value;
                    }
                }
            }
            $TopInfra['Total'] = $TotalInfra;

            $TopIssuesOthers = Ticket::select('ProblemCategory', DB::raw('Count(ProblemCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('ProblemCategory', 'Others')
                ->whereMonth('DateCreated', '>=', Carbon::parse($datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($dateto))
                ->groupBy('ProblemCategory')
                ->get();
                
            $TopOthers = collect([]);
            foreach ($TopIssuesOthers as $issue) {
                if ($issue->ProblemCategory != Null) {
                    $TopOthers->offsetSet($issue->ProblemCategory,$issue->Total);
                }
            }
            $TopOthers = $TopOthers->sortDesc();
            // return $TopOthers;
            $softwarekey = [];
            foreach ($TopSoft as $key => $value) {
                if (count($softwarekey) < 3) {
                    if ($key != "Others") {
                        array_push($softwarekey, $key);
                    }
                }else if (count($softwarekey) < 4) {
                    array_push($softwarekey, 'Others');
                    array_push($softwarekey, 'Total');
                }
            }
            $softwareval = [];
            foreach ($TopSoft as $key => $value) {
                if (count($softwareval) < 3) {
                    if ($key != "Others") {
                        array_push($softwareval, $value);
                    }
                }else if (count($softwareval) < 4) {
                    array_push($softwareval, $TopSoft['Others']);
                    array_push($softwareval, $TopSoft['Total']);
                }
            }
            $Hardwarekey = [];
            foreach ($TopHard as $key => $value) {
                if (count($Hardwarekey) < 3) {
                    if ($key != "Others") {
                        array_push($Hardwarekey, $key);
                    }
                }else if (count($Hardwarekey) < 4) {
                    array_push($Hardwarekey, 'Others');
                    array_push($Hardwarekey, 'Total');
                }
            }
            $Hardwareval = [];
            foreach ($TopHard as $key => $value) {
                if (count($Hardwareval) < 3) {
                    if ($key != "Others") {
                        array_push($Hardwareval, $value);
                    }
                }else if (count($Hardwareval) < 4) {
                    array_push($Hardwareval, $TopHard['Others']);
                    array_push($Hardwareval, $TopHard['Total']);
                }
            }
            $Infrawarekey = [];
            foreach ($TopInfra as $key => $value) {
                if (count($Infrawarekey) < 3) {
                    if ($key != "Others") {
                        array_push($Infrawarekey, $key);
                    }
                }else if (count($Infrawarekey) < 4) {
                    array_push($Infrawarekey, 'Others');
                    array_push($Infrawarekey, 'Total');
                }
            }
            $Infrawareval = [];
            foreach ($TopInfra as $key => $value) {
                if (count($Infrawareval) < 3) {
                    if ($key != "Others") {
                        array_push($Infrawareval, $value);
                    }
                }else if (count($Infrawareval) < 4) {
                    array_push($Infrawareval, $TopInfra['Others']);
                    array_push($Infrawareval, $TopInfra['Total']);
                }
            }
        }else{
            $TopIssuesSoftware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from Ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Software/ Application')
                ->whereDate('DateCreated', '>=', Carbon::parse($request->datefrom))
                ->whereDate('DateCreated', '<=', Carbon::parse($request->dateto))
                ->groupBy('SubCategory')
                ->get();
                
            $TopSoftware = collect([]);
            foreach ($TopIssuesSoftware as $issue) {
                if ($issue->SubCategory != Null) {
                    $TopSoftware->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $TopSoftware = $TopSoftware->sortDesc();
            $TopSoft = collect([]);
            $SoftCount = 1;
            $TotalSoft = 0;
            foreach ($TopSoftware as $key => $value) {
                $TotalSoft = $TotalSoft+$value;
                if ($SoftCount < 4 && $key != "Others") {
                    $SoftCount++;
                    $TopSoft[$key] = $value;
                }else if ($SoftCount >= 4) {
                    if ($key != "Others") {
                        if (isset($TopSoft['Others'])) {
                            $TopSoft['Others'] = $TopSoft['Others']+$value;
                        }else{
                            $TopSoft['Others'] = $value;
                        }
                    }else{
                        $TopSoft[$key] = $value;
                    }
                }else if ($key == "Others") {
                    if (isset($TopSoft['Others'])) {
                        $TopSoft['Others'] = $TopSoft['Others']+$value;
                    }else{
                        $TopSoft['Others'] = $value;
                    }
                }
            }
            $TopSoft['Total'] = $TotalSoft;

            $TopIssuesHardware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from Ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Hardware')
                ->groupBy('SubCategory')
                ->get();
                
            $TopHardware = collect([]);
            foreach ($TopIssuesHardware as $issue) {
                if ($issue->SubCategory != Null) {
                    $TopHardware->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $TopHardware = $TopHardware->sortDesc();
            $TopHard = collect([]);
            $HardCount = 1;
            $TotalHard = 0;
            foreach ($TopHardware as $key => $value) {
                $TotalHard = $TotalHard+$value;
                if ($HardCount < 4 && $key != "Others") {
                    $HardCount++;
                    $TopHard[$key] = $value;
                }else if ($HardCount >= 4) {
                    if ($key != "Others") {
                        if (isset($TopHard['Others'])) {
                            $TopHard['Others'] = $TopHard['Others']+$value;
                        }else{
                            $TopHard['Others'] = $value;
                        }
                    }else{
                        $TopHard[$key] = $value;
                    }
                }else if ($key == "Others") {
                    if (isset($TopHard['Others'])) {
                        $TopHard['Others'] = $TopHard['Others']+$value;
                    }else{
                        $TopHard['Others'] = $value;
                    }
                }
            }
            $TopHard['Total'] = $TotalHard;

            $TopIssuesInfrastructure = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from Ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Infrastructure')
                ->groupBy('SubCategory')
                ->get();
                
            $TopInfrastructure = collect([]);
            foreach ($TopIssuesInfrastructure as $issue) {
                if ($issue->SubCategory != Null) {
                    $TopInfrastructure->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $TopInfrastructure = $TopInfrastructure->sortDesc();
            $TopInfra = collect([]);
            $InfraCount = 1;
            $TotalInfra = 0;
            foreach ($TopInfrastructure as $key => $value) {
                $TotalInfra = $TotalInfra+$value;
                if ($InfraCount < 4 && $key != "Others") {
                    $InfraCount++;
                    $TopInfra[$key] = $value;
                }else if ($InfraCount >= 4) {
                    if ($key != "Others") {
                        if (isset($TopInfra['Others'])) {
                            $TopInfra['Others'] = $TopInfra['Others']+$value;
                        }else{
                            $TopInfra['Others'] = $value;
                        }
                    }else{
                        $TopInfra[$key] = $value;
                    }
                }else if ($key == "Others") {
                    if (isset($TopInfra['Others'])) {
                        $TopInfra['Others'] = $TopInfra['Others']+$value;
                    }else{
                        $TopInfra['Others'] = $value;
                    }
                }
            }
            $TopInfra['Total'] = $TotalInfra;

            $TopIssuesOthers = Ticket::select('ProblemCategory', DB::raw('Count(ProblemCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('ProblemCategory', 'Others')
                ->groupBy('ProblemCategory')
                ->get();
                
            $TopOthers = collect([]);
            foreach ($TopIssuesOthers as $issue) {
                if ($issue->ProblemCategory != Null) {
                    $TopOthers->offsetSet($issue->ProblemCategory,$issue->Total);
                }
            }
            $TopOthers = $TopOthers->sortDesc();
            // return $TopOthers;
            $softwarekey = [];
            foreach ($TopSoft as $key => $value) {
                if (count($softwarekey) < 3) {
                    if ($key != "Others") {
                        array_push($softwarekey, $key);
                    }
                }else if (count($softwarekey) < 4) {
                    array_push($softwarekey, 'Others');
                    array_push($softwarekey, 'Total');
                }
            }
            $softwareval = [];
            foreach ($TopSoft as $key => $value) {
                if (count($softwareval) < 3) {
                    if ($key != "Others") {
                        array_push($softwareval, $value);
                    }
                }else if (count($softwareval) < 4) {
                    array_push($softwareval, $TopSoft['Others']);
                    array_push($softwareval, $TopSoft['Total']);
                }
            }
            $Hardwarekey = [];
            foreach ($TopHard as $key => $value) {
                if (count($Hardwarekey) < 3) {
                    if ($key != "Others") {
                        array_push($Hardwarekey, $key);
                    }
                }else if (count($Hardwarekey) < 4) {
                    array_push($Hardwarekey, 'Others');
                    array_push($Hardwarekey, 'Total');
                }
            }
            $Hardwareval = [];
            foreach ($TopHard as $key => $value) {
                if (count($Hardwareval) < 3) {
                    if ($key != "Others") {
                        array_push($Hardwareval, $value);
                    }
                }else if (count($Hardwareval) < 4) {
                    array_push($Hardwareval, $TopHard['Others']);
                    array_push($Hardwareval, $TopHard['Total']);
                }
            }
            $Infrawarekey = [];
            foreach ($TopInfra as $key => $value) {
                if (count($Infrawarekey) < 3) {
                    if ($key != "Others") {
                        array_push($Infrawarekey, $key);
                    }
                }else if (count($Infrawarekey) < 4) {
                    array_push($Infrawarekey, 'Others');
                    array_push($Infrawarekey, 'Total');
                }
            }
            $Infrawareval = [];
            foreach ($TopInfra as $key => $value) {
                if (count($Infrawareval) < 3) {
                    if ($key != "Others") {
                        array_push($Infrawareval, $value);
                    }
                }else if (count($Infrawareval) < 4) {
                    array_push($Infrawareval, $TopInfra['Others']);
                    array_push($Infrawareval, $TopInfra['Total']);
                }
            }
        }
        return view('dashpcategory', compact(
            'TopSoft',
            'TopHard',
            'TopInfra',
            'softwarekey',
            'softwareval',
            'Hardwareval',
            'Hardwarekey',
            'Infrawareval',
            'Infrawarekey',
            'TopOthers'
        ));
    }

    public function totalticket(Request $request, $datefrom, $dateto)
    {
        if ($datefrom != "default") {
            if (strtotime($datefrom) == false || strtotime($dateto) == false) {
                return view('errors.404');
            }
        }else{
            if ($dateto != 1) {
                return view('errors.404');
            }
        }
        if ($datefrom == "default") {
            $StoreTopIssues = Ticket::query()->select('SubCategory', DB::raw('Count(SubCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('SBU', 'Store')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
                ->groupBy('SubCategory')
                ->get();
            $Stop = collect([]);
            foreach ($StoreTopIssues as $issue) {
                if ($issue->SubCategory != Null) {
                    $Stop->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $StoreTop = $Stop->sortDesc();
            $OfficeTopIssues = Ticket::query()->select('SubCategory', DB::raw('Count(SubCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('SBU', 'Office')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
                ->groupBy('SubCategory')
                ->get();
            $Otop = collect([]);
            foreach ($OfficeTopIssues as $issue) {
                if ($issue->SubCategory != Null) {
                    $Otop->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $OfficeTop = $Otop->sortDesc();
            $PlantTopIssues = Ticket::query()->select('SubCategory', DB::raw('Count(SubCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('SBU', 'Plant')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
                ->groupBy('SubCategory')
                ->get();
            $Ptop = collect([]);
            foreach ($PlantTopIssues as $issue) {
                if ($issue->SubCategory != Null) {
                    $Ptop->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $PlantTop = $Ptop->sortDesc();
        }else{
            $StoreTopIssues = Ticket::query()->select('SubCategory', DB::raw('Count(SubCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('SBU', 'Store')
                ->whereDate('DateCreated', '>=', Carbon::parse($datefrom))
                ->whereDate('DateCreated', '<=', Carbon::parse($dateto))
                ->groupBy('SubCategory')
                ->get();
            $Stop = collect([]);
            foreach ($StoreTopIssues as $issue) {
                if ($issue->SubCategory != Null) {
                    $Stop->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $StoreTop = $Stop->sortDesc();
            $OfficeTopIssues = Ticket::query()->select('SubCategory', DB::raw('Count(SubCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('SBU', 'Office')
                ->whereDate('DateCreated', '>=', Carbon::parse($datefrom))
                ->whereDate('DateCreated', '<=', Carbon::parse($dateto))
                ->groupBy('SubCategory')
                ->get();
            $Otop = collect([]);
            foreach ($OfficeTopIssues as $issue) {
                if ($issue->SubCategory != Null) {
                    $Otop->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $OfficeTop = $Otop->sortDesc();
            $PlantTopIssues = Ticket::query()->select('SubCategory', DB::raw('Count(SubCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('SBU', 'Plant')
                ->whereDate('DateCreated', '>=', Carbon::parse($datefrom))
                ->whereDate('DateCreated', '<=', Carbon::parse($dateto))
                ->groupBy('SubCategory')
                ->get();
            $Ptop = collect([]);
            foreach ($PlantTopIssues as $issue) {
                if ($issue->SubCategory != Null) {
                    $Ptop->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
            $PlantTop = $Ptop->sortDesc();
        }

        return view('dashtotalticket', compact(
            'StoreTop',
            'OfficeTop',
            'PlantTop',
        ));
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
