<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Sheets\DataExports;
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
