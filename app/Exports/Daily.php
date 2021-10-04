<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\Task;
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
use Carbon\Carbon;
use DB;

class Daily implements FromArray,ShouldAutoSize,WithColumnWidths,WithStyles,WithTitle,WithCharts
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $year;
    protected $month;
    protected $monthname;

    public function __construct($year, $month, $monthname) {
        $this->year = $year;
        $this->month = $month;
        $this->monthname = $monthname;
    }

    public function charts()
    {
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Daily!$A$5', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Daily!$A$6', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Daily!$A$7', null, 1)
        ];
        if (Carbon::parse($this->year.'-'.$this->month.'-'.'1')->daysInMonth == 31) {
            $xAxisTickValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Daily!$B$4:$AF$4', null, 4)];
            $dataSeriesValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$5:$AF$5', null,4),
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$6:$AF$6', null,4),
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$7:$AF$7', null,4),
            ];
        }else if (Carbon::parse($this->year.'-'.$this->month.'-'.'1')->daysInMonth == 30) {
            $xAxisTickValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Daily!$B$4:$AE$4', null, 4)];
            $dataSeriesValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$5:$AE$5', null,4),
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$6:$AE$6', null,4),
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$7:$AE$7', null,4),
            ];
        }
        
        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );
        //	Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);
        //	Set the chart legend
        $legend = new Legend(Legend::POSITION_TOP, null, false);
        $title = new Title('Daily Ticket Chart');

        //	Create the chart
        $chart = new Chart(
            'Daily', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true // plotVisibleOnly
            // 0, // displayBlanksAs
            // null, // xAxisLabel
            // NULL,  // yAxisLabel
        );

        $chart->setTopLeftPosition('B10');
        $chart->setBottomRightPosition('AF25');

        // $chart1 = new Chart(
        //     'chart',
        //     new Title('Weekly Ticket Chart'),
        //     new Legend(),
        //     new PlotArea(null, [
        //         new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values) - 1), $labels, $categories, $values)
        //     ])
        // );
        return $chart;
    }

    public function title(): string
    {
        return 'Daily';
    }
    public function columnWidths(): array
    {
        return [
            'K' => 3,            
            'N' => 3,            
        ];
    }
    // public function styles(Worksheet $sheet)
    // {
    //     $sheet->setBorder(4, 'thin');
    //     return [
    //         // // Style the first row as bold text.
    //         4    => ['font' => ['bold' => true]],

    //         // // Styling a specific cell by coordinate.
    //         // 'Q1' => ['font' => ['Bold' => true]],

    //         // Styling an entire column.
    //         'K1'  => ['font' => ['size' => 16, 'bold' => true]]
    //     ];
    // }
    
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
        $sheet->getStyle('K1')->getFont()->setBold(true);
        $sheet->getStyle('K1')->getFont()->setSize(16);
        $sheet->getStyle('N3')->getFont()->setBold(true);
        $sheet->getStyle('N3')->getFont()->setSize(16);
        $sheet->getStyle('A4:AG4')->applyFromArray($styleHeader);
        $sheet->getStyle('A4:AG4')->getFont()->setBold(true);
        $sheet->getStyle('A5:AG5')->applyFromArray($styleHeader);
        $sheet->getStyle('A6:AG6')->applyFromArray($styleHeader);
        $sheet->getStyle('A7:AG7')->applyFromArray($styleHeader);
        $sheet->getStyle('A8:AG8')->applyFromArray($styleHeader);

        // $sheet->setBorder(4, 'thin');
    }
    public function array(): array
    {
        $Store = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("DATE_FORMAT(DateCreated, '%m/%d/%Y') as Date"))
            ->join('Data','Code', 'StoreCode')
            ->where('SBU', 'Store')
            ->whereYear('DateCreated', $this->year)
            ->whereMonth('DateCreated', $this->month)
            ->groupBy('Date')
            ->get();
        $Plant = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("DATE_FORMAT(DateCreated, '%m/%d/%Y') as Date"))
            ->join('Data','Code', 'StoreCode')
            ->where('SBU', 'Plant')
            ->whereYear('DateCreated', $this->year)
            ->whereMonth('DateCreated', $this->month)
            ->groupBy('Date')
            ->get();
            // ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            // // ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            // ->join('Form', 'TaskId', 'Task.Id')
            // ->join('FormField', 'FormId', 'Form.Id')
            // ->where('FieldId', 'GBISBU')
            // ->where('Value', 'Plant')
            // ->whereYear('DateCreated', $this->year)
            // ->whereMonth('DateCreated', $this->month)
            // ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            // ->get();
        $Office = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("DATE_FORMAT(DateCreated, '%m/%d/%Y') as Date"))
            ->join('Data','Code', 'StoreCode')
            ->where('SBU', 'Office')
            ->whereYear('DateCreated', $this->year)
            ->whereMonth('DateCreated', $this->month)
            ->groupBy('Date')
            ->get();
            // Task::query()
            // ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            // // ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            // ->join('Form', 'TaskId', 'Task.Id')
            // ->join('FormField', 'FormId', 'Form.Id')
            // // ->orderBy('DateCreated', 'Asc')
            // ->where('FieldId', 'GBISBU')
            // ->where('Value', 'Office')
            // ->whereYear('DateCreated', $this->year)
            // ->whereMonth('DateCreated', $this->month)
            // ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            // ->get();
        $date = Ticket::query()
            ->select(DB::raw("DATE_FORMAT(DateCreated, '%m/%d/%Y') as date"))
            // ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Data','Code', 'StoreCode')
            // ->orderBy('DateCreated', 'Asc')
            ->whereYear('DateCreated', $this->year)
            ->whereMonth('DateCreated', $this->month)
            ->groupBy('date')
            ->get();
        $dates = Ticket::query()
            ->select(DB::raw("DATE_FORMAT(DateCreated, '%d') as date"))
            // ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Data','Code', 'StoreCode')
            // ->orderBy('DateCreated', 'Asc')
            ->whereYear('DateCreated', $this->year)
            ->whereMonth('DateCreated', $this->month)
            ->groupBy('date')
            ->pluck('date');
        $ofc = [];
        $plnt = [];
        $str = [];
        foreach ($date as $key) {
            if ($Office->where('Date', $key->date)->first()) {
                array_push($ofc, $Office->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($ofc, '0');
            }
            if ($Plant->where('Date', $key->date)->first()) {
                array_push($plnt, $Plant->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($plnt, '0');
            }
            if ($Store->where('Date', $key->date)->first()) {
                array_push($str, $Store->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($str, '0');
            }
        }

        $plnt = collect($plnt);
        $str = collect($str);
        $ofc = collect($ofc);
        $strtotal = $str->sum(function($item) {
            return $item;
        });
        $plnttotal = $plnt->sum(function($item) {
            return $item;
        });
        $ofctotal = $ofc->sum(function($item) {
            return $item;
        });
        $grandtotal = array();
        foreach ($str as $key => $value) {
            array_push($grandtotal, $str[$key]+$plnt[$key]+$ofc[$key]);
        }
        array_push($grandtotal, number_format($strtotal+$plnttotal+$ofctotal));
        $dates->prepend('GBI SBU')->push('GRAND TOTAL');
        $str->prepend('STORE')->push(number_format($strtotal));
        $plnt->prepend('PLANT')->push(number_format($plnttotal));
        $ofc->prepend('OFFICE')->push(number_format($ofctotal));
        $grandtotal= (collect($grandtotal))->prepend('Grand Total');
        return [
            ['','','','','','','','','','','MONTHLY VIEW '.$this->year.' '.$this->monthname],
            [''],
            ['','','','','','','','','','','','','','DAILY TICKETS'],
            $dates,
            $str,
            $plnt,
            $ofc,
            $grandtotal
        ];
    }
}
