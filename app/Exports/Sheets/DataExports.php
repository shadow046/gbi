<?php

namespace App\Exports\Sheets;

use App\Exports\DataExport;
use App\Exports\DataExport1;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;

class DataExports implements WithMultipleSheets
{
    use Exportable;

    protected $year;
    protected $month;
    protected $monthname;

    public function __construct($year, $month, $monthname) {
        $this->year = $year;
        $this->month = $month;
        $this->monthname = $monthname;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new DataExport($this->year, $this->month, $this->monthname);
        $sheets[] = new DataExport1($this->year, $this->month, $this->monthname);
        return $sheets;
    }

}
