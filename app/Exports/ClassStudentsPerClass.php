<?php

namespace App\Exports;

use App\Models\Team;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassStudentsPerClass implements FromCollection, WithEvents, WithMapping, WithHeadings, WithDrawings, WithStyles
{
    protected $teamId;

    protected $index = 0;

    protected $course;
    protected $teaching;
    protected $team;

    protected $qtdStudents;
    protected $qtdRows;
    protected $extraLines;

    protected $header = 5;

    public function __construct($teamId, $extraLines = 1)
    {
        $this->teamId = $teamId;
        $this->extraLines = $extraLines;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $team = Team::findOrFail($this->teamId);
        $students = $team->getStudentsByTeam($this->teamId);

        $this->qtdStudents = count($students);

        for ($i = 0; $i <= $this->extraLines; $i++)
            array_push($students, new \stdClass());

        $this->course = $students[0]->course ?? null;
        $this->team = $students[0]->team ?? null;
        $this->teaching = $students[0]->teaching ?? null;

        $c = collect($students);

        $this->qtdRows = $c->count();

        return $c;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1  => ['font' => ['size' => 10]],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/logo.png'));
        $drawing->setHeight(74);
        $drawing->setCoordinates('B1');

        return $drawing;
    }

    public function headings(): array
    {
        return [
            'N°',
            'ALUNO',
            'ASSINATURA',
        ];
    }

    public function map($item): array
    {
        $this->index++;

        return [
            $this->index,
            mb_strtoupper($item->student ?? "")
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

        $aOnlyCenter = [
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
            BeforeSheet::class => function (BeforeSheet $event) use ($aStylesHeader, $stylesAllCells, $aOnlyCenter) {

                $event->sheet->insertNewRowBefore(1);

                /** //? SUPER HEADER */
                $event->sheet->setCellValue('A1', '');
                $event->sheet->mergeCells('A1:B2', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('A4', '');
                $event->sheet->mergeCells('A4:D4', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->getStyle("C1:D1")->applyFromArray($aStylesHeader);
            },
            AfterSheet::class => function (AfterSheet $event) use ($aStylesHeader, $stylesAllCells, $aOnlyCenter) {

                $event->sheet
                    ->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                #$event->sheet->getPageMargins()->setTop(0.10);
                #$event->sheet->getPageMargins()->setRight(0.10);
                #$event->sheet->getPageMargins()->setLeft(0.10);
                #$event->sheet->getPageMargins()->setBottom(0.10);
                #$event->sheet->getPageMargins()->setHeader(0.10);
                #$event->sheet->getPageMargins()->setFooter(0.10);

                $event->sheet->getPageSetup()->setFitToPage(true);

                //? Autosize de todas as colunas
                $event->sheet->autoSize();

                //? Aplicar estilo somente às células de intervalo definido.
                $event->sheet->getStyle('A3:D5')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'EEEEEE',
                        ],
                    ],
                ]);

                //? Ajusta a altura de determinada dimensao (linha).
                $event->sheet->getRowDimension('1')->setRowHeight(35);
                $event->sheet->getRowDimension('2')->setRowHeight(35);

                //? ---------------------------------------------------------- //

                $event->sheet->setCellValue('C1', "PAIDEIA EDUCACIONAL CURSOS\n E TREINAMENTOS LTDA.\n$this->team");
                $event->sheet->mergeCells('C1:C2', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('D1', "RELAÇÃO DE ALUNOS POR TURMA\nTURMA: $this->team\nTURNO: -- \n{MES} - {DISCIPLINA}\n");
                $event->sheet->mergeCells('D1:D2', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('A3', mb_strtoupper($this->teaching));
                $event->sheet->mergeCells('A3:D3', Worksheet::MERGE_CELL_CONTENT_MERGE);

                //? ---------------------------------------------------------- //

                $event->sheet->mergeCells('C5:D5', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $row = $this->qtdRows + $this->header;

                //? Total de alunos.
                $event->sheet->setCellValue("A{$row}", 'TOTAL DE ALUNOS: ' . $this->qtdStudents);
                $event->sheet->mergeCells("A{$row}:D{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'EEEEEE',
                        ],
                    ],
                ]);

                //? Mesclando varias linhas (assinaturas).
                for ($i = 6; $i < $row; $i++)
                    $event->sheet->mergeCells("C{$i}:D{$i}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->getStyle("A1:D{$row}")->applyFromArray($stylesAllCells);
                $event->sheet->getStyle("A5:D5")->applyFromArray($aStylesHeader);
            }
        ];
    }
}
