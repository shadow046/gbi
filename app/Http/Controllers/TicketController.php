<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Sheets\DataExports;
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
        $this->middleware(['auth', 'verified']);
        
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

        return Excel::download(new DataExports($year,$month,$monthname,$store,$plant), $monthname.' - '.$year.'.xlsx');
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
            // ->whereNotIN('TaskStatus',['Submitted','Closed'])
            ->whereIN('Status', ['Closed'])
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
        if (auth()->user()->roles->first()->name == "Agent") {
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
            ->whereNotIN('TaskStatus',['Closed'])
            ->whereIN('Status', ['Open', 'Re Open', 'Closed'])
            ->get();
        }else if (auth()->user()->roles->first()->name == "Manager") {
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
            ->whereNotIN('TaskStatus',['Closed'])
            ->whereIN('Status', ['Open', 'Re Open'])
            ->get();
        }else if (auth()->user()->roles->first()->name == "Client") {
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
            ->whereNotIN('TaskStatus',['Closed'])
            ->whereIN('Status', ['Open', 'Re Open'])
            ->get();
        }
        
        return DataTables::of($tickets)
        ->addColumn('StoreName', function (Ticket $tickets){
            if ($tickets->StoreCode == '2000') {
                return $tickets->AdditionalStoreDetails;
            }
            return $tickets->StoreName;
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
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
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
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
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
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
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
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
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
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
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
            'weekslabel'=>$weekslabel
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
        $storecode = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIStoreCode')
            ->select('value')
            ->pluck('value')->first();
        $storename = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIStoreName')
            ->select('value')
            ->pluck('value')->first();
        $storeaddress = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIStoreAddress')
            ->select('value')
            ->pluck('value')->first();
        $ownership = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIOwnership')
            ->select('value')
            ->pluck('value')->first();

        $contactperson = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIContactPerson')
            ->select('value')
            ->pluck('value')->first();

        $contactnumber = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIContactNumber')
            ->select('value')
            ->pluck('value')->first();

        $email = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIEmailAddress')
            ->select('value')
            ->pluck('value')->first();

        $problemreported = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIProblemReported')
            ->select('value')
            ->pluck('value')->first();

        $location = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBILocation')
            ->select('value')
            ->pluck('value')->first();
        
        $rootcause = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIRootCause')
            ->select('value')
            ->pluck('value')->first();

        $latestnotes = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBILatestNotes')
            ->select('value')
            ->pluck('value');
        $SBU = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBISBU')
            ->select('value')
            ->pluck('value')->first();

        $IncidentStatus = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIIncidentStatus')
            ->select('value')
            ->pluck('value')->first();

        $GBIActionTaken = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIActionTaken')
            ->select('value')
            ->pluck('value')->first();

        $GBIResolverGroup = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIResolverGroup')
            ->select('value')
            ->pluck('value')->first();

        $GBIResolvedBy = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIResolvedBy')
            ->select('value')
            ->pluck('value')->first();
        $GBIStoreType = Task::query()
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->where('FieldId', 'GBIStoreType')
            ->select('value')
            ->pluck('value')->first();

        $Remarks = Task::query()
            ->select('Author', 'Message', 'Timestamp')
            ->join('Remark', 'taskid', 'task.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->get();
        $History = Task::query()
            ->select('Label as Action', 'Snapshotvalue as Original','Source','Timestamp','UpdatedValue as Updated', 'Message','AuditLevel')
            ->join('taskauditlog', 'taskid', 'task.id')
            ->where('TaskNumber', $request->TaskNumber)
            ->get();
        return response()->json(
            [
                'Store_Code'=>$storecode,
                'Store_Address'=>$storeaddress,
                'Ownership'=>$ownership,
                'Contact_Person'=>$contactperson,
                'Contact_Number'=>$contactnumber,
                'Store_Name'=>$storename,
                'Email_Address'=>$email,
                'Location'=>$location,
                'Latest_Notes'=>$latestnotes,
                'Sbu'=>$SBU,
                'IncidentStatus'=>$IncidentStatus,
                'Problem_Reported'=>$problemreported,
                'GBIActionTaken'=>$GBIActionTaken,
                'GBIResolverGroup'=>$GBIResolverGroup,
                'GBIResolvedBy'=>$GBIResolvedBy,
                'Root_Cause'=>$rootcause,
                'Remarks'=>$Remarks,
                'History'=>$History,
                'GBIStoreType'=>$GBIStoreType
                // 'Incident_Status'=>$incidentstatus
            ]
        );
    }       
}
