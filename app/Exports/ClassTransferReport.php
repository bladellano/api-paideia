<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ClassTransferReport implements FromCollection, WithColumnWidths, WithCustomStartCell, WithEvents, WithDrawings
{
    private $report;
    private $qtColumns;
    private $quantity;
    private $start_date;
    private $end_date;
    private $course;
    private $team_name;

    public function __construct(array $report, $team, string $start_date, string $end_date)
    {
        $this->report = $report;
        $this->qtColumns = $report ? count($report[0]) : 0;
        $this->quantity = count($report);
        $this->start_date = $start_date;
        $this->end_date = $end_date;

        $students = $team->getStudentsByTeam($team->id);
        $this->course = $students[0]->course;
        $this->team_name = $team->name;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $report = $this->report;

        $header = array_keys($report[0]);
        $header = array_map('mb_strtoupper', $header);
        array_unshift($report, $header);

        return collect($report);
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('~~Paideia Educacional~~');
        $drawing->setPath(public_path('/logo.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 34,
            'D' => 20,
            'E' => 26,
            'F' => 16,
            'G' => 12,
            'H' => 16,
            'I' => 10,
            'J' => 14,
            'K' => 14,
            'L' => 24,
        ];
    }

    public function registerEvents(): array
    {
        $aStylesHeader = [
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];

        $textWhite = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        ];

        $stylesAllCells = [
            'borders' => [
                'allBorders' => ['borderStyle' => 'hair', 'color' => ['rgb' => '333333']]
            ]
        ];

        return [
            BeforeSheet::class => function (BeforeSheet $event) {

                $concernable = $event->getConcernable();

                $event->sheet->mergeCells("A1:L1", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue('A1', "RELATÓRIO DE CONTAS RECEBIDAS / CURSO: {$concernable->course} / TURMA: {$concernable->team_name}");

                $event->sheet->mergeCells("A2:L2", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue('A2', "PERÍODO DE PAGAMENTO: {$concernable->start_date} à {$concernable->end_date}");

                $event->sheet->mergeCells("A3:L3", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue('A3', "DATA DA GERAÇÃO: " . date('d/m/Y H:m:s'));

                $event->sheet->mergeCells("A4:L4", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue('A4', "TOTAL DE TÍTULOS: {$concernable->quantity}");
            },
            AfterSheet::class => function (AfterSheet $event) use ($aStylesHeader, $stylesAllCells, $textWhite) {

                $concernable = $event->getConcernable();

                //? Configuração do papel
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

                $event->sheet->getStyle("A1:L5")->applyFromArray($aStylesHeader);

                $event->sheet->getStyle("A6:L6")->applyFromArray($aStylesHeader);

                $i = $concernable->quantity + 6;

                $i++;

                $report = $concernable->report;
                $total = array_sum(array_column($report, 'Valor até venc.'));

                $event->sheet->setCellValue("E{$i}", "TOTAL (R$)");
                $event->sheet->setCellValue("F{$i}", number_format($total, 2, ',', '.'));

                $event->sheet->getStyle("A6:L" . $i)->applyFromArray($stylesAllCells);

                $event->sheet->getStyle("A{$i}:L{$i}")->applyFromArray($textWhite);
                $event->sheet->getStyle("A6:L6")->applyFromArray($textWhite);

                $event->sheet->getStyle("A6:L6")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '305496',
                        ],
                    ],
                ]);

                $event->sheet->getStyle("A{$i}:L{$i}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '305496',
                        ],
                    ],
                ]);
            }

        ];
    }
}
