<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

//! @TODO elimar essa class e deixar tudo em ClassDiaryPerDisciplineSheet.
class ClassDiaryExport implements FromCollection, WithMapping, WithEvents, WithMultipleSheets
{

    protected $teamId;
    protected $qtdStudents;
    protected $index = 0;
    protected $headerHeight = 9; // Linhas para baixo.

    protected $course;
    protected $teaching;
    protected $team;

    protected $disciplines;

    public function __construct($teamId, $aDisciplines)
    {
        $this->teamId = $teamId;
        $this->disciplines = $aDisciplines;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $sql = "
            SELECT 
                LPAD(r.id, 6, '0') AS registration,
                s.name AS student,
                t.name AS team,
                g.name AS grid,
                __gt.course,
                __gt.teaching
            FROM 
                registrations r
            INNER JOIN 
                teams t ON t.id = r.team_id
            INNER JOIN 
                students s ON s.id = r.student_id
            INNER JOIN 
                grids g ON g.id = t.grid_id
            INNER JOIN (
                SELECT 
                    gt.grid_id, 
                    gt.course_id, 
                    c.name AS course, 
                    teaching.name AS teaching 
                FROM 
                    grid_templates gt
                INNER JOIN 
                    courses c ON c.id = gt.course_id
                INNER JOIN 
                    teachings teaching ON teaching.id = c.teaching_id
                GROUP BY 
                    gt.grid_id, 
                    gt.course_id, 
                    c.name,
                    teaching.name
            ) __gt ON __gt.grid_id = t.grid_id
            WHERE 
                r.team_id = " . $this->teamId . "
            ORDER BY 
                s.name ASC";

        $records = \DB::select($sql);

        $this->course = $records[0]->course;
        $this->team = $records[0]->team;
        $this->teaching = $records[0]->teaching;

        $collection = collect($records);
        $this->qtdStudents = $collection->count();

        return $collection;
    }

    public function map($item): array
    {
        $this->index++;

        return [
            //? $item->registration,
            $this->index, // N°,
            mb_strtoupper($item->student),
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            $this->index, // N°
        ];
    }

