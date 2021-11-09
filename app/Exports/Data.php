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
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use Carbon\Carbon;
use DB;

class Data implements FromArray,ShouldAutoSize,WithColumnWidths,WithStyles,WithTitle,WithCharts
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $datefrom;
    protected $dateto;
    protected $tick;

    public function __construct($datefrom, $dateto) {
        $this->datefrom = $datefrom;
        $this->dateto = $dateto;
        $this->tick = Ticket::query()
            ->select(
                DB::raw("monthname(DateCreated) as Month"),
                DB::raw('SUM(CASE WHEN SBU = \'Store\' THEN 1 ELSE 0 END) as Store'),
                DB::raw('SUM(CASE WHEN SBU = \'Plant\' THEN 1 ELSE 0 END) as Plant'),
                DB::raw('SUM(CASE WHEN SBU = \'Office\' THEN 1 ELSE 0 END) as Office'),
                DB::raw('max(Day(DateCreated)) as DateCount'),
                DB::raw('max(DateCreated) as DateCreated'),
            )
            ->join('Data', 'Code', 'StoreCode')
            ->where('TaskStatus','!=','Closed')
            ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(5))
            ->whereMonth('DateCreated', '<', Carbon::now())
            ->orderBy('DateCreated', 'asc')
            ->groupBy('Month')
            ->get();
    }

    public function charts()
    {
        // $i = 4;
        // $dataSeriesLabels = [];
        // foreach ($this->tick as $ticket) {
        //     $dataSeriesLabels[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'TotalTickets!$A$'.$i, null, 1);
        //     $i++;
        // }3
        $count = count($this->tick)+3;
        $bardataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'TotalTickets!$B$3', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'TotalTickets!$C$3', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'TotalTickets!$D$3', null, 1)
        ];
        $barxAxisTickValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'TotalTickets!$A$4:$A$'.$count, null, 4)];
        $bardataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'TotalTickets!$B$'.$count, null,4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'TotalTickets!$C$'.$count, null,4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'TotalTickets!$D$'.$count, null,4)
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$6:$AE$6', null,4),
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$7:$AE$7', null,4),
        ];
        $barlayout = new Layout();
        $barlayout->setShowVal(true);
        
        // Or show pct
        // $barlayout->setShowPercent(true);
        $barseries = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_CLUSTERED, // plotGrouping
            range(0, count($bardataSeriesValues) - 1), // plotOrder
            $bardataSeriesLabels, // plotLabel
            $barxAxisTickValues, // plotCategory
            $bardataSeriesValues        // plotValues
        );
        //	Set the series in the plot area
        $barplotArea = new PlotArea($barlayout, [$barseries]);
        //	Set the chart legend
        $barlegend = new Legend(Legend::POSITION_BOTTOM, null, false);
        $bartitle = new Title('Total Tickets Chart');

        //	Create the chart
        $barchart = new Chart(
            'Total Tickets', // name
            $bartitle, // title
            $barlegend, // legend
            $barplotArea, // plotArea
            true // plotVisibleOnly
            // 0, // displayBlanksAs
            // null, // xAxisLabel
            // NULL,  // yAxisLabel
        );

        $barchart->setTopLeftPosition('B'.count($this->tick)+7);
        $barchart->setBottomRightPosition('O30');

        $piedataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'TotalTickets!$B$3', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'TotalTickets!$C$3', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'TotalTickets!$D$3', null, 1)
        ];
        $piexAxisTickValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'TotalTickets!$B$3:$D$3', null, 4)];
        $piedataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'TotalTickets!$B$'.($count+1).':D$'.$count+1, null,4)
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'TotalTickets!$C$'.$count+1, null,4),
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'TotalTickets!$D$'.$count+1, null,4)
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$6:$AE$6', null,4),
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Daily!$B$7:$AE$7', null,4),
        ];
       
        $pieseries = new DataSeries(
            DataSeries::TYPE_PIECHART, // plotType
            DataSeries::GROUPING_CLUSTERED, // plotGrouping
            range(0, count($piedataSeriesValues) - 1), // plotOrder
            [], // plotLabel
            $piexAxisTickValues, // plotCategory
            $piedataSeriesValues        // plotValues
        );
       
        $pielayout = new Layout();
        $pielayout->setShowPercent(true);
        //	Set the series in the plot area
        $pieplotArea = new PlotArea($pielayout, [$pieseries]);
        //	Set the chart legend
        $pielegend = new Legend(Legend::POSITION_BOTTOM, null, true);
        $pietitle = new Title('');
        $piechart = new Chart(
            'Total Tickets', // name
            $pietitle, // title
            $pielegend, // legend
            $pieplotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            // null, // xAxisLabel
            // null// null  // yAxisLabel
        );
        $piechart->setTopLeftPosition('L1');
        $piechart->setBottomRightPosition('O10');

        // $chart1 = new Chart(
        //     'chart',
        //     new Title('Weekly Ticket Chart'),
        //     new Legend(),
        //     new PlotArea(null, [
        //         new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values) - 1), $labels, $categories, $values)
        //     ])
        // );
        $charts = [$barchart, $piechart];
        return $charts;
    }

    public function title(): string
    {
        return 'TotalTickets';
    }
    public function columnWidths(): array
    {
        return [
            'B' => 7,            
            'C' => 7,            
            'D' => 7,            
            'E' => 7,            
            'F' => 15,     
            'I' => 18,            
            'J' => 18,            
            'H' => 18,            

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
        
        $StoreTopIssues = Ticket::query()->select('SubCategory', DB::raw('Count(SubCategory) as Total'))
            ->join('Data','Code','StoreCode')
            ->where('SBU', 'Store')
            ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
            ->whereMonth('DateCreated', '<=', Carbon::now())
            ->groupBy('SubCategory')
            ->get();
        $Stop = collect([]);
        foreach ($StoreTopIssues as $issue) {
            if ($issue->SubCategory != Null) {
                $Stop->offsetSet($issue->SubCategory,$issue->Total);
            }
        }
        $StoreTop = $Stop->sortDesc();
        $OfficeTopIssues = Ticket::query()->select('SubCategory', DB::raw('Count(SubCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('SBU', 'Office')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
                ->groupBy('SubCategory')
                ->get();
            $Otop = collect([]);
            foreach ($OfficeTopIssues as $issue) {
                if ($issue->SubCategory != Null) {
                    $Otop->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
        $OfficeTop = $Otop->sortDesc();
        $PlantTopIssues = Ticket::query()->select('SubCategory', DB::raw('Count(SubCategory) as Total'))
                ->join('Data','Code','StoreCode')
                ->where('SBU', 'Plant')
                ->whereMonth('DateCreated', '>=', Carbon::now()->subMonths(3))
                ->whereMonth('DateCreated', '<', Carbon::now())
                ->groupBy('SubCategory')
                ->get();
            $Ptop = collect([]);
            foreach ($PlantTopIssues as $issue) {
                if ($issue->SubCategory != Null) {
                    $Ptop->offsetSet($issue->SubCategory,$issue->Total);
                }
            }
        $PlantTop = $Ptop->sortDesc();
        $sheet->getStyle('F1')->getFont()->setBold(true);
        $sheet->getStyle('F1')->getFont()->setSize(16);
        $sheet->getStyle('H3')->getFont()->setBold(true);
        $sheet->getStyle('H3')->getFont()->setSize(10);
        $sheet->getStyle('H4')->getFont()->setBold(true);
        $sheet->getStyle('H4')->getFont()->setSize(10);
        $sheet->getStyle('J4')->getFont()->setBold(true);
        $sheet->getStyle('J4')->getFont()->setSize(10);
        $sheet->getStyle('I4')->getFont()->setBold(true);
        $sheet->getStyle('I4')->getFont()->setSize(10);
        $sheet->getStyle('A3:F3')->getFont()->setBold(true);
        $sheet->getStyle('A3:F3')->applyFromArray($styleHeader);
        $sheet->getStyle('H3:J7')->applyFromArray($styleHeader);
        $sheet->mergeCells('H3:J3');
        $i = 4;
        $store = 0;
        $plant = 0;
        $office = 0;
        $total = 0;
        foreach ($this->tick as $ticket) {
            $store = $store+$ticket->Store;
            $plant = $plant+$ticket->Plant;
            $office = $office+$ticket->Office;
            $total = $total+$ticket->Store+$ticket->Plant+$ticket->Office;
            $sheet->getStyle('A'.$i.':F'.$i)->applyFromArray($styleHeader);
            $sheet->setCellValue('A'.$i, $ticket->Month);
            $sheet->setCellValue('B'.$i, $ticket->Store);
            $sheet->setCellValue('C'.$i, $ticket->Office);
            $sheet->setCellValue('D'.$i, $ticket->Plant);
            $sheet->setCellValue('E'.$i, $ticket->Store+$ticket->Plant+$ticket->Office);
            $sheet->setCellValue('F'.$i, round(($ticket->Store+$ticket->Plant+$ticket->Office)/$ticket->DateCount));
            $i++;
        }
        $sheet->setCellValue('A'.$i, 'GRAND TOTAL');
        $sheet->setCellValue('B'.$i, $store);
        $sheet->setCellValue('C'.$i, $office);
        $sheet->setCellValue('D'.$i, $plant);
        $sheet->setCellValue('E'.$i, $total);
        $sheet->getStyle('A'.$i.':E'.$i)->applyFromArray($styleHeader);
        $sheet->setCellValue('H3', 'TOP 3 ISSUE BY CATEGORY');
        $sheet->setCellValue('H4', 'STORE');
        $sheet->setCellValue('I4', 'OFFICE');
        $sheet->setCellValue('J4', 'PLANT');
        $i = 5;
        foreach ($StoreTop as $store => $value) {
            if ($i > 4 && $i < 8) {
                $sheet->setCellValue('H'.$i, $store);
                $i++;
            }
        }
        $i = 5;
        foreach ($OfficeTop as $office => $value) {
            if ($i > 4 && $i < 8) {
                $sheet->setCellValue('I'.$i, $office);
                $i++;
            }
        }
        $i = 5;
        foreach ($PlantTop as $plant => $value) {
            if ($i > 4 && $i < 8) {
                $sheet->setCellValue('J'.$i, $plant);
                $i++;
            }
        }
        
        // $sheet->setBorder(4, 'thin');
    }
    public function array(): array
    {
        return [
            ['','','','','','TOTAL TICKETS'],
            [''],
            ['MONTH','STORE','OFFICE','PLANT', 'TOTAL','DAILY AVERAGE']
        ];
    }
}
