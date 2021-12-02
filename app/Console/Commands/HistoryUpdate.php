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
