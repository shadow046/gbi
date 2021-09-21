<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Carbon\Carbon;
use DB;

class RawDataOpen implements FromArray,WithHeadings,WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            ['Date/Time',
            'Task Number',
            'Ticket Age',
            'Store Code',
            'Store Name',
            'SBU',
            'Ownership',
            'Call Type',
            'Problem Category',
            'Sub Category',
            'Incident Status',
            'Report Mode',
            'Contact Person',
            'Contact Number',
            'Problem Reported'
        ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 18,  
            'C' => 10,
            'D' => 10,
            'E' => 38,
            'F' => 6,
            'G' => 17,
            'H' => 15,
            'I' => 20,
            'J' => 15,
            'K' => 39,
            'L' => 12,
            'M' => 22,
            'N' => 24,
            'O' => 67,

        ];
    }
    public function array(): array
    {
        $open = Ticket::query()
            ->select(
                DB::raw("DATE_FORMAT(DateCreated, '%m/%d/%Y %H:%m:%s') as Date"),
                'TaskNumber',
                DB::raw("DATEDIFF(NOW(),DateCreated) as Age"),
                'StoreCode',
                'Store_Name',
                'SBU',
                'Ownership',
                'CallType',
                'ProblemCategory',
                'SubCategory',
                'IncidentStatus',
                'ReportMode',
                'ContactPerson',
                'ContactNumber',
                'ProblemReported'
            )
            ->join('Data', 'Code', 'StoreCode')
            ->where('IncidentStatus','!=','Resolved')
            ->where('TaskStatus','!=','Submitted')
            ->get();
        return [$open];
    }
}
