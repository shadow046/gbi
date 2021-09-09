<?php

namespace App\Exports;

use App\Models\Task;
use App\Models\Ticket;
use App\Models\FormField;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use DB;

class TopIssue implements FromArray,ShouldAutoSize,WithColumnWidths,WithStyles,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $year;
    protected $month;
    protected $monthname;
    protected $store;
    protected $plant;
    protected $office;

    public function __construct($year, $month, $monthname, $store, $plant, $office) {
        $this->year = $year;
        $this->month = $month;
        $this->monthname = $monthname;
        $this->store = $store;
        $this->plant = $plant;
        $this->office = $office;
    }

    public function title(): string
    {
        return 'Top Issue';
    }
    public function columnWidths(): array
    {
        return [
            'D' => 40,            
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $styleHeader = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('B4')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(18);
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getFont()->setSize(16);
        $sheet->getStyle('A4:B4')->applyFromArray($styleHeader);
        $sheet->getStyle('D4')->getFont()->setBold(true);
        $sheet->getStyle('E4')->getFont()->setBold(true);
        $sheet->getStyle('D3')->getFont()->setBold(true);
        $sheet->getStyle('D3')->getFont()->setSize(16);
        $sheet->getStyle('D4:E4')->applyFromArray($styleHeader);
        $sheet->getStyle('G4')->getFont()->setBold(true);
        $sheet->getStyle('H4')->getFont()->setBold(true);
        $sheet->getStyle('G3')->getFont()->setBold(true);
        $sheet->getStyle('G3')->getFont()->setSize(16);
        $sheet->getStyle('G4:H4')->applyFromArray($styleHeader);
        if ($this->store) {
            $storecounts = count($this->store);
            for ($i=0; $i < $storecounts; $i++) { 
                $b = $i+5;
                $sheet->getStyle('A'.$b.':B'.$b)->applyFromArray($styleHeader);
            }
        }
        if ($this->plant) {
            $plantcounts = count($this->plant);
            for ($i=0; $i < $plantcounts; $i++) { 
                $b = $i+5;
                $sheet->getStyle('D'.$b.':E'.$b)->applyFromArray($styleHeader);
            }
        }
        if ($this->office) {
            $officecounts = count($this->office);
            for ($i=0; $i < $officecounts; $i++) { 
                $b = $i+5;
                $sheet->getStyle('G'.$b.':H'.$b)->applyFromArray($styleHeader);
            }
        }
        
        // $sheet->setBorder(4, 'thin');
    }

    public function array(): array
    {
        $stores = $this->store;
        $plant = $this->plant;
        $office = $this->office;
        $issue = array();
        foreach ($stores as $key => $value) {
            if (isset($plant[$key])) {
                if (isset($office[$key])) {
                    $issue[] = [$value->SubCategory,$value->count,'',$plant[$key]->SubCategory,$plant[$key]->count,'',$office[$key]->SubCategory,$office[$key]->count];
                }else{
                    $issue[] = [$value->SubCategory,$value->count,'',$plant[$key]->SubCategory,$plant[$key]->count];
                }
            }else{
                if (isset($office[$key])) {
                    $issue[] = [$value->SubCategory,$value->count,'','','',$office[$key]->SubCategory,$office[$key]->count];
                }else{
                    $issue[] = [$value->SubCategory,$value->count];
                }
            }
        }
        
        return [
            ['TOP ISSUE - '.$this->year.' '.$this->monthname],
            [''],
            ['STORE TOP ISSUE','','','PLANT TOP ISSUE','','','OFFICE TOP ISSUE'],
            ['SubCategory','Count','','SubCategory','Count','','SubCategory','Count'],
            $issue,
            ['']
        ];
    }
}
