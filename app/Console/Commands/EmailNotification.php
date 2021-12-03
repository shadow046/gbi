<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Models\StatusLog;
use Illuminate\Database\Schema\Blueprint;
use App\Models\PStatusLog;
use App\Models\Task;
use App\Models\Form;
use Illuminate\Support\Facades\Schema;
use App\Models\FormField;
use Mail;
use Config;
class EmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:ticket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a ticket number via email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get gbi fields
        $fields = FormField::select('FieldId')->where('FieldId', 'LIKE', 'GBI%')->groupBy('FieldId')->get();
        // get latest ticket taskid from new database
        // $fields = array ("FieldId":"GBIActionTaken","FieldId":"GBIActionTaken1","FieldId":"GBIAdditionalStoreDetails","FieldId":"GBICallType","FieldId":"GBIContactNumber","FieldId":"GBIContactPerson","FieldId":"GBICreatedBy","FieldId":"GBIDateandTimeAttended","FieldId":"GBIDateandTimeFinished","FieldId":"GBIDateandTimeForwared","FieldId":"GBIEmailAddress","FieldId":"GBIEndUserOfficer","FieldId":"GBIFlag","FieldId":"GBIForParts","FieldId":"GBIFSRNumber","FieldId":"GBIIncidentStatus","FieldId":"GBILatestNotes","FieldId":"GBILocation","FieldId":"GBILocationCode","FieldId":"GBIMachineModel","FieldId":"GBIMachineModel1","FieldId":"GBIMachineType","FieldId":"GBIMachineType1","FieldId":"GBIOthers","FieldId":"GBIOthersSubCategory","FieldId":"GBIOwnership","FieldId":"GBIPartDetail","FieldId":"GBIPossibleCause","FieldId":"GBIPriority","FieldId":"GBIProblemCategory","FieldId":"GBIProblemReported","FieldId":"GBIProvinceMunicipality","FieldId":"GBIRegion","FieldId":"GBIReportMode","FieldId":"GBIResolutionCategory","FieldId":"GBIResolvedBy","FieldId":"GBIResolverGroup","FieldId":"GBIResponseTime","FieldId":"GBIRootCause","FieldId":"GBISBU","FieldId":"GBISerialNumber","FieldId":"GBISerialNumber1","FieldId":"GBIStatus","FieldId":"GBIStoreAddress","FieldId":"GBIStoreCode","FieldId":"GBIStoreName","FieldId":"GBIStoreType","FieldId":"GBISubCategory","FieldId":"GBISubRegion","FieldId":"GBISystemEngineer","FieldId":"GBITicketType","FieldId":"GBITimeArrived","FieldId":"GBITimeEnded","FieldId":"GBITimeFinished","FieldId":"GBITimeStarted","FieldId":"GBITypeofResolution","FieldId":"GBITypeofResolution1");
        // $loop = 'yes';
        // do
        $ticket = Ticket::orderBy('TaskId','desc')->first()->TaskId;
        //check new ticket from powerform
        $task = Task::select('Id', 'TaskNumber', 'DateCreated', 'TaskStatus', 'CreatedBy')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->where('Id', '>', $ticket)
            ->orderBy('Id','asc')
            ->first();
        $exclude = array('GBIEmailAddress',
            'GBILocationCode',
            'GBIOwnership',
            'GBIProvinceMunicipality',
            'GBIRegion',
            'GBISBU',
            'GBIStoreAddress',
            'GBIStoreName',
            'GBIStoreType',
            'GBISubRegion',
            'GBICreatedBy'
        );
        if ($task) {
            if (!Ticket::where('TaskId',$task->Id)->first()) {
                $newticket = new Ticket([
                    'TaskId'=>$task->Id,
                    'TaskNumber'=>$task->TaskNumber,
                    'DateCreated'=>strstr($task->DateCreated,'.',true),
                    'TaskStatus'=>$task->TaskStatus,
                    'CreatedBy'=>$task->CreatedBy,
                ]);
                $newticket->Save();
                if ($newticket) {
                    $formid = Form::where('TaskId', $task->Id)->first()->Id;
                    Ticket::where('TaskId', $task->Id)->update(['FormId'=>$formid]);
                    foreach ($fields as $field) {
                        if (!in_array($field->FieldId,$exclude)) {
                            if (!Schema::hasColumn('Ticket', substr($field->FieldId,3))) //check the column
                            {
                                $newColumnType = 'string';
                                $newColumnName = substr($field->FieldId,3);
                                Schema::table('Ticket', function (Blueprint $table) use ($newColumnType, $newColumnName) {
                                    $table->$newColumnType($newColumnName)->nullable();
                                });
                            }
                            $value = FormField::where('FormId',$formid)->where('FieldId', $field->FieldId)->first();
                            if ($value) {
                                Ticket::where('TaskId', $task->Id)
                                ->update([
                                    substr($field->FieldId,3)=>$value->Value,
                                ]);
                            }
                        }
                    }
                }
            }
        }
        $stat = StatusLog::orderBy('id','desc')->first()->Id;

        $Pstat = PStatusLog::query()
            ->select('Id', 'TaskNumber', 'Timestamp', 'TaskStatus', 'TaskId')
            ->where('Id', '>', $stat)
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->orderBy('id','asc')
            ->first();
            
        if ($Pstat) {
            $newlog = new StatusLog([
                'Id' => $Pstat->Id,
                'TaskNumber' => $Pstat->TaskNumber,
                'Timestamp' => $Pstat->Timestamp,
                'TaskStatus' => $Pstat->TaskStatus,
                'TaskId' => $Pstat->TaskId
            ]);
            $newlog->Save();
            $formid = Form::where('TaskId', $Pstat->TaskId)->first()->Id;
            Ticket::where('TaskId', $Pstat->TaskId)->update(['TaskStatus'=>$Pstat->TaskStatus]);
            foreach ($fields as $field) {
                if (!in_array($field->FieldId,$exclude)) {
                    if (!Schema::hasColumn('Ticket', substr($field->FieldId,3))) //check the column
                    {
                        $newColumnType = 'string';
                        $newColumnName = substr($field->FieldId,3);
                        Schema::table('Ticket', function (Blueprint $table) use ($newColumnType, $newColumnName) {
                            $table->$newColumnType($newColumnName)->nullable();
                        });
                    }
                    $value = FormField::where('FormId',$formid)->where('FieldId', $field->FieldId)->first();
                    if ($value) {
                        Ticket::where('TaskId', $Pstat->TaskId)
                        ->update([
                            substr($field->FieldId,3)=>$value->Value,
                        ]);
                    }
                }
            }
            // dd($Pstat->TaskId);
            $notified = Ticket::where('TaskId',$Pstat->TaskId)->join('Data', 'Code', 'StoreCode')->first();
            // dd($notified);
            $config = array(
                'driver'     => \config('mailconf.driver'),
                'host'       => \config('mailconf.host'),
                'port'       => \config('mailconf.port'),
                'from'       => \config('mailconf.from'),
                'encryption' => \config('mailconf.encryption'),
                'username'   => \config('mailconf.username'),
                'password'   => \config('mailconf.password')
            );
            Config::set('mail', $config);
            if ($notified) {
                if ($Pstat->TaskStatus == "Submitted" && $notified->Email) {
                    Mail::send('closed_ticket', [
                        'StoreCode'=>$notified->StoreCode,
                        'StoreName'=>$notified->Store_Name,
                        'Ticket'=>$notified->TaskNumber,
                        'Problem'=>$notified->ProblemReported
                    ],
                    function( $message) use ($notified){ 
                        $message->to($notified->Email, 'Goldilocks '.$notified->Store_Name)->subject('RESOLVED - '.$notified->TaskNumber); 
                        $message->from('noreply@apsoft.com.ph', 'NO REPLY');
                        $message->bcc(['kdgonzales@ideaserv.com.ph','jolopez@ideaserv.com.ph','tony.tan@goldilocks.com','mira.decastro@goldilocks.com']);
                                // $message->cc(['tony.tan@goldilocks.com','mira.decastro@goldilocks.com']);
                        // $message->bcc(['kdgonzales@ideaserv.com.ph','jolopez@ideaserv.com.ph']);
                    });
                    Ticket::where('TaskNumber', $notified->TaskNumber)->update(['Notified'=>'submitted']);
                    $this->info('Successfully sent emailss');
                }else{
                    if (!$notified->Notified && $notified->Email) {
                        $this->info('sending emails');
                        Mail::send('new_ticket', [
                            'StoreCode'=>$notified->StoreCode,
                            'StoreName'=>$notified->Store_Name,
                            'Ticket'=>$notified->TaskNumber,
                            'Problem'=>$notified->ProblemReported
                        ],
                        function( $message) use ($notified){ 
                            $message->to($notified->Email, 'Goldilocks '.$notified->Store_Name)->subject('NEW TICKET - '.$notified->TaskNumber); 
                            $message->from('noreply@apsoft.com.ph', 'NO REPLY');
                            // $message->cc(['tony.tan@goldilocks.com','mira.decastro@goldilocks.com']);
                            $message->bcc(['kdgonzales@ideaserv.com.ph','jolopez@ideaserv.com.ph','tony.tan@goldilocks.com','mira.decastro@goldilocks.com']);
                        // $message->bcc(['kdgonzales@ideaserv.com.ph','jolopez@ideaserv.com.ph']);
                        });
                        Ticket::where('TaskNumber', $notified->TaskNumber)->update(['Notified'=>'created']);
                        $this->info('Successfully sent emails');
                    }
                }
                $this->info('Successfully sent email');
            }
        }
        $this->info('Successfully Updated');
    }
}
