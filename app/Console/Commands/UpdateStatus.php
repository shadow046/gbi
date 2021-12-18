<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StoreData;
use App\Models\Data;
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
class UpdateStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:statu1s';

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
        $task = 'GBI-20211106-69619';
        $fields = FormField::select('FieldId')->where('FieldId', 'LIKE', 'GBI%')->groupBy('FieldId')->get();
        Ticket::where('TaskNumber', $task)->update(['TaskStatus'=>'Submitted']);
        $taskid = Ticket::where('TaskNumber', $task)->first();
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
        $formid = Form::where('TaskId', $taskid->TaskId)->first()->Id;
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
                    Ticket::where('TaskId', $taskid->TaskId)
                    ->update([
                        substr($field->FieldId,3)=>$value->Value,
                    ]);
                }
            }
        }
    }
}
