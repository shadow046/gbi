<?php

namespace App\Exports\Sheets;

use App\Exports\Daily;
use App\Exports\Weekly;
use App\Exports\TopIssue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;

class DataExports implements WithMultipleSheets
{
    use Exportable;

    protected $year;
    protected $month;
    protected $monthname;
    protected $store;
    protected $plant;

    public function __construct($year, $month, $monthname, $store, $plant) {
        $this->year = $year;
        $this->month = $month;
        $this->monthname = $monthname;
        $this->store = $store;
        $this->plant = $plant;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new Daily($this->year, $this->month, $this->monthname);
        $sheets[] = new Weekly($this->year, $this->month, $this->monthname);
        $sheets[] = new TopIssue($this->year, $this->month, $this->monthname, $this->store, $this->plant);
        return $sheets;
    }

}
