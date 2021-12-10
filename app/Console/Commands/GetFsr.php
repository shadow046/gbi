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
class GetFsr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:fsr';

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
        
    }
}