    public function sheets(): array
    {

        $sheets = [];

        foreach ($this->disciplines as $discipline)
            $sheets[$discipline] = new ClassDiaryPerDisciplineSheet($this->teamId, $discipline);

        return $sheets;
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
            BeforeSheet::class => function (BeforeSheet $event) {

                /** SUPER HEADER */
                $event->sheet->setCellValue('A1', 'LOGO');
                $event->sheet->mergeCells('A1:B4', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('C1', 'DIÁRIO DE CLASSE');
                $event->sheet->mergeCells('C1:Z4', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AA1', 'DISCIPLINA');
                $event->sheet->mergeCells('AA1:AI2', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AA3', 'TOTAL DE AULAS PREVISTAS:');
                $event->sheet->mergeCells('AA3:AI3', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AA4', 'TOTAL DE AULAS DADAS:');
                $event->sheet->mergeCells('AA4:AI4', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->mergeCells('AJ1:AL2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('AJ3:AL3', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('AJ4:AL4', Worksheet::MERGE_CELL_CONTENT_MERGE);

                /** LINHA 5 */
                $event->sheet->setCellValue('A5', 'UNIDADE');
                $event->sheet->mergeCells('A5:B5', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('C5', 'ANO');
                $event->sheet->mergeCells('C5:E5', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('F5', 'MÊS');
                $event->sheet->mergeCells('F5:K5', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('L5', 'ETAPA');
                $event->sheet->mergeCells('L5:N5', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('O5', 'CURSO');
                $event->sheet->mergeCells('O5:V5', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('W5', 'TURMA');
                $event->sheet->mergeCells('W5:Z5', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AA5', 'PROFESSOR');
                $event->sheet->mergeCells('AA5:AL5', Worksheet::MERGE_CELL_CONTENT_MERGE);

                /** LINHA 6 */
                $event->sheet->setCellValue('A6', 'PAIDEIA EDUCACIONAL');
                $event->sheet->mergeCells('A6:B6', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('C6', date('Y'));
                $event->sheet->mergeCells('C6:E6', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('F6', mb_strtoupper(Carbon::now()->format('F')));
                $event->sheet->mergeCells('F6:K6', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('L6', "1");
                $event->sheet->mergeCells('L6:N6', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AA6', '--');
                $event->sheet->mergeCells('AA6:AL6', Worksheet::MERGE_CELL_CONTENT_MERGE);

                /** VARIAS COMBINACOES */

                $event->sheet->setCellValue('A7', 'N°');
                $event->sheet->mergeCells('A7:A9', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('B7', 'NOME DO ALUNO');
                $event->sheet->mergeCells('B7:B9', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('C7', 'NÚMERO DE AULAS');
                $event->sheet->mergeCells('C7:AH7', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AI7', 'AVALIAÇÕES');
                $event->sheet->mergeCells('AI7:AL7', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('C8', 'DIAS');
                $event->sheet->mergeCells('C8:D8', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('C9', 'AULAS');
                $event->sheet->mergeCells('C9:D9', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AH8', 'N°');
                $event->sheet->mergeCells('AH8:AH9', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AI8', '1');
                $event->sheet->mergeCells('AI8:AI9', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AJ8', '2');
                $event->sheet->mergeCells('AJ8:AJ9', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AK8', 'MÉDIA');
                $event->sheet->mergeCells('AK8:AK9', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('AL8', 'FALTAS');
                $event->sheet->mergeCells('AL8:AL9', Worksheet::MERGE_CELL_CONTENT_MERGE);
            },
            AfterSheet::class => function (AfterSheet $event) use ($aStylesHeader, $stylesAllCells, $aOnlyCenter) {

                $event->sheet->setCellValue('O6', mb_strtoupper($this->course));
                $event->sheet->mergeCells('O6:V6', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue('W6', mb_strtoupper($this->team));
                $event->sheet->mergeCells('W6:Z6', Worksheet::MERGE_CELL_CONTENT_MERGE);

                /** APLICAÇÃO DOS ESTILOS */
                $event->sheet->getStyle('A1:AL9')->applyFromArray($aStylesHeader);

                $row = $this->qtdStudents + $this->headerHeight + 6; // + 6 Linhas para baixo.

                $event->sheet->setCellValue("A{$row}", 'COMPETÊNCIAS');
                $event->sheet->mergeCells("A{$row}:B{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue("C{$row}", 'DATA');
                $event->sheet->mergeCells("C{$row}:G{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue("H{$row}", 'REGISTRO DO PROCESSO EDUCATIVO');
                $event->sheet->mergeCells("H{$row}:W{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue("X{$row}", 'REGISTRO DO PROCESSO EDUCATIVO (DETALHAMENTO)');
                $event->sheet->mergeCells("X{$row}:AL{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->getStyle("A1:AL{$row}")->applyFromArray($stylesAllCells);
                $event->sheet->getStyle("A{$row}:AL{$row}")->applyFromArray($aStylesHeader);

                // ASSINATURAS
                $row += 10; // + 10 Linhas para baixo.

                $event->sheet->setCellValue("A{$row}", '______/______/______');
                $event->sheet->mergeCells("A{$row}:H{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue("I{$row}", '______/______/______');
                $event->sheet->mergeCells("I{$row}:Y{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue("Z{$row}", '______/______/______');
                $event->sheet->mergeCells("Z{$row}:AL{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->getStyle("A{$row}:AL{$row}")->applyFromArray($aOnlyCenter);

                // RESPONSAVEIS ASSINATURA
                $row += 1; // + 1 Linha para baixo.

                $event->sheet->setCellValue("A{$row}", 'ENCERRAMENTO PEDAGÓGICO');
                $event->sheet->mergeCells("A{$row}:H{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue("I{$row}", 'PROFESSOR');
                $event->sheet->mergeCells("I{$row}:Y{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->setCellValue("Z{$row}", 'DIRETOR');
                $event->sheet->mergeCells("Z{$row}:AL{$row}", Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->getStyle("A{$row}:AL{$row}")->applyFromArray($aOnlyCenter);
            }
        ];
    }
}
