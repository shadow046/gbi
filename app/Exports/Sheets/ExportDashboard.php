<?php

namespace App\Exports\Sheets;

use App\Exports\Data;
use App\Exports\Weekly;
use App\Exports\TopIssue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;

class ExportDashboard implements WithMultipleSheets
{
    use Exportable;

    protected $datefrom;
    protected $dateto;
   

    public function __construct($datefrom, $dateto) {
        $this->datefrom = $datefrom;
        $this->dateto = $dateto;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new Data($this->datefrom,$this->dateto);
        // $sheets[] = new Weekly($this->year, $this->month, $this->monthname);
        // $sheets[] = new TopIssue($this->year, $this->month, $this->monthname, $this->store, $this->plant, $this->office);
        return $sheets;
    }

}
