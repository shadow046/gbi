<?php

namespace App\Exports;

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
use Carbon\Carbon;
use DB;

class Weekly implements FromArray,ShouldAutoSize,WithColumnWidths,WithStyles,WithTitle,WithCharts
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
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Weekly!$D$4', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Weekly!$E$4', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Weekly!$F$4', null, 1)
        ];
        $xAxisTickValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Weekly!$C$5:$C$9', null, 4)];
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Weekly!$D$5:$D$9', null,4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Weekly!$E$5:$E$9', null,4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Weekly!$F$5:$F$9', null,4),
        ];
        $dataSeriesValues[2]->setLineWidth(60000);
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_CLUSTERED, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );
        //	Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);
        //	Set the chart legend
        $legend = new Legend(Legend::POSITION_TOP, null, false);
        $title = new Title('Weekly Ticket Chart');

        //	Create the chart
        $chart = new Chart(
            'Weekly', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true
        );

        $chart->setTopLeftPosition('I2');
        $chart->setBottomRightPosition('O25');

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
        return 'Weekly';
    }
    public function columnWidths(): array
    {
        return [
            'D' => 8,            
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
        $sheet->getStyle('D1')->getFont()->setBold(true);
        $sheet->getStyle('D1')->getFont()->setSize(16);
        $sheet->getStyle('D3')->getFont()->setBold(true);
        $sheet->getStyle('D3')->getFont()->setSize(16);
        $sheet->getStyle('C4:G4')->applyFromArray($styleHeader);
        $sheet->getStyle('C4:G4')->getFont()->setBold(true);
        $sheet->getStyle('C5:G5')->applyFromArray($styleHeader);
        $sheet->getStyle('C6:G6')->applyFromArray($styleHeader);
        $sheet->getStyle('C7:G7')->applyFromArray($styleHeader);
        $sheet->getStyle('C8:G8')->applyFromArray($styleHeader);
        $sheet->getStyle('C9:G9')->applyFromArray($styleHeader);
        $sheet->getStyle('C10:G10')->applyFromArray($styleHeader);
        $sheet->getStyle('C11:G11')->applyFromArray($styleHeader);

        // $sheet->setBorder(4, 'thin');
    }



    public function array(): array
    {
        $weekstart = Carbon::createFromDate($this->year, $this->month, 1)->startofweek(Carbon::SUNDAY)->toDateString();
        $weekend = Carbon::createFromDate($this->year, $this->month, 30)->endofweek(Carbon::SATURDAY)->toDateString();
        $week1 = Carbon::parse($weekstart)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(6)->formatLocalized('%b %d');
        $week2 = Carbon::parse($weekstart)->addDays(7)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(13)->formatLocalized('%b %d');
        $week3 = Carbon::parse($weekstart)->addDays(14)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(20)->formatLocalized('%b %d');
        $week4 = Carbon::parse($weekstart)->addDays(21)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(27)->formatLocalized('%b %d');
        $week5 = Carbon::parse($weekstart)->addDays(28)->formatLocalized('%b %d').' - '.Carbon::parse($weekstart)->addDays(34)->formatLocalized('%b %d');
        $StoreW = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("WEEK(DateCreated) as Date"))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Store')
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('Date')
            ->get();
        $PlantW = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("WEEK(DateCreated) as Date"))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Plant')
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('Date')
            ->get();
        $OfficeW = Ticket::query()
            ->select(DB::raw('COUNT(TaskId) as count'), DB::raw("WEEK(DateCreated) as Date"))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Office')
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('Date')
            ->get();
        $dateW = Ticket::query()
            ->select(DB::raw("WEEK(DateCreated) as date"))
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('date')
            ->get();
        $datesW = Ticket::query()
            ->select(DB::raw("WEEK(DateCreated) as date"))
            ->whereNotIn('TaskStatus',['Closed'])
            ->whereDate('DateCreated','>=',$weekstart)
            ->whereDate('DateCreated','<=',$weekend)
            ->groupBy('date')
            ->pluck('date');
        $ofcW = [];
        $plntW = [];
        $strW = [];
        foreach ($dateW as $key) {
            if ($OfficeW->where('Date', $key->date)->first()) {
                array_push($ofcW, $OfficeW->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($ofcW, '0');
            }
            if ($PlantW->where('Date', $key->date)->first()) {
                array_push($plntW, $PlantW->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($plntW, '0');
            }
            if ($StoreW->where('Date', $key->date)->first()) {
                array_push($strW, $StoreW->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($strW, '0');
            }
        }
        $plntW = collect($plntW);
        $strW = collect($strW);
        $ofcW = collect($ofcW);

        $strtotalW = $strW->sum(function($item) {
            return $item;
        });
        $plnttotalW = $plntW->sum(function($item) {
            return $item;
        });
        $ofctotalW = $ofcW->sum(function($item) {
            return $item;
        });
        $grandtotalW = array();
        foreach ($strW as $key => $value) {
            array_push($grandtotalW, $strW[$key]+$plntW[$key]+$ofcW[$key]);
        }
        array_push($grandtotalW, $strtotalW+$plnttotalW+$ofctotalW);
        $percent = array();
        if (count($grandtotalW) < 6) {
            array_push($percent, round(($strtotalW/$grandtotalW[4])*100,2).'%');
            array_push($percent, round(($plnttotalW/$grandtotalW[4])*100,2).'%');
            array_push($percent, round(($ofctotalW/$grandtotalW[4])*100,2).'%');
        }else{
            array_push($percent, round(($strtotalW/$grandtotalW[5])*100,2).'%');
            array_push($percent, round(($plnttotalW/$grandtotalW[5])*100,2).'%');
            array_push($percent, round(($ofctotalW/$grandtotalW[5])*100,2).'%');
        }
        $percent = collect($percent);
        $weekslabel = array();
        for ($i=1; $i <= count($datesW); $i++) { 
           array_push($weekslabel, 'Week '.$i);
        }
        $weekslabel=collect($weekslabel);
        
        // $dates->prepend('GBI SBU')->push('GRAND TOTAL');
        // $str->prepend('STORE')->push(number_format($strtotal));
        // $plnt->prepend('PLANT')->push(number_format($plnttotal));
        // $ofc->prepend('OFFICE')->push(number_format($ofctotal));
        // $grandtotal= (collect($grandtotal))->prepend('Grand Total');
        return [
            ['','','','WEEKLY VIEW '.$this->year.' '.$this->monthname],
            [''],
            ['','','','WEEKLY TICKETS'],
            ['','','','STORE','PLANT','OFFICE','GRAND TOTAL'],
            ['','',$week1, $strW[0],$plntW[0],$ofcW[0],$grandtotalW[0]],
            ['','',$week2, $strW[1],$plntW[1],$ofcW[1],$grandtotalW[1]],
            ['','',$week3, $strW[2],$plntW[2],$ofcW[2],$grandtotalW[2]],
            ['','',$week4, $strW[3],$plntW[3],$ofcW[3],$grandtotalW[3]],
            ['','',$week5, $strW[4],$plntW[4],$ofcW[4],$grandtotalW[4]],
            ['','','Grand Total', $strtotalW,$plnttotalW,$ofctotalW,$grandtotalW[5]],
            ['','','Percentage',round(($strtotalW/$grandtotalW[5])*100,2),round(($plnttotalW/$grandtotalW[5])*100,2),round(($ofctotalW/$grandtotalW[5])*100,2),'100%'],
        ];
    }
}
