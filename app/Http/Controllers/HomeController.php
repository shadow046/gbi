<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\FormField;
use App\Models\Form;
use Response;
use DB;
use DateTime;
Use Exception;
use Illuminate\Support\Arr;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        // return DB::table('TaskAuditLog')->select('*')
        //     ->where('taskid', '1187251')
        //     ->get();
            // return array_filter(FormField::query()->select('value')
            // ->where('FieldId', 'GBIResolverGroup')
            // ->groupby('value')
            // ->get()->all());
            try {
                DB::connection()
                    ->getPdo();
            } catch (Exception $e) {
                // abort($e instanceof PDOException ? 503 : 500);
                // return "Connection to database failed"
                return abort(403, 'There was a problem connecting to the server. Please try again later.');
            }
            $TopIssue = FormField::query()->select(
                DB::raw(
                    'SUM(CASE WHEN value = \'avr\' THEN 1 ELSE 0 END) as avr'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'AX Issue\' THEN 1 ELSE 0 END) as AXIssue'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Back Office\' THEN 1 ELSE 0 END) as BackOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Biometrics\' THEN 1 ELSE 0 END) as Biometrics'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Browser\' THEN 1 ELSE 0 END) as Browser'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Cabling\' THEN 1 ELSE 0 END) as Cabling'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Cash Drawer\' THEN 1 ELSE 0 END) as CashDrawer'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'CBB\' THEN 1 ELSE 0 END) as CBB'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'CCTV\' THEN 1 ELSE 0 END) as CCTV'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Desktop\' THEN 1 ELSE 0 END) as Desktop'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Dismantling / Re-Installation\' THEN 1 ELSE 0 END) as Dismantling'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'EIMS\' THEN 1 ELSE 0 END) as EIMS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Email\' THEN 1 ELSE 0 END) as Email'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'EOD\' THEN 1 ELSE 0 END) as EOD'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'E-Sales\' THEN 1 ELSE 0 END) as ESales'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-MPC\' THEN 1 ELSE 0 END) as HWMPC'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-PC/POS\' THEN 1 ELSE 0 END) as HWPCPOS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-POS\' THEN 1 ELSE 0 END) as HWPOS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-Printer\' THEN 1 ELSE 0 END) as HWPrinter'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-Server\' THEN 1 ELSE 0 END) as HWServer'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Inquiry\' THEN 1 ELSE 0 END) as Inquiry'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Installation\' THEN 1 ELSE 0 END) as Installation'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Internet\' THEN 1 ELSE 0 END) as Internet'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Laptop\' THEN 1 ELSE 0 END) as Laptop'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Microsoft 365\' THEN 1 ELSE 0 END) as Microsoft365'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Modem\' THEN 1 ELSE 0 END) as Modem'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'MS Office\' THEN 1 ELSE 0 END) as MSOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'My HR\' THEN 1 ELSE 0 END) as MyHR'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Others\' THEN 1 ELSE 0 END) as Others'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'PC/POS\' THEN 1 ELSE 0 END) as PCPOS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'POS\' THEN 1 ELSE 0 END) as POS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'POS Application\' THEN 1 ELSE 0 END) as POSApplication'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Price Change\' THEN 1 ELSE 0 END) as PriceChange'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Printer\' THEN 1 ELSE 0 END) as Printer'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Relocation\' THEN 1 ELSE 0 END) as Relocation'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Reset Password\' THEN 1 ELSE 0 END) as ResetPassword'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Router\' THEN 1 ELSE 0 END) as Router'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Sales Discrepancy\' THEN 1 ELSE 0 END) as SalesDiscrepancy'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'UPS\' THEN 1 ELSE 0 END) as UPS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'VPN\' THEN 1 ELSE 0 END) as VPN'
                )
            )
            ->where('FieldId', 'GBISubCategory')
            ->get();
        
            $top = [
                'AVR'=>$TopIssue[0]->avr,
                'AX Issue'=>$TopIssue[0]->Axissue,
                'Back Office'=>$TopIssue[0]->Backoffice,
                'Biometrics'=>$TopIssue[0]->Biometrics,
                'Browser'=>$TopIssue[0]->Browser,
                'Cabling'=>$TopIssue[0]->Cabling,
                'Cash Drawer'=>$TopIssue[0]->CashDrawer,
                'CBB'=>$TopIssue[0]->CBB,
                'Cctv'=>$TopIssue[0]->CCTV,
                'Desktop'=>$TopIssue[0]->Desktop,
                'Dismantling / Re-Installation'=>$TopIssue[0]->Dismantling,
                'EIMS'=>$TopIssue[0]->EIMS,
                'Email'=>$TopIssue[0]->Email,
                'EOD'=>$TopIssue[0]->EOD,
                'E-Sales'=>$TopIssue[0]->ESales,
                'HW-MPC'=>$TopIssue[0]->HWMPC,
                'HW-PC/POS'=>$TopIssue[0]->HWPCPOS,
                'HW-POS"'=>$TopIssue[0]->HWPOS,
                'HW-Printer'=>$TopIssue[0]->HWPrinter,
                'HW-Server'=>$TopIssue[0]->HWServer,
                'Inquiry'=>$TopIssue[0]->Inquiry,
                'Installation'=>$TopIssue[0]->Installation,
                'Internet'=>$TopIssue[0]->Internet,
                'Laptop'=>$TopIssue[0]->Laptop,
                'Microsoft 365'=>$TopIssue[0]->Microsoft365,
                'Modem'=>$TopIssue[0]->Modem,
                'MS Office'=>$TopIssue[0]->MSOffice,
                'My HR'=>$TopIssue[0]->MyHR,
                'Others'=>$TopIssue[0]->Others,
                'PC/POS'=>$TopIssue[0]->PCPOS,
                'POS'=>$TopIssue[0]->POS,
                'POS Application'=>$TopIssue[0]->POSApplication,
                'Price Change'=>$TopIssue[0]->PriceChange,
                'Printer'=>$TopIssue[0]->Printer,
                'Relocation'=>$TopIssue[0]->Relocation,
                'Reset Password'=>$TopIssue[0]->ResetPassword,
                'Router'=>$TopIssue[0]->Router,
                'Sales Discrepancy'=>$TopIssue[0]->SalesDiscrepancy,
                'UPS'=>$TopIssue[0]->UPS,
                'VPN'=>$TopIssue[0]->VPN
            ];
        
        $filtered = array_filter($top);
        arsort($filtered);
        //
        
        //resolver group

        $resolvergroup = FormField::query()->select(
            DB::raw(
                'SUM(CASE WHEN value = \'avr\' THEN 1 ELSE 0 END) as avr'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'AX Issue\' THEN 1 ELSE 0 END) as AXIssue'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Back Office\' THEN 1 ELSE 0 END) as BackOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Biometrics\' THEN 1 ELSE 0 END) as Biometrics'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Browser\' THEN 1 ELSE 0 END) as Browser'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cabling\' THEN 1 ELSE 0 END) as Cabling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cash Drawer\' THEN 1 ELSE 0 END) as CashDrawer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CBB\' THEN 1 ELSE 0 END) as CBB'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CCTV\' THEN 1 ELSE 0 END) as CCTV'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Desktop\' THEN 1 ELSE 0 END) as Desktop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Dismantling / Re-Installation\' THEN 1 ELSE 0 END) as Dismantling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EIMS\' THEN 1 ELSE 0 END) as EIMS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Email\' THEN 1 ELSE 0 END) as Email'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EOD\' THEN 1 ELSE 0 END) as EOD'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'E-Sales\' THEN 1 ELSE 0 END) as ESales'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-MPC\' THEN 1 ELSE 0 END) as HWMPC'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-PC/POS\' THEN 1 ELSE 0 END) as HWPCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-POS\' THEN 1 ELSE 0 END) as HWPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Printer\' THEN 1 ELSE 0 END) as HWPrinter'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Server\' THEN 1 ELSE 0 END) as HWServer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Inquiry\' THEN 1 ELSE 0 END) as Inquiry'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Installation\' THEN 1 ELSE 0 END) as Installation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Internet\' THEN 1 ELSE 0 END) as Internet'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Laptop\' THEN 1 ELSE 0 END) as Laptop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Microsoft 365\' THEN 1 ELSE 0 END) as Microsoft365'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Modem\' THEN 1 ELSE 0 END) as Modem'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'MS Office\' THEN 1 ELSE 0 END) as MSOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'My HR\' THEN 1 ELSE 0 END) as MyHR'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Others\' THEN 1 ELSE 0 END) as Others'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'PC/POS\' THEN 1 ELSE 0 END) as PCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS\' THEN 1 ELSE 0 END) as POS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS Application\' THEN 1 ELSE 0 END) as POSApplication'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Price Change\' THEN 1 ELSE 0 END) as PriceChange'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Printer\' THEN 1 ELSE 0 END) as Printer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Relocation\' THEN 1 ELSE 0 END) as Relocation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Reset Password\' THEN 1 ELSE 0 END) as ResetPassword'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Router\' THEN 1 ELSE 0 END) as Router'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Sales Discrepancy\' THEN 1 ELSE 0 END) as SalesDiscrepancy'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'UPS\' THEN 1 ELSE 0 END) as UPS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'VPN\' THEN 1 ELSE 0 END) as VPN'
            )
        )
        ->where('FieldId', 'GBIResolverGroup')
        ->get();
        //

        //aging

        $lessthan5 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->whereNotIN('Value', ['Resolved','Closed'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $sixto10 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->whereNotIN('Value', ['Resolved','Closed'])
            // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $elevento15 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->whereNotIN('Value', ['Resolved','Closed'])
            // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $sixteento20 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->whereNotIN('Value', ['Resolved','Closed'])
            // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $greaterthan20 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->whereNotIN('Value', ['Resolved','Closed'])
            // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        
        // $lessthanfive = collect($lessthanfive);
        // foreach ($lessthanfive as $key) {
        //     $date = explode(' ',$key->DateCreated);
        //     // return $date[0];
        //     return Carbon::parse($date[0]);
        //     return (Carbon::parse($date[0]) < Carbon::parse(Carbon::now()));
        // }

        //
        return view('gbi', compact('filtered', 'lessthan5', 'sixto10', 'elevento15', 'sixteento20','greaterthan20'));
        // 20210628-50585
        
    }

    public function getticket()
    {
        
        $openticket =  Task::query()->select(
            'DateCreated',
            'TaskNumber',
            'TaskStatus',
            'CreatedBy',
            DB::raw('(CASE
                WHEN FormField.FieldId = \'GBIIncidentStatus\'
                THEN
                FormField.Value
                END) as IncidentStatus'
            )
        )
        ->join('form', 'taskid', 'task.id')
        ->join('formfield', 'formid', 'form.id')
        ->where('FieldId', 'GBIIncidentStatus')
        ->whereNotIN('Value', ['Resolved','Closed'])
        ->get();
//
        $subcategory =  Task::query()->select(
                'DateCreated',
                'TaskNumber',
                'CreatedBy',
                'TaskStatus',
                DB::raw('(CASE
                    WHEN FormField.FieldId = \'GBISubCategory\'
                THEN
                    FormField.Value
                    END) as Issue'
                )
            )
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('FieldId', 'GBISubCategory')
            ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->get();
        $gbi = Task::query()->select(
                'TaskNumber',
                DB::raw('(CASE
                    WHEN FormField.FieldId = \'GBISBU\'
                    THEN
                    FormField.Value
                    END) as gbisbu'
                )
            )
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->where('FieldId', 'GBISBU')
            ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->get();
            // 
        foreach ($openticket as $keys){
            // return $keys;
            // $openticket = collect($openticket);
            // if ($openticket->where('TaskNumber', $keys->TaskNumber)) {
            //     $keys->IncidentStatus = $openticket->where('TaskNumber', $keys->TaskNumber)->pluck('IncidentStatus')->first();
            // }else{
            //     $keys->IncidentStatus = '';
            // }
            $subcategory = collect($subcategory);
            if ($subcategory->where('TaskNumber', $keys->TaskNumber)) {
                $keys->Issue = $subcategory->where('TaskNumber', $keys->TaskNumber)->pluck('Issue')->first();
            }else{
                $keys->Issue = '';
            }
            //
            // return $gbi;
            $gbi = collect($gbi);
            if ($gbi->where('TaskNumber', $keys->TaskNumber)) {
                $keys->gbisbu = $gbi->where('TaskNumber', $keys->TaskNumber)->pluck('gbisbu')->first();
            }else{
                $keys->gbisbu = '';
            }
        }

        return DataTables::of($openticket)
        ->addColumn('DateCreated', function ($task){
            return Carbon::parse($task->DateCreated)->isoFormat('lll');
        })

        // ->addColumn('Resolver', function ($task){
        //     $Issue = FormField::query()->select('value')
        //         ->where('FormId', $task->formid)
        //         ->where('label', 'Resolver Group')
        //         ->first()->value;
        
        //     return $Issue;
        // })
        ->make(true);
        
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
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Office')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $date = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $dates = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"),)
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->pluck('date');
        // return $dates;
        $ofc = [];
        $plnt = [];
        $str = [];
        // return $Store;
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
        // $datas=array();
        // for ($i=0; $i < $date->count(); $i++) { 
        //     array_push($datas, 0);
        // }
        
        // foreach ($date as $index => $date) {
        //     $datas[$date] = $test[$index];
        // }
        
        // return $stor;
        // return $datas;
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
    public function monthlyticketsdata(Request $request)
    {
        // return Carbon::now()->weekOfYear.'/'.Carbon::now()->endOfweek();
        $Store = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Store')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $StoreW = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("DATENAME(week, DateCreated) as Date"))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Store')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
            ->get();
            // $year = $request->year;
            // $week = array();
            // foreach ($Store as $key) {
            //     array_push($week, $key->Date);
            // }
            // $weekcount = count($week);
            
            // $dateTime = Carbon::now();
            // $dateTime->setISODate($year, $week); 
            // $result['start_date'] = $dateTime->format('d-M-Y');
            // $dateTime->modify('+6 days'); 
            // // return $dateTime;
            // $result['end_date'] = $dateTime->format('d-M-Y'); 
            // return $result;
            // return $Store;
        $Plant = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Plant')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $PlantW = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("DATENAME(week, DateCreated) as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Plant')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
            ->get();
        $Office = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Office')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $OfficeW = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("DATENAME(week, DateCreated) as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Office')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
            ->get();
        $date = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $dateW = Task::query()
            ->select(DB::raw("DATENAME(week, DateCreated) as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->whereNotNull('Value')
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
            ->get();
        $dates = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->pluck('date');
        $datesW = Task::query()
            ->select(DB::raw("DATENAME(week, DateCreated) as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->whereNotNull('Value')
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
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
                array_push($str, '0');
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
        // $datas=array();
        // for ($i=0; $i < $date->count(); $i++) { 
        //     array_push($datas, 0);
        // }
        
        // foreach ($date as $index => $date) {
        //     $datas[$date] = $test[$index];
        // }
        
        // return $stor;
        // return $datas;
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
        $percent = array();
        array_push($percent, round(($strtotalW/$grandtotalW[4])*100,2).'%');
        array_push($percent, round(($plnttotalW/$grandtotalW[4])*100,2).'%');
        array_push($percent, round(($ofctotalW/$grandtotalW[4])*100,2).'%');

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
            'percent'=>$percent
        ];

        return response()->json($data);
    }
    public function dailyticketsdata()
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
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Office')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $date = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $dates = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->pluck('date');
        // return $Office;
        $ofc = [];
        $plnt = [];
        $str = [];
        // return $Store;
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
        // $datas=array();
        // for ($i=0; $i < $date->count(); $i++) { 
        //     array_push($datas, 0);
        // }
        
        // foreach ($date as $index => $date) {
        //     $datas[$date] = $test[$index];
        // }
        
        // return $stor;
        // return $datas;
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
    public function storetopissue()
    {

        // $test = FormField::query()->select('TaskNumber')
        //     ->join('Form', 'FormId', 'Form.Id')
        //     ->join('Task', 'TaskId', 'Task.Id')
        //     ->where('FieldId', 'GBISBU')
        //     ->whereNull('value')
        //     ->count();
        // return $test;

        $TopIssue = FormField::query()->select(
            DB::raw(
                'SUM(CASE WHEN value = \'avr\' THEN 1 ELSE 0 END) as avr'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'AX Issue\' THEN 1 ELSE 0 END) as AXIssue'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Back Office\' THEN 1 ELSE 0 END) as BackOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Biometrics\' THEN 1 ELSE 0 END) as Biometrics'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Browser\' THEN 1 ELSE 0 END) as Browser'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cabling\' THEN 1 ELSE 0 END) as Cabling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cash Drawer\' THEN 1 ELSE 0 END) as CashDrawer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CBB\' THEN 1 ELSE 0 END) as CBB'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CCTV\' THEN 1 ELSE 0 END) as CCTV'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Desktop\' THEN 1 ELSE 0 END) as Desktop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Dismantling / Re-Installation\' THEN 1 ELSE 0 END) as Dismantling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EIMS\' THEN 1 ELSE 0 END) as EIMS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Email\' THEN 1 ELSE 0 END) as Email'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EOD\' THEN 1 ELSE 0 END) as EOD'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'E-Sales\' THEN 1 ELSE 0 END) as ESales'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-MPC\' THEN 1 ELSE 0 END) as HWMPC'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-PC/POS\' THEN 1 ELSE 0 END) as HWPCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-POS\' THEN 1 ELSE 0 END) as HWPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Printer\' THEN 1 ELSE 0 END) as HWPrinter'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Server\' THEN 1 ELSE 0 END) as HWServer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Inquiry\' THEN 1 ELSE 0 END) as Inquiry'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Installation\' THEN 1 ELSE 0 END) as Installation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Internet\' THEN 1 ELSE 0 END) as Internet'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Laptop\' THEN 1 ELSE 0 END) as Laptop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Microsoft 365\' THEN 1 ELSE 0 END) as Microsoft365'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Modem\' THEN 1 ELSE 0 END) as Modem'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'MS Office\' THEN 1 ELSE 0 END) as MSOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'My HR\' THEN 1 ELSE 0 END) as MyHR'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Others\' THEN 1 ELSE 0 END) as Others'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'PC/POS\' THEN 1 ELSE 0 END) as PCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS\' THEN 1 ELSE 0 END) as POS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS Application\' THEN 1 ELSE 0 END) as POSApplication'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Price Change\' THEN 1 ELSE 0 END) as PriceChange'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Printer\' THEN 1 ELSE 0 END) as Printer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Relocation\' THEN 1 ELSE 0 END) as Relocation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Reset Password\' THEN 1 ELSE 0 END) as ResetPassword'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Router\' THEN 1 ELSE 0 END) as Router'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Sales Discrepancy\' THEN 1 ELSE 0 END) as SalesDiscrepancy'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'UPS\' THEN 1 ELSE 0 END) as UPS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'VPN\' THEN 1 ELSE 0 END) as VPN'
            )
        )
        ->where('FieldId', 'GBISubCategory')
        ->join('Form', 'FormId', 'Form.Id')
        ->join('Task', 'TaskId', 'Task.Id')
        ->get();
        $top = [
            'AVR'=>$TopIssue[0]->avr,
            'AX Issue'=>$TopIssue[0]->Axissue,
            'Back Office'=>$TopIssue[0]->Backoffice,
            'Biometrics'=>$TopIssue[0]->Biometrics,
            'Browser'=>$TopIssue[0]->Browser,
            'Cabling'=>$TopIssue[0]->Cabling,
            'Cash Drawer'=>$TopIssue[0]->CashDrawer,
            'CBB'=>$TopIssue[0]->CBB,
            'Cctv'=>$TopIssue[0]->CCTV,
            'Desktop'=>$TopIssue[0]->Desktop,
            'Dismantling / Re-Installation'=>$TopIssue[0]->Dismantling,
            'EIMS'=>$TopIssue[0]->EIMS,
            'Email'=>$TopIssue[0]->Email,
            'EOD'=>$TopIssue[0]->EOD,
            'E-Sales'=>$TopIssue[0]->ESales,
            'HW-MPC'=>$TopIssue[0]->HWMPC,
            'HW-PC/POS'=>$TopIssue[0]->HWPCPOS,
            'HW-POS"'=>$TopIssue[0]->HWPOS,
            'HW-Printer'=>$TopIssue[0]->HWPrinter,
            'HW-Server'=>$TopIssue[0]->HWServer,
            'Inquiry'=>$TopIssue[0]->Inquiry,
            'Installation'=>$TopIssue[0]->Installation,
            'Internet'=>$TopIssue[0]->Internet,
            'Laptop'=>$TopIssue[0]->Laptop,
            'Microsoft 365'=>$TopIssue[0]->Microsoft365,
            'Modem'=>$TopIssue[0]->Modem,
            'MS Office'=>$TopIssue[0]->MSOffice,
            'My HR'=>$TopIssue[0]->MyHR,
            'Others'=>$TopIssue[0]->Others,
            'PC/POS'=>$TopIssue[0]->PCPOS,
            'POS'=>$TopIssue[0]->POS,
            'POS Application'=>$TopIssue[0]->POSApplication,
            'Price Change'=>$TopIssue[0]->PriceChange,
            'Printer'=>$TopIssue[0]->Printer,
            'Relocation'=>$TopIssue[0]->Relocation,
            'Reset Password'=>$TopIssue[0]->ResetPassword,
            'Router'=>$TopIssue[0]->Router,
            'Sales Discrepancy'=>$TopIssue[0]->SalesDiscrepancy,
            'UPS'=>$TopIssue[0]->UPS,
            'VPN'=>$TopIssue[0]->VPN
        ];
       
        // store open top issue
        $storeopensubcategory =Task::query()->select('value as issue',
                'TaskNumber'
            )
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('FieldId', 'GBISubCategory')
            ->whereNotIN('TaskStatus',['Closed', 'Submitted'])
            ->get();
        
        $storeopengbisbu = Task::query()->select(
                        'TaskNumber',
                        'value as gbisbu'
                    )
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->whereNotIN('TaskStatus',['Closed', 'Submitted'])
                ->where('FieldId', 'GBISBU')
                ->where('value', 'Store')
                ->get();
        
        foreach ($storeopengbisbu as $keys) {
            $storeopensubcategory = collect($storeopensubcategory);
            if ($storeopensubcategory->where('TaskNumber', $keys->TaskNumber)) {
                $keys->subcategory = $storeopensubcategory->where('TaskNumber', $keys->TaskNumber)->pluck('issue')->first();
            }
        }
        $storeopen = $storeopengbisbu->countBy('subcategory')->all();
        arsort($storeopen);
        //

        $filtered = array_filter($top);
        arsort($filtered);
        
        $issue=[];
        foreach ($storeopen as $key => $value) {
            if (isset($filtered[$key])) {
                $closedvalue = $filtered[$key];
                if ($key) {
                    $issue[]=array('key'=>$key, 'open'=>$value, 'closed'=>$closedvalue, 'total'=>$value+$closedvalue);
                }else{
                    $issue[]=array('key'=>'Unknown', 'open'=>$value, 'closed'=>$closedvalue, 'total'=>$value+$closedvalue);
                }
            }else{
                if ($key) {
                    $issue[]=array('key'=>$key, 'open'=>$value, 'closed'=>'0', 'total'=>$value);
                }else{
                    $issue[]=array('key'=>'Unknown', 'open'=>$value, 'closed'=>'0', 'total'=>$value);
                }
            }
        }
        // data
        return DataTables::of($issue)->make(true);
    }

    public function taskdata(Request $request)
    {
            if(DB::connection()->getDatabaseName()){
                $storecode = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBIStoreCode')
                    ->select('value')
                    ->first()->value;
                $storename = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBIStoreName')
                    ->select('value')
                    ->first()->value;
                $storeaddress = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBIStoreAddress')
                    ->select('value')
                    ->first()->value;
                $ownership = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBIOwnership')
                    ->select('value')
                    ->first()->value;
                $contactperson = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBIContactPerson')
                    ->select('value')
                    ->first()->value;
                $contactnumber = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBIContactNumber')
                    ->select('value')
                    ->first()->value;
                $email = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBIEmailAddress')
                    ->select('value')
                    ->first()->value;
                $problemreported = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBIProblemReported')
                    ->select('value')
                    ->first()->value;
                $location = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBILocation')
                    ->select('value')
                    ->first()->value;
                $rootcause = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBIRootCause')
                    ->select('value')
                    ->first()->value;
                $latestnotes = Task::query()
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->where('TaskNumber', $request->TaskNumber)
                    ->where('FieldId', 'GBILatestNotes')
                    ->select('value')
                    ->first()->value;
                // $responsetime = Task::query()
                //     ->join('form', 'taskid', 'task.id')
                //     ->join('formfield', 'formid', 'form.id')
                //     ->where('TaskNumber', $request->TaskNumber)
                //     ->where('label', 'Response Time')
                //     ->select('value')
                //     ->first()->value;
                // $createdby = Task::query()
                //     ->join('form', 'taskid', 'task.id')
                //     ->join('formfield', 'formid', 'form.id')
                //     ->where('TaskNumber', $request->TaskNumber)
                //     ->where('label', 'Created By')
                //     ->select('value')
                //     ->first()->value;
                
                // $problemcategory = Task::query()
                //     ->join('form', 'taskid', 'task.id')
                //     ->join('formfield', 'formid', 'form.id')
                //     ->where('TaskNumber', $request->TaskNumber)
                //     ->where('label', 'Problem Category')
                //     ->select('value')
                //     ->first()->value;
                // $subcategory = Task::query()
                //     ->join('form', 'taskid', 'task.id')
                //     ->join('formfield', 'formid', 'form.id')
                //     ->where('TaskNumber', $request->TaskNumber)
                //     ->where('label', 'Sub Category')
                //     ->select('value')
                //     ->first()->value;
                // $machinemodel = Task::query()
                //     ->join('form', 'taskid', 'task.id')
                //     ->join('formfield', 'formid', 'form.id')
                //     ->where('TaskNumber', $request->TaskNumber)
                //     ->where('label', 'Machine Model')
                //     ->select('value')
                //     ->first()->value;
                // $incidentstatus = Task::query()
                //     ->join('form', 'taskid', 'task.id')
                //     ->join('formfield', 'formid', 'form.id')
                //     ->where('TaskNumber', $request->TaskNumber)
                //     ->where('label', 'Incident Status')
                //     ->select('value')
                //     ->first()->value;

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
                        // 'Response_Time'=>$responsetime,
                        // 'Created_By'=>$createdby,
                        'Problem_Reported'=>$problemreported,
                        // 'Problem_Category'=>$problemcategory,
                        // 'Sub_Category'=>$subcategory,
                        // 'Machine_Model'=>$machinemodel,
                        'Root_Cause'=>$rootcause,
                        // 'Incident_Status'=>$incidentstatus
                    ]
                );
                $taskdata = DB::table('task')->select('label','value')
                    ->where('TaskNumber', 'LIKE', '%20210626-50365')
                    ->wherein('label', 
                        [
                            'Store Code',
                            'Store Name',
                            'Store Address',
                            'Region',
                            'Province / Municipality',
                            'Contact Person',
                            'Contact Number',
                            'Email Address',
                            'Response Time',
                            'Created By',
                            'Problem Reported',
                            'Problem Category',
                            'Sub Category',
                            'Machine Model',
                            'Root Cause',
                            'Incident Status'
                        ]
                    )
                    ->join('form', 'taskid', 'task.id')
                    ->join('formfield', 'formid', 'form.id')
                    ->get();
            }
        return DataTables::of($task)->make(true);
        
    }
}
// 
// $avr = FormField::query()
//             // ->select('FieldId')
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'avr')
//             ->count();
//             // return $avr;
//             $axissue = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'AX Issue')
//             ->groupBy('value')
//             ->count();
//             $Backoffice = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Back Office')
//             ->groupBy('value')
//             ->count();
//             $biometrics = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Biometrics')
//             ->groupBy('value')
//             ->count();
//             $Browser = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Browser')
//             ->groupBy('value')
//             ->count();
//             $Cabling = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Cabling')
//             ->groupBy('value')
//             ->count();
//             $Cashdrawer = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Cash Drawer')
//             ->groupBy('value')
//             ->count();
//             $CBB = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'CBB')
//             ->groupBy('value')
//             ->count();
//             $Cctv = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'CCTV')
//             ->groupBy('value')
//             ->count();
//             $Desktop = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Desktop')
//             ->groupBy('value')
//             ->count();
//             $Dismantling = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Dismantling / Re-Installation')
//             ->groupBy('value')
//             ->count();
//             $EIMS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'EIMS')
//             ->groupBy('value')
//             ->count();
//             $Email = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Email')
//             ->groupBy('value')
//             ->count();
//             $EOD = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'EOD')
//             ->groupBy('value')
//             ->count();
//             $ESales = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'E-Sales')
//             ->groupBy('value')
//             ->count();
//             $HWMPC = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HW-MPC')
//             ->groupBy('value')
//             ->count();
//             $HWPCPOS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HW-PC/POS')
//             ->groupBy('value')
//             ->count();
//             $HWPOS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HWPOS')
//             ->groupBy('value')
//             ->count();
//             $HWPrinter = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HW-Printer')
//             ->groupBy('value')
//             ->count();
//             $HWServer = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HW-Server')
//             ->groupBy('value')
//             ->count();
//             $Inquiry = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Inquiry')
//             ->groupBy('value')
//             ->count();
//             $Installation = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Installation')
//             ->groupBy('value')
//             ->count();
//             $Internet = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Internet')
//             ->groupBy('value')
//             ->count();
//             $Laptop = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Laptop')
//             ->groupBy('value')
//             ->count();
//             $Microsoft365 = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Microsoft 365')
//             ->groupBy('value')
//             ->count();
//             $Modem = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Modem')
//             ->groupBy('value')
//             ->count();
//             $MSOffice = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'MS Office')
//             ->groupBy('value')
//             ->count();
//             $MyHr = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'My Hr')
//             ->groupBy('value')
//             ->count();
//             $Others = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Others')
//             ->groupBy('value')
//             ->count();
//             $PCPOS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'PC/POS')
//             ->groupBy('value')
//             ->count();
//             $POS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'POS')
//             ->groupBy('value')
//             ->count();
//             $POSApplication = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'POS Application')
//             ->groupBy('value')
//             ->count();
//             $pricechange = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Price Change')
//             ->groupBy('value')
//             ->count();
//             $Printer = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Printer')
//             ->groupBy('value')
//             ->count();
//             $Relocation = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Relocation')
//             ->groupBy('value')
//             ->count();
//             $ResetPassword = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Reset Password')
//             ->groupBy('value')
//             ->count();
//             $Router = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Router')
//             ->groupBy('value')
//             ->count();
//             $SalesDiscrepancy = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Sales Discrepancy')
//             ->groupBy('value')
//             ->count();
//             $UPS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'UPS')
//             ->groupBy('value')
//             ->count();
//             $VPN = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'VPN')
//             ->groupBy('value')
//             ->count();
//             $top = [
//                 'AVR'=>$avr,
//                 'AX Issue'=>$axissue,
//                 'Back Office'=>$Backoffice,
//                 'Biometrics'=>$biometrics,
//                 'Browser'=>$Browser,
//                 'Cabling'=>$Cabling,
//                 'Cash Drawer'=>$Cashdrawer,
//                 'CBB'=>$CBB,
//                 'Cctv'=>$Cctv,
//                 'Desktop'=>$Desktop,
//                 'Dismantling / Re-Installation'=>$Dismantling,
//                 'EIMS'=>$EIMS,
//                 'Email'=>$Email,
//                 'EOD'=>$EOD,
//                 'E-Sales'=>$ESales,
//                 'HW-MPS'=>$HWMPC,
//                 'HW-PC/POS'=>$HWPCPOS,
//                 'HW-POS"'=>$HWPOS,
//                 'HW-Printer'=>$HWPrinter,
//                 'HW-Server'=>$HWServer,
//                 'Inquiry'=>$Inquiry,
//                 'Installation'=>$Installation,
//                 'Internet'=>$Internet,
//                 'Laptop'=>$Laptop,
//                 'Microsoft 365'=>$Microsoft365,
//                 'Modem'=>$Modem,
//                 'MS Office'=>$MSOffice,
//                 'My HR'=>$MyHr,
//                 'Others'=>$Others,
//                 'PC/POS'=>$PCPOS,
//                 'POS'=>$POS,
//                 'POS Application'=>$POSApplication,
//                 'Price Change'=>$pricechange,
//                 'Printer'=>$Printer,
//                 'Relocation'=>$Relocation,
//                 'Reset Password'=>$ResetPassword,
//                 'Router'=>$Router,
//                 'Sales Discrepancy'=>$SalesDiscrepancy,
//                 'UPS'=>$UPS,
//                 'VPN'=>$VPN
//             ];