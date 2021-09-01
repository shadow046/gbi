<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\FormField;
use App\Models\Form;
use App\Models\User;
use App\Models\Ticket;
use DB;

class GetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $getticket = $this->updatedata('GBISBU');
        return $getticket;
    }

    public function getticket()
    {
        $tasks = Task::query()
            ->join('Form', 'Taskid', 'Task.Id')
            ->select('Task.id as TaskId', 'Task.TaskNumber', 'Task.DateCreated', 'Task.CreatedBy', 'Form.Id as FormId')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(100))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(2))
            ->get();
        foreach ($tasks as $task) {
            $ticket = Ticket::where('TaskNumber', $task->TaskNumber)->first();
            if (!$ticket) {
                Ticket::create(
                    [
                        'TaskId'=>$task->TaskId,
                        'FormId'=>$task->FormId,
                        'TaskNumber'=>$task->TaskNumber,
                        'DateCreated'=>strtok($task->DateCreated,'.'),
                        'CreatedBy'=>$task->CreatedBy
                    ]
                );
            }
        }
    }

    public function getDaily()
    {
        $tasks = Task::query()
            ->join('Form', 'Taskid', 'Task.Id')
            ->select('Task.id as TaskId', 'Task.TaskNumber', 'Task.DateCreated', 'Task.CreatedBy', 'Form.Id as FormId')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(2))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(1))
            ->get();
        foreach ($tasks as $task) {
            $ticket = Ticket::where('TaskNumber', $task->TaskNumber)->first();
            if (!$ticket) {
                Ticket::create(
                    [
                        'TaskId'=>$task->TaskId,
                        'FormId'=>$task->FormId,
                        'TaskNumber'=>$task->TaskNumber,
                        'DateCreated'=>strtok($task->DateCreated,'.'),
                        'CreatedBy'=>$task->CreatedBy
                    ]
                );
            }
        }
        return 'done';
    }
    public function updatedata($field)
    {
        $tickets = Ticket::query()->select('FormId')->get();
        foreach ($tickets as $ticket) {
            $values = FormField::query()->select('value')
                ->where('FormId', $ticket->FormId)
                ->where('FieldId', $field)
                ->get();
            if (!Schema::hasColumn('Ticket', $field)){
                Schema::table('Ticket', function($table) use ($field){
                    $table->string($field)->collation('utf8_general_ci');
                });
            }
            foreach ($values as $value) {
                Ticket::where('FormId', $ticket->FormId)->update([$field=>$value->value]);
            }
        }
        return 'ok';        
    }
    public function getdata($field)
    {
        $tasks = Task::query()
            ->join('Form', 'Taskid', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->select('Task.id', 'Task.TaskNumber', 'formfield.value', 'Task.DateCreated', 'Task.CreatedBy')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->where('Fieldid', $field)
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(100))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(2))
            ->get();
        
        if (Schema::hasColumn('Ticket', $field)){
            Schema::table('Ticket', function($table){
                $table->string($field)->collation('utf8_general_ci');
            });
            foreach ($tasks as $task) {
                $ticket = Ticket::where('TaskNumber', $task->TaskNumber)->first();
                if ($ticket) {
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
