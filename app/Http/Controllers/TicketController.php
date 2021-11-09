<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Sheets\ExportDashboard;
use App\Exports\RawDataClosed;
use App\Exports\RawDataCancelled;
use App\Exports\RawDataOpen;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\Task;
use DB;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth', 'CheckPassword']);
    }

    public function ExportDashboard(Request $request, $datefrom, $dateto) 
    {
       
        return Excel::download(new ExportDashboard($datefrom, $dateto), 'test.xlsx');
    }

    public function openTicketData(Request $request) 
    {
        return Excel::download(new RawDataOpen(), Carbon::now()->format('Y-m-d').' - OpenTickets.xlsx');
    }
    public function closeTicketData(Request $request) 
    {
        return Excel::download(new RawDataClosed(), Carbon::now()->format('Y-m-d').' - ClosedTickets.xlsx');
    }
    public function cancelTicketData(Request $request) 
    {
        return Excel::download(new RawDataCancelled(), Carbon::now()->format('Y-m-d').' - CancelledTickets.xlsx');
    }
    public function ExportData(Request $request, $year, $month, $monthname) 
    {
        $store = Ticket::query()
            ->select('SubCategory', DB::raw('COUNT(SubCategory) as count'))
            ->join('Data','Code', 'StoreCode')
            ->whereMonth('DateCreated', $month)
            ->whereYear('DateCreated', $year)
            ->whereNotNull('SubCategory')
            ->where('SBU', 'Store')
            ->groupBy('SubCategory')
            ->orderBy('count', 'DESC')
            ->get();
        $plant = Ticket::query()
            ->select('SubCategory', DB::raw('COUNT(SubCategory) as count'))
            ->join('Data','Code', 'StoreCode')
            ->whereMonth('DateCreated', $month)
            ->whereYear('DateCreated', $year)
            ->whereNotNull('SubCategory')
            ->where('SBU', 'Plant')
            ->groupBy('SubCategory')
            ->orderBy('count', 'DESC')
            ->get();
        $office = Ticket::query()
            ->select('SubCategory', DB::raw('COUNT(SubCategory) as count'))
            ->join('Data','Code', 'StoreCode')
            ->whereMonth('DateCreated', $month)
            ->whereYear('DateCreated', $year)
            ->whereNotNull('SubCategory')
            ->where('SBU', 'Office')
            ->groupBy('SubCategory')
            ->orderBy('count', 'DESC')
            ->get();

        return Excel::download(new DataExports($year,$month,$monthname,$store,$plant,$office), $monthname.' - '.$year.'.xlsx');
    }

    public function closedtickets()
    {
        $tickets = Ticket::query()
            ->select(
                'DateCreated',
                'TaskNumber',
                'TaskStatus',
                'CreatedBy',
                'IncidentStatus',
                'SubCategory as Issue',
                'ProblemCategory',
                'StoreCode',
                'Store_Name as StoreName',
                'AdditionalStoreDetails',
                'LatestNotes',
                // 'TaskStatus'
                )
            ->join('Data', 'Code', 'StoreCode')
            ->whereIN('TaskStatus',['Submitted','Closed'])
            ->whereIN('IncidentStatus', ['Closed', 'Resolved'])
            ->get();
        return DataTables::of($tickets)
        ->addColumn('StoreName', function (Ticket $tickets){
            if ($tickets->StoreCode == '2000') {
                return $tickets->AdditionalStoreDetails;
            }
            return $tickets->StoreName;
        })
        ->make(true);
    }

    public function getticket()
    {
        $tickets = Ticket::query()
            ->select(
                'DateCreated',
                'TaskNumber',
                'TaskStatus',
                'CreatedBy',
                'IncidentStatus',
                'CallType',
                'SubCategory as Issue',
                'ProblemCategory',
                'StoreCode',
                'SBU',
                'Status',
                'Store_Name as StoreName',
                'AdditionalStoreDetails',
                'LatestNotes'
                // 'TaskStatus'
                )
            ->join('Data', 'Code', 'StoreCode')
            ->where('TaskStatus','!=','Closed')
            ->whereIN('Status',['Open', 'Re Open', 'Closed', 'Cancelled'])
            ->get();
        
        return DataTables::of($tickets)
        ->addColumn('StoreName', function (Ticket $tickets){
            if ($tickets->StoreCode == '2000') {
                return $tickets->AdditionalStoreDetails;
            }
            return $tickets->StoreName;
        })
        ->addColumn('SystemStatus', function (Ticket $tickets){
            if ($tickets->TaskStatus == 'Submitted') {
                if ($tickets->IncidentStatus == 'Resolved') {
                    if ($tickets->Status == "Closed") {
                        return 'Closed';
                    }else if ($tickets->Status == "Cancelled") {
                        return 'Cancelled';
                    }else if ($tickets->Status == "Open") {
                        return 'Open-Submitted';
                    }else if ($tickets->Status == "Re Open") {
                        return 'Open-Submitted';
                    }
                }else{
                    if ($tickets->Status == "Cancelled") {
                        return 'Cancelled';
                    }else if ($tickets->Status == "Open") {
                        return 'Open';
                    }else if ($tickets->Status == "Re Open") {
                        return 'Open';
                    }else{
                        return 'Open';
                    }
                }
            }else if ($tickets->TaskStatus != ""){
                if ($tickets->TaskStatus != 'Closed') {
                    if ($tickets->Status == "Cancelled") {
                        return 'Cancelled';
                    }else if ($tickets->Status == "Open") {
                        return 'Open';
                    }else if ($tickets->Status == "Re Open") {
                        return 'Open';
                    }
                }
            }
        })
        ->make(true);
    }

    public function dashpercent()
    {
        $TopIssues = Ticket::select(DB::raw('round((Count(SubCategory)/(select count(*) from old_ticket)*100),2) as percentage'))
            ->join('Data','Code','StoreCode')
            ->groupBy('SubCategory')
            ->get()->toArray();
            // return $TopIssues;
        // $top = collect([]);
        // $percent = collect([]);
        // foreach ($TopIssues as $issue) {
        //     if ($issue->SubCategory != Null) {
        //         // return $issue->percentage;
        //         $percent->offsetSet($issue->percentage);
        //     }
        // }
        asort($TopIssues);
        return response()->json($TopIssues);

    }
    
    public function dashdata(Request $request, $datefrom, $dateto)
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
            $tickets = Ticket::query()
                ->select(
                    DB::raw("monthname(DateCreated) as Month"),
                    DB::raw("max(Year(DateCreated)) as Year"),
                    DB::raw('SUM(CASE WHEN SBU = \'Store\' THEN 1 ELSE 0 END) as Store'),
                    DB::raw('SUM(CASE WHEN SBU = \'Plant\' THEN 1 ELSE 0 END) as Plant'),
                    DB::raw('SUM(CASE WHEN SBU = \'Office\' THEN 1 ELSE 0 END) as Office'),
                    DB::raw('max(DateCreated) as DateCreated')
                )
                ->join('Data', 'Code', 'StoreCode')
                ->where('TaskStatus','!=','Closed')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
                ->orderBy('DateCreated', 'asc')
                ->groupBy('Month')
                ->get();
        }else{
            $tickets = Ticket::query()
                ->select(
                    DB::raw("monthname(DateCreated) as Month"),
                    DB::raw("max(Year(DateCreated)) as Year"),
                    DB::raw('SUM(CASE WHEN SBU = \'Store\' THEN 1 ELSE 0 END) as Store'),
                    DB::raw('SUM(CASE WHEN SBU = \'Plant\' THEN 1 ELSE 0 END) as Plant'),
                    DB::raw('SUM(CASE WHEN SBU = \'Office\' THEN 1 ELSE 0 END) as Office'),
                    DB::raw('max(DateCreated) as DateCreated')
                )
                ->join('Data', 'Code', 'StoreCode')
                ->where('TaskStatus','!=','Closed')
                ->whereMonth('DateCreated', '>=', Carbon::parse($datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($dateto))
                ->orderBy('DateCreated', 'asc')
                ->groupBy('Month')
                ->get();
        }
        return DataTables::of($tickets)
        ->addColumn('Store', function (Ticket $tickets){
            return number_format($tickets->Store);
        })
        ->addColumn('Plant', function (Ticket $tickets){
            return number_format($tickets->Plant);
        })
        ->addColumn('Office', function (Ticket $tickets){
            return number_format($tickets->Office);
        })
        ->addColumn('Total', function (Ticket $tickets){
            return number_format($tickets->Store+$tickets->Plant+$tickets->Office);
        })
        ->addColumn('Tot', function (Ticket $tickets){
            return $tickets->Store+$tickets->Plant+$tickets->Office;
        })
        ->make(true);
    }

    public function ResTickCount()
    {

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
        return DataTables::of($ResTickCount)
        ->addColumn('one', function (Ticket $ResTickCount){
            return $ResTickCount->onedt+$ResTickCount->onet;
        })
        ->addColumn('two', function (Ticket $ResTickCount){
            return $ResTickCount->twodt+$ResTickCount->twot;
        })
        ->addColumn('three', function (Ticket $ResTickCount){
            return $ResTickCount->threedt+$ResTickCount->threet;
        })
        ->addColumn('four', function (Ticket $ResTickCount){
            return $ResTickCount->fourdt+$ResTickCount->fourt;
        })
        ->addColumn('five', function (Ticket $ResTickCount){
            return $ResTickCount->fivedt+$ResTickCount->fivet;
        })
        ->addColumn('morethanfive', function (Ticket $ResTickCount){
            return $ResTickCount->morethanfivedt+$ResTickCount->morethanfivet;
        })
        ->addColumn('grandtotal', function (Ticket $ResTickCount){
            return $ResTickCount->onedt+$ResTickCount->onet+$ResTickCount->twodt+$ResTickCount->twot+$ResTickCount->threedt+$ResTickCount->threet+$ResTickCount->fourdt+$ResTickCount->fourt+$ResTickCount->fivedt+$ResTickCount->fivet+$ResTickCount->morethanfivedt+$ResTickCount->morethanfivet;
        })
        ->make(true);
    }

    public function pcategory(Request $request)
    {
        if ($request->datefrom == 'default') {
            # code...
            $TopIssuesSoftware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from old_ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Software/ Application')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
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

            $TopIssuesHardware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from old_ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Hardware')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
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

            $TopIssuesInfrastructure = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from old_ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Infrastructure')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
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
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
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
            $TopIssuesSoftware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from old_ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Software/ Application')
                ->whereMonth('DateCreated', '>=', Carbon::parse($request->datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($request->dateto))
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

            $TopIssuesHardware = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from old_ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Hardware')
                ->whereMonth('DateCreated', '>=', Carbon::parse($request->datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($request->dateto))
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

            $TopIssuesInfrastructure = Ticket::select('SubCategory', DB::raw('Count(SubCategory) as Total'), DB::raw('round((Count(SubCategory)/(select count(*) from old_ticket)*100),2) as percentage'))
                ->join('Data','Code','StoreCode')
                ->whereNotNull('SubCategory')
                ->where('ProblemCategory', 'Infrastructure')
                ->whereMonth('DateCreated', '>=', Carbon::parse($request->datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($request->dateto))
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
                ->whereMonth('DateCreated', '>=', Carbon::parse($request->datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($request->dateto))
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
        $data = [
            'TopSoft'=>$TopSoft,
            'TopHard'=>$TopHard,
            'TopInfra'=>$TopInfra,
            'softwarekey'=>$softwarekey,
            'softwareval'=>$softwareval,
            'Hardwareval'=>$Hardwareval,
            'Hardwarekey'=>$Hardwarekey,
            'Infrawareval'=>$Infrawareval,
            'Infrawarekey'=>$Infrawarekey,
            'TopOthers'=>$TopOthers
        ];
        return response()->json($data);
    }

    
    public function totalticketdata(Request $request)
    {
        if ($request->datefrom == "default") {
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
                ->whereMonth('DateCreated', '>=', Carbon::parse($request->datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($request->dateto))
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
                ->whereMonth('DateCreated', '>=', Carbon::parse($request->datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($request->dateto))
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
                ->whereMonth('DateCreated', '>=', Carbon::parse($request->datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($request->dateto))
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
        
        $data = [
            'StoreTop'=>$StoreTop,
            'OfficeTop'=>$OfficeTop,
            'PlantTop'=>$PlantTop
        ];

        return response()->json($data);
    }
    public function piedata(Request $request)
    {
        if ($request->datefrom == "default") {
            $TopIssues = Ticket::select(
                DB::raw('SUM(CASE WHEN SBU = \'Store\' THEN 1 ELSE 0 END) as Store'),
                DB::raw('SUM(CASE WHEN SBU = \'Plant\' THEN 1 ELSE 0 END) as Plant'),
                DB::raw('SUM(CASE WHEN SBU = \'Office\' THEN 1 ELSE 0 END) as Office')
            )
            ->join('Data','Code','StoreCode')
            ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
            ->whereMonth('DateCreated', '<', Carbon::now())
            ->get();
        }else{
            $TopIssues = Ticket::select(
                    DB::raw('SUM(CASE WHEN SBU = \'Store\' THEN 1 ELSE 0 END) as Store'),
                    DB::raw('SUM(CASE WHEN SBU = \'Plant\' THEN 1 ELSE 0 END) as Plant'),
                    DB::raw('SUM(CASE WHEN SBU = \'Office\' THEN 1 ELSE 0 END) as Office')
                )
                ->join('Data','Code','StoreCode')
                ->whereMonth('DateCreated', '>=', Carbon::parse($request->datefrom))
                ->whereMonth('DateCreated', '<=', Carbon::parse($request->dateto))
                ->get();
            
            
        }
        $data = [
            'TopIssue' => $TopIssues
        ];
        return response()->json($TopIssues);
    }

    public function dailyticketsdata()
    {
        $Store = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y') as Date"))
            ->join('Data','Code','StoreCode')
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->where('SBU', 'Store')
            ->whereNotIn('TaskStatus',['Closed'])
            ->groupBy(DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y')"))
            ->get();
        $Plant = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y') as Date"))
            ->join('Data','Code','StoreCode')
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->where('SBU', 'Plant')
            ->whereNotIn('TaskStatus',['Closed'])
            ->groupBy(DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y')"))
            ->get();
        $Office = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y') as Date"))
            ->join('Data','Code','StoreCode')
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->where('SBU', 'Office')
            ->whereNotIn('TaskStatus',['Closed'])
            ->groupBy(DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y')"))
            ->get();
        $date = Ticket::query()
            ->select(DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->whereNotIn('TaskStatus',['Closed'])
            ->groupBy(DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y')"))
            ->get();
        $dates = Ticket::query()
            ->select(DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->groupBy(DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y')"))
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
        $data = [
            'plnt'=>$plnt,
            'str'=>$str,
            'ofc'=>$ofc,
            'dates'=>$dates,
            'strtotal'=>$strtotal,
            'plnttotal'=>$plnttotal,
            'ofctotal'=>$ofctotal,
            'grandtotal'=>$grandtotal
        ];
        return response()->json($data);
    }

    public function monthlyticketsdata(Request $request)
    {
        $weekstart = Carbon::createFromDate($request->year, $request->month, 1)->startofweek(Carbon::SUNDAY)->toDateString();
        $weekend = Carbon::createFromDate($request->year, $request->month, 30)->endofweek(Carbon::SATURDAY)->toDateString();
        $week1 = Carbon::parse($weekstart)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(6)->formatLocalized('%b %d');
        $week2 = Carbon::parse($weekstart)->addDays(7)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(13)->formatLocalized('%b %d');
        $week3 = Carbon::parse($weekstart)->addDays(14)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(20)->formatLocalized('%b %d');
        $week4 = Carbon::parse($weekstart)->addDays(21)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(27)->formatLocalized('%b %d');
        $week5 = Carbon::parse($weekstart)->addDays(28)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(34)->formatLocalized('%b %d');
        $Store = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y') as Date"))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Store')
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy('Date')
            ->get();
        $StoreW = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("WEEK(DateCreated) as Date"))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Store')
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('Date')
            ->get();
        $Plant = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y') as Date"))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Plant')
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy('Date')
            ->get();
        $PlantW = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("WEEK(DateCreated) as Date"))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Plant')
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('Date')
            ->get();
        $Office = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y') as Date"))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Office')
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy('Date')
            ->get();
        $OfficeW = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("WEEK(DateCreated) as Date"))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Office')
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('Date')
            ->get();
        $date = Ticket::query()
            ->select(DB::raw("DATE_FORMAT(DateCreated, '%m-%d-%Y') as date"))
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy('date')
            ->get();
        $dateW = Ticket::query()
            ->select(DB::raw("WEEK(DateCreated) as date"))
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('date')
            ->get();
        $dates = Ticket::query()
            ->select(DB::raw("DATE_FORMAT(DateCreated, '%d') as date"))
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy('date')
            ->pluck('date');
        $datesW = Ticket::query()
            ->select(DB::raw("WEEK(DateCreated) as date"))
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('date')
            ->pluck('date');
        $ofc = [];
        $plnt = [];
        $str = [];
        $ofcW = [];
        $plntW = [];
        $strW = [];
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
                array_push($str, 0);
            }
        }
        foreach ($dateW as $key) {
            if ($OfficeW->where('Date', $key->date)->first()) {
                array_push($ofcW, $OfficeW->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($ofcW, 0);
            }
            if ($PlantW->where('Date', $key->date)->first()) {
                array_push($plntW, $PlantW->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($plntW, 0);
            }
            if ($StoreW->where('Date', $key->date)->first()) {
                array_push($strW, $StoreW->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($strW, '0');
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

        $plntW = collect($plntW);
        $strW = collect($strW);
        $ofcW = collect($ofcW);
        $strtotalW = $strW->sum(function($item) {
            return $item;
        });
        $plnttotalW = $plntW->sum(function($item) {
            return $item;
        });
        $ofctotalW = $ofcW->sum(function($item) {
            return $item;
        });
        $grandtotalW = array();
        foreach ($strW as $key => $value) {
            array_push($grandtotalW, $strW[$key]+$plntW[$key]+$ofcW[$key]);
        }
        array_push($grandtotalW, $strtotalW+$plnttotalW+$ofctotalW);
        $weekcount = count($grandtotalW);

        $percent = array();
        if (count($grandtotalW) < 6) {
            array_push($percent, round(($strtotalW/$grandtotalW[$weekcount-1])*100,2).'%');
            array_push($percent, round(($plnttotalW/$grandtotalW[$weekcount-1])*100,2).'%');
            array_push($percent, round(($ofctotalW/$grandtotalW[$weekcount-1])*100,2).'%');
        }else{
            array_push($percent, round(($strtotalW/$grandtotalW[$weekcount-1])*100,2).'%');
            array_push($percent, round(($plnttotalW/$grandtotalW[$weekcount-1])*100,2).'%');
            array_push($percent, round(($ofctotalW/$grandtotalW[$weekcount-1])*100,2).'%');
        }
        $weekslabel = array();
        for ($i=1; $i <= count($datesW); $i++) { 
           array_push($weekslabel, 'Week '.$i);
        }
        $weekslabel=collect($weekslabel);
        $data = [
            'plnt'=>$plnt,
            'str'=>$str,
            'ofc'=>$ofc,
            'plntW'=>$plntW,
            'strW'=>$strW,
            'ofcW'=>$ofcW,
            'dates'=>$dates,
            'datesW'=>$datesW,
            'strtotal'=>$strtotal,
            'plnttotal'=>$plnttotal,
            'ofctotal'=>$ofctotal,
            'grandtotal'=>$grandtotal,
            'strtotalW'=>$strtotalW,
            'plnttotalW'=>$plnttotalW,
            'ofctotalW'=>$ofctotalW,
            'grandtotalW'=>$grandtotalW,
            'percent'=>$percent,
            'weekcount'=>count($datesW),
            'weekslabel'=>$weekslabel,
            'firstweek'=>$week1,
            'secondweek'=>$week2,
            'thirdweek'=>$week3,
            'fourthweek'=>$week4,
            'fifthweek'=>$week5,
        ];

        return response()->json($data);
    }

    public function storetopissue()
    {
        $TopIssues = Ticket::select('SubCategory', 
                DB::raw
                (
                    'SUM(CASE WHEN Status = \'Open\' THEN 1 ELSE 0 END) as Open'
                ),
                DB::raw
                (
                    'SUM(CASE WHEN Status = \'Closed\' THEN 1 ELSE 0 END) as Closed'
                ),
            )
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->groupBy('SubCategory')
            ->get();
        return DataTables::of($TopIssues)
        ->addColumn('Total', function (Ticket $TopIssues){
            return $TopIssues->Open+$TopIssues->Closed;
        })
        ->make(true);
    }

    public function taskdata(Request $request)
    {
        $store = Ticket::query()
            ->join('Data','Code','StoreCode')
            ->select(
                'StoreCode',
                'Store_Name',
                'Address',
                'Data.Ownership',
                'ContactPerson',
                'ContactNumber',
                'Email',
                'ProblemReported',
                'Cluster',
                'RootCause',
                'LatestNotes',
                'SBU',
                'IncidentStatus',
                'ActionTaken',
                'ResolverGroup',
                'ResolvedBy',
                'Type as StoreType'
            )
            ->where('TaskNumber', $request->TaskNumber)
            ->first();

        $Remarks = Ticket::query()
            ->select('Author', 'Message', 'Timestamp')
            ->join('Remark', 'Remark.taskid', 'Ticket.taskid')
            ->orderBy('Remark.Id', 'DESC')
            ->where('TaskNumber', $request->TaskNumber)
            ->get();
        $History = Ticket::query()
            ->select('Label as Action', 'Snapshotvalue as Original','Source','Timestamp','UpdatedValue as Updated', 'Message','AuditLevel')
            ->join('History', 'History.TaskId', 'Ticket.TaskId')
            ->where('TaskNumber', $request->TaskNumber)
            ->orderBy('History.Id', 'DESC')
            ->get();
        return response()->json(
            [
                'Store_Code'=>$store->StoreCode,
                'Store_Address'=>$store->Address,
                'Ownership'=>$store->Ownership,
                'Contact_Person'=>$store->ContactPerson,
                'Contact_Number'=>$store->ContactNumber,
                'Store_Name'=>$store->Store_Name,
                'Email_Address'=>$store->Email,
                'Location'=>$store->Cluster,
                'Latest_Notes'=>$store->LatestNotes,
                'Sbu'=>$store->SBU,
                'IncidentStatus'=>$store->IncidentStatus,
                'Problem_Reported'=>$store->ProblemReported,
                'GBIActionTaken'=>$store->ActionTaken,
                'GBIResolverGroup'=>$store->ResolverGroup,
                'GBIResolvedBy'=>$store->ResolvedBy,
                'Root_Cause'=>$store->RootCause,
                // 'Remarks'=>$Remarks,
                // 'History'=>$History,
                'Remarks'=>$Remarks,
                'History'=>$History,
                'GBIStoreType'=>$store->StoreType
                // 'Incident_Status'=>$incidentstatus
            ]
        );
    }       
}
