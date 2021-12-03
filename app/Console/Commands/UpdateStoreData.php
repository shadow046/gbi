<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StoreData;
use App\Models\Data;
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
class UpdateStoreData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:data';

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
        
        $dataid = Data::orderBy('RefDataValueId','desc')->first()->RefDataValueId;
        $storedata = StoreData::select('Id', 'Value', 'RowId')
            // ->where('Id', '>', $dataid)
            ->where('RefDataColumnId', '45046')
            ->orderBy('Id', 'asc')
            ->get();
        foreach ($storedata as $store) {
            if (!Data::where('Code', $store->Value)->first()) {
                $stores = new Data([
                    'Code'=>$store->Value,
                    'RefDataValueId'=>$store->Id,
                    'RowId'=>$store->RowId
                ]);
                $stores->Save();
                Data::where('Code', $store->Value)->update([
                    'Store_Name' => StoreData::select('Value')->where('RefDataColumnId','45047')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value,
                    'Address' => StoreData::select('Value')->where('RefDataColumnId','45048')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value,
                    'Ownership' => StoreData::select('Value')->where('RefDataColumnId','45049')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value,
                    'Type' => StoreData::select('Value')->where('RefDataColumnId','45050')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value,
                    'Region' => StoreData::select('Value')->where('RefDataColumnId','45051')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value,
                    'SubRegion' => StoreData::select('Value')->where('RefDataColumnId','45052')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value,
                    'Municipality' => StoreData::select('Value')->where('RefDataColumnId','45053')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value,
                    'Cluster' => StoreData::select('Value')->where('RefDataColumnId','45054')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value,
                    'Email' => StoreData::select('Value')->where('RefDataColumnId','45055')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value,
                    'SBU' => StoreData::select('Value')->where('RefDataColumnId','45056')->where('RowId', 'LIKE','%'.$store->RowId.'%')->first()->Value
                ]);
                $this->info('Updated Done');
            }
            
        }
    }
}
