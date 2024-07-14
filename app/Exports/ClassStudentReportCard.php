<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassStudentReportCard implements FromCollection, WithColumnWidths, WithEvents, WithDrawings, WithCustomStartCell
{
    private $grades;
    private $course;
    private $student;

    const MAP = [
        "discipline_name" => "disciplinas",
        "media" => "média",
        "stage_id 1" => "etapa 1",
        "stage_id 2" => "etapa 2",
        "stage_id 3" => "etap 3",
        "stage_id 4" => "etapa 4",
    ];

    public function __construct($arGrades, $student, $team)
    {
        $students = $team->getStudentsByTeam($team->id);

        $this->grades = $arGrades;
        $this->student = $student;
        $this->course = $students[0]->course;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $grades = $this->grades;

        $header = array_keys($grades[0]);
        $header = array_map(fn ($i) => self::MAP[$i], $header);
        $header = array_map('mb_strtoupper', $header);
        array_unshift($grades, $header);

        return collect($grades);
    }

    public function startCell(): string
    {
        return 'A13';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('~~Paideia Educacional~~');
        $drawing->setPath(public_path('/logo.png'));
        $drawing->setHeight(74);
        $drawing->setCoordinates('B1');

        return $drawing;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 26,
            'B' => 12,
            'C' => 12,
            'D' => 12,
            'E' => 12,
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

        $textCenter = [
            'font' => ['size' => 10, 'color' => ['rgb' => '666666']],
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

                $quantityColumns = count($concernable->grades[0]);
                $letter = \App\Helpers\GenerateReportFinancial::getColumnLetter($quantityColumns);

                $event->sheet->mergeCells("A1:{$letter}1", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A2:{$letter}2", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A3:{$letter}3", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A4:{$letter}4", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A5:{$letter}5", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A6:{$letter}6", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A7:{$letter}7", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A8:{$letter}8", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A9:{$letter}9", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A10:{$letter}10", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A11:{$letter}11", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells("A12:{$letter}12", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('A2', 'RESOLUÇÃO CEE/PA N° 90 DE 27 DE MARÇO DE 2023');
                $event->sheet->setCellValue('A3', 'INEP - Nº 15176266');

                $event->sheet->setCellValue('A4', 'FICHA INDIVIDUAL');

                $event->sheet->setCellValue('A5', mb_strtoupper("CURSO: {$concernable->course})"));
                $event->sheet->setCellValue('A6', '');
                $event->sheet->setCellValue('A7', mb_strtoupper("NOME DO ALUNO: {$concernable->student->name}"));
                $event->sheet->setCellValue('A8', mb_strtoupper("MÃE: {$concernable->student->name_mother}"));
                $event->sheet->setCellValue('A9', mb_strtoupper("DATA DE NASCIMENTO: {$concernable->student->birth_date->translatedFormat('d/m/Y')}"));
                $event->sheet->setCellValue('A10', mb_strtoupper("NATURALIDADE: {$concernable->student->naturalness}"));
                $event->sheet->setCellValue('A11', mb_strtoupper("CPF: {$concernable->student->cpf}"));

                $event->sheet->setCellValue('A12', 'QUADRO DE NOTAS');
            },
            AfterSheet::class => function (AfterSheet $event) use ($aStylesHeader, $textCenter, $stylesAllCells) {

                $concernable = $event->getConcernable();
                $quantityColumns = count($concernable->grades[0]);
                $letter = \App\Helpers\GenerateReportFinancial::getColumnLetter($quantityColumns);

                $event->sheet->getRowDimension('1')->setRowHeight(70);
                $event->sheet->getRowDimension('12')->setRowHeight(30);

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

                $event->sheet->getStyle("A2:{$letter}3")->applyFromArray($textCenter);
                $event->sheet->getStyle("A4:{$letter}5")->applyFromArray($aStylesHeader);

                $event->sheet->getStyle("A12:{$letter}12")->applyFromArray($aStylesHeader);
                $event->sheet->getStyle("A13:{$letter}13")->applyFromArray($aStylesHeader);

                $endLine = count($concernable->grades) + 13;
                $event->sheet->getStyle("A1:{$letter}" . $endLine)->applyFromArray($stylesAllCells);

                // After
                $endLineSignature = $endLine + 5;

                $event->sheet->mergeCells("A{$endLineSignature}:{$letter}{$endLineSignature}", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue("A{$endLineSignature}", mb_strtoupper("________________________________________________________________________"));
                $event->sheet->getStyle("A{$endLineSignature}:{$letter}{$endLineSignature}")->applyFromArray($textCenter);

                $endLineSignature++;

                $event->sheet->mergeCells("A{$endLineSignature}:{$letter}{$endLineSignature}", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue("A{$endLineSignature}", mb_strtoupper("DIRETORA PEDAGÓGICA             SECRETÁRIA ESCOLAR"));
                $event->sheet->getStyle("A{$endLineSignature}:{$letter}{$endLineSignature}")->applyFromArray($textCenter);

                $endLineSignature++;
                $endLineSignature++;

                $event->sheet->mergeCells("A{$endLineSignature}:{$letter}{$endLineSignature}", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue("A{$endLineSignature}", "Ananindeua/PA, " . \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y'));
                $event->sheet->getStyle("A{$endLineSignature}:{$letter}{$endLineSignature}")->applyFromArray($textCenter);

                $endLineSignature++;
                $endLineSignature++;

                $event->sheet->mergeCells("A{$endLineSignature}:{$letter}{$endLineSignature}", Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue("A{$endLineSignature}", "Tv. WE 17, Cidade Nova 2, Nº 111 - Coqueiro, Ananindeua - PA, 7130-450,\nEmail: contato@paideiaeducacional.com, Fone: 91 3722-9891 / 9 8176-9979");
                $event->sheet->getStyle("A{$endLineSignature}:{$letter}{$endLineSignature}")->applyFromArray($textCenter);

                $event->sheet->getRowDimension($endLineSignature)->setRowHeight(30);
            }

        ];
    }
}
