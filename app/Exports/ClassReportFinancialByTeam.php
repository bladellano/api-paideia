<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassReportFinancialByTeam implements FromCollection, WithMapping, WithEvents, WithCustomStartCell, WithColumnWidths
{

    private $financialReport;
    private $quantity;
    private $index = 0;
    private $year;
    private $teamName;

    private const COLORS = [
        'SUCCESS' => 'D1E7DD',
        'DANGER' => 'F8D7DA',
        'DEFAULT' => 'EEEEEE',
    ];

    public function __construct(
        array $financialReport,
        int $year,
        string $teamName,
    ) {
        $this->financialReport = $financialReport;
        $this->quantity = count($this->financialReport);
        $this->year = $year;
        $this->teamName = $teamName;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $c = collect($this->financialReport);

        return $c;
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 40,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 10,
            'N' => 10,
        ];
    }

    public function map($item): array
    {

        $this->index++;

        return [
            $this->index,
            mb_strtoupper($item[0]),
            empty($item[1]) ? "--" : $item[1]['value'],
            empty($item[2]) ? "--" : $item[2]['value'],
            empty($item[3]) ? "--" : $item[3]['value'],
            empty($item[4]) ? "--" : $item[4]['value'],
            empty($item[5]) ? "--" : $item[5]['value'],
            empty($item[6]) ? "--" : $item[6]['value'],
            empty($item[7]) ? "--" : $item[7]['value'],
            empty($item[8]) ? "--" : $item[8]['value'],
            empty($item[9]) ? "--" : $item[9]['value'],
            empty($item[10]) ? "--" : $item[10]['value'],
            empty($item[11]) ? "--" : $item[11]['value'],
            empty($item[12]) ? "--" : $item[12]['value'],
        ];
    }

    public function registerEvents(): array
    {
        $aStylesHeader = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];

        $stylesAllCells = [
            'borders' => [
                'allBorders' => ['borderStyle' => 'hair', 'color' => ['rgb' => '333333']]
            ]
        ];

        return [
            BeforeSheet::class => function (BeforeSheet $event) {

                /** SUPER HEADER */
                $event->sheet->mergeCells('A1:N1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue('A1', "RELATÓRIO FINANCEIRO POR TURMA\n " . $this->teamName);

                $event->sheet->setCellValue('B2', 'ALUNOS');

                $event->sheet->setCellValue('C2', 'JAN' . "/{$this->year}");
                $event->sheet->setCellValue('D2', 'FEV' . "/{$this->year}");
                $event->sheet->setCellValue('E2', 'MAR' . "/{$this->year}");
                $event->sheet->setCellValue('F2', 'ABR' . "/{$this->year}");
                $event->sheet->setCellValue('G2', 'MAI' . "/{$this->year}");
                $event->sheet->setCellValue('H2', 'JUN' . "/{$this->year}");
                $event->sheet->setCellValue('I2', 'JUL' . "/{$this->year}");
                $event->sheet->setCellValue('J2', 'AGO' . "/{$this->year}");
                $event->sheet->setCellValue('K2', 'SET' . "/{$this->year}");
                $event->sheet->setCellValue('L2', 'OUT' . "/{$this->year}");
                $event->sheet->setCellValue('M2', 'NOV' . "/{$this->year}");
                $event->sheet->setCellValue('N2', 'DEZ' . "/{$this->year}");
            },
            AfterSheet::class => function (AfterSheet $event) use ($aStylesHeader, $stylesAllCells) {

                //? Configuracao do papel
                $event->sheet
                    ->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                $event->sheet->getPageMargins()->setTop(0.10);
                $event->sheet->getPageMargins()->setRight(0.10);
                $event->sheet->getPageMargins()->setLeft(0.10);
                $event->sheet->getPageMargins()->setBottom(0.10);
                $event->sheet->getPageMargins()->setHeader(0.10);
                $event->sheet->getPageMargins()->setFooter(0.10);

                $event->sheet->getPageSetup()->setFitToPage(true);

                //? Ajusta a altura de determinada dimensao (linha).
                $event->sheet->getRowDimension('1')->setRowHeight(38);

                $concernable = $event->getConcernable();
                $report = $concernable->financialReport;

                /** APLICAÇÃO DOS ESTILOS */
                $event->sheet->getStyle('A1:N2')->applyFromArray($aStylesHeader);

                /** BORDAS/COTORNOS */
                $headerHeigth = 2;

                $event->sheet->getStyle("A1:N" . ($this->quantity + $headerHeigth))->applyFromArray($stylesAllCells);

                /** COLORIR */
                $report = array_values($report);

                $cellsMonths = [
                    1 => 'C',
                    2 => 'D',
                    3 => 'E',
                    4 => 'F',
                    4 => 'F',
                    5 => 'G',
                    6 => 'H',
                    7 => 'I',
                    8 => 'J',
                    9 => 'K',
                    10 => 'L',
                    11 => 'M',
                    12 => 'N',
                ];

                $report = array_map(function ($item) {
                    unset($item[0]);
                    return $item;
                }, $report);

                foreach ($report as $key => $row) {

                    foreach ($row as $m => $v) {

                        if ($v == 0)
                            continue;

                        $color = self::COLORS['DEFAULT'];

                        if ($v['paid'])
                            $color = self::COLORS['SUCCESS'];

                        if (!$v['paid'] && $v['overdue'])
                            $color = self::COLORS['DANGER'];

                        $number = $key + 3; //! 3 = Quantidade de linhas que forma o header sem os alunos.

                        $range = "{$cellsMonths[$m]}{$number}:{$cellsMonths[$m]}{$number}";

                        $event->sheet->getStyle($range)->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => $color,
                                ],
                            ],
                        ]);
                    }
                }
            }

        ];
    }
}
