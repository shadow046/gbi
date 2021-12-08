<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Models\History;
use App\Models\Remark;
use Illuminate\Database\Schema\Blueprint;
use App\Models\PStatusLog;
use App\Models\Task;
use App\Models\Form;
use Illuminate\Support\Facades\Schema;
use App\Models\FormField;
use Mail;
use Config;
class HistoryUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remark:history';

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
        $loop = 'yes';
        do {
            $HistoryId = History::orderBy('Id','desc')->first()->Id;
            $History = Task::query()
                ->select('TaskAuditLog.*')
                ->join('TaskAuditLog', 'Task.Id', 'TaskId')
                ->where('TaskAuditLog.Id', '>', $HistoryId)
                ->where('TaskNumber', 'Like', 'GBI%')
                ->orderBy('TaskAuditLog.Id', 'asc')
                ->first();
            if ($History) {
                if (!History::query()->where('Id',$History->Id)->first()) {
                    $newhistory = new History([
                        'Id'=>$History->Id,
                        'TaskId'=>$History->TaskId,
                        'Label'=>$History->Label,
                        'Snapshotvalue'=>$History->Snapshotvalue,
                        'UpdatedValue'=>$History->UpdatedValue,
                        'Source'=>$History->Source,
                        'Timestamp'=>$History->Timestamp,
                        'Message'=>$History->Message,
                        'AuditLevel'=>$History->AuditLevel,
                        'Status'=>$History->Status
                    ]);
                    $newhistory->Save();
                }
            }
            $RemarksId = Remark::orderBy('Id','desc')->first()->Id;
            $Remarks = Task::query()
                ->select('Remark.*')
                ->join('Remark', 'Task.Id', 'TaskId')
                ->where('Remark.Id', '>', $RemarksId)
                ->where('TaskNumber', 'Like', 'GBI%')
                ->orderBy('Remark.Id', 'asc')
                ->first();
            $this->info($Remarks);
            if ($Remarks) {
                if (!Remark::query()->where('Id',$Remarks->Id)->first()) {
                    $newRemarks = new Remark([
                        'Id'=>$Remarks->Id,
                        'TaskId'=>$Remarks->TaskId,
                        'Author'=>$Remarks->Author,
                        'Timestamp'=>$Remarks->Timestamp,
                        'Message'=>$Remarks->Message,
                        'Status'=>$Remarks->Status
                    ]);
                    $newRemarks->Save();
                    if ($newRemarks) {
                        $notified = Ticket::where('TaskId',$Remarks->TaskId)->join('Data', 'Code', 'StoreCode')->first();
                        if ($Remarks->Message || $Remarks->Message != "" || $Remarks->Message != "Done." || $Remarks->Message != "Done" || $Remarks->Message != $notified->ProblemReported) {
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
                            Mail::send('update_ticket', [
                                'StoreCode'=>$notified->StoreCode,
                                'StoreName'=>$notified->Store_Name,
                                'Ticket'=>$notified->TaskNumber,
                                'Problem'=>$notified->ProblemReported,
                                'Remarks'=>$Remarks->Message
                            ],
                            function( $message) use ($notified){ 
                                $message->to($notified->Email, 'Goldilocks '.$notified->Store_Name)->subject('UPDATED - '.$notified->TaskNumber); 
                                $message->from(env('MAIL_FROM_ADDRESS'), 'IT SERVICE DESK');
                                // $message->bcc(['germelo.entrada@goldilocks.com','emorej046@gmail.com','demesac@apsoft.com.ph','kdgonzales@ideaserv.com.ph','jolopez@ideaserv.com.ph','tony.tan@goldilocks.com','mira.decastro@goldilocks.com']);
                                $message->bcc(['emorej046@gmail.com','jolopez@ideaserv.com.ph']);
                            });
                            Remark::where('Id', $Remarks->Id)->whereNull('Status')->update(['Status'=>'done']);
                            $this->info('Update sent');
                        }

                    }
                }
            }
            $RemarksId = Remark::orderBy('Id','desc')->first()->Id;
            $Remarks = Task::query()
                ->select('Remark.*')
                ->join('Remark', 'Task.Id', 'TaskId')
                ->where('Remark.Id', '>', $RemarksId)
                ->where('TaskNumber', 'Like', 'GBI%')
                ->orderBy('Remark.Id', 'asc')
                ->first();
            $HistoryId = History::orderBy('Id','desc')->first()->Id;
            $History = Task::query()
                ->select('TaskAuditLog.*')
                ->join('TaskAuditLog', 'Task.Id', 'TaskId')
                ->where('TaskAuditLog.Id', '>', $HistoryId)
                ->where('TaskNumber', 'Like', 'GBI%')
                ->orderBy('TaskAuditLog.Id', 'asc')
                ->first();
            if (!$History && !$Remarks) {
                $loop = 'no';
            }
        }while ($loop == 'yes');
        $this->info('Updated Done');
    }
}
