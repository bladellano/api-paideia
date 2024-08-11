<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassAnnualPerformanceReport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithMapping, WithProperties, WithCustomStartCell, WithDrawings, WithTitle
{
    protected $data;
    protected $header;
    protected $lastCell;
    protected $cellsToRotate;
    protected $cellsToRed;
    protected $index = 0;
    protected $teamName;
    protected $writeFromDimension = '4';
    protected $resultOnly;
    protected $title;

    public function __construct(\Illuminate\Support\Collection $grades, string $teamName, string $title, $resultOnly = FALSE)
    {
        $data = \App\Helpers\GenerateReportAnnualPerformance::execute($grades, $resultOnly);
        $this->data = $data;
        $this->title = $title;
        $this->header = $data[0];
        $this->teamName = $teamName;
        $this->resultOnly = $resultOnly;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('~~Paideia Educacional~~');
        $drawing->setPath(public_path('/logo.png'));
        $drawing->setHeight(74);

        if(!$this->resultOnly)
            $drawing->setCoordinates('AG1');
        else
            $drawing->setCoordinates('L1');

        return $drawing;
    }

    public function title(): string
    {
        return mb_strtoupper($this->title);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->data;
    }

    public function startCell(): string
    {
        return 'A4'; // Define a célula inicial
    }

    public function headings(): array
    {
        return $this->header;
    }

    public function properties(): array
    {
        return [
            'creator'        => 'bladellano@gmail.com',
            'lastModifiedBy' => 'Caio Silva',
            'title'          => 'Relatório de aproveitamento anual.',
            'description'    => 'Latest Invoices',
            'keywords'       => 'relatório,paideia,educacional,planilha,anual,notas,escola,educação',
            'category'       => 'Relatório',
            'manager'        => 'Caio Silva',
            'company'        => 'Paideia Educacional',
        ];
    }

    public function map($item): array
    {
        $this->index++;

        if ($this->index == 2) {

            return [
                ...$item,
                'FREQUÊNCIA',
                'RESULTADO',
            ];
        }

        return [
            ...$item,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $disciplines = array_filter($this->header);
        $c = 3; //C
        $i = $c;
        foreach ($disciplines as $d) {

            $COL1 = \App\Helpers\GenerateReportFinancial::getColumnLetter(!$this->resultOnly ? $c : $i);
            $c++;
            $c++;
            $COL3 = \App\Helpers\GenerateReportFinancial::getColumnLetter(!$this->resultOnly ? $c : $i);

            $this->cellsToRotate[] = $COL1;
            $this->cellsToRed[] = $COL3;

            if (!$this->resultOnly)
                $sheet->mergeCells("{$COL1}{$this->writeFromDimension}:{$COL3}{$this->writeFromDimension}");

            $c++;
            $i++;
        }

        $this->lastCell = $COL3;

        $sheet->getStyle("A{$this->writeFromDimension}:{$COL3}{$this->writeFromDimension}")->getAlignment()->setHorizontal('center');

        // Centralizar header
        $sheet->getStyle("A1:{$COL3}2")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A1:{$COL3}2")->getAlignment()->setVertical('center');
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

                $concernable = $event->getConcernable();

                $qtdColumns = count($concernable->header);

                $frequencyAndResult = 2;
                $letter = \App\Helpers\GenerateReportFinancial::getColumnLetter($qtdColumns + $frequencyAndResult);

                // Mesclar as duas primeras dimensoes.
                $event->sheet->mergeCells("A1:{$letter}1", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A2:{$letter}2", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('A2', "RESOLUÇÃO CEE/PA N° 90 DE 27 DE MARÇO DE 2023\nINEP - 15176266\nPAIDEIA EDUCACIONAL\nRELATÓRIO DE APROVEITAMENTO ANUAL REFERENTE AO ANO DE " . date('Y'));

                $event->sheet->setCellValue('B3', "TURMA : {$this->teamName}");
            },
            AfterSheet::class => function (AfterSheet $event) use ($aStylesHeader, $stylesAllCells) {

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
                $event->sheet->getRowDimension($this->writeFromDimension)->setRowHeight(100);

                $event->sheet->getRowDimension('1')->setRowHeight(60);
                $event->sheet->getRowDimension('2')->setRowHeight(60);

                $sizeHeader = count($event->getConcernable()->header);
                $letterFinal = \App\Helpers\GenerateReportFinancial::getColumnLetter($sizeHeader + 2);

                /** APLICAÇÃO DOS ESTILOS */
                $dimension = $this->writeFromDimension;

                $quantityDatePlusTwoHeads = count($this->data) + 3;

                $event->sheet->getStyle("A{$dimension}:{$letterFinal}" . ($dimension + 1))->applyFromArray($aStylesHeader);
                $event->sheet->getStyle("A{$dimension}:{$letterFinal}" . $quantityDatePlusTwoHeads)->applyFromArray($stylesAllCells);

                foreach ($this->cellsToRotate as $letter)
                    $event->sheet->getDelegate()->getStyle("{$letter}{$dimension}")->getAlignment()->setTextRotation(90);

                $dimension++;

                foreach ($this->cellsToRed as $letter) {

                    $event->sheet->getStyle("{$letter}{$dimension}")->applyFromArray([
                        'font' => [
                            'color' => [
                                'rgb' => 'FF0000'
                            ]
                        ]
                    ]);
                }

                $event->sheet->getStyle("A{$this->writeFromDimension}:{$letterFinal}{$this->writeFromDimension}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => "D9D9D9",
                        ],
                    ],
                ]);

                $event->sheet->getStyle("A{$dimension}:{$letterFinal}{$dimension}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => "C5E0B4",
                        ],
                    ],
                ]);
            }

        ];
    }
}
