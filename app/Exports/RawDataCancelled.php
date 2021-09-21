<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Carbon\Carbon;
use DB;

class RawDataCancelled implements FromArray,WithHeadings,WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            ['Date/Time',
            'Task Number',
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
            'D' => 38,
            'E' => 6,
            'F' => 17,
            'G' => 15,
            'H' => 20,
            'I' => 15,
            'J' => 39,
            'K' => 12,
            'L' => 22,
            'M' => 24,
            'N' => 67,

        ];
    }
    public function array(): array
    {
        $cancelled = Ticket::query()
            ->select(
                DB::raw("DATE_FORMAT(DateCreated, '%m/%d/%Y %H:%m:%s') as Date"),
                'TaskNumber',
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
            ->where('Status', 'Cancelled')
            ->get();
        return [$cancelled];
    }
}
