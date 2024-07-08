<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Team;
use App\Helpers\GenerateReportFinancial;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassReportOfStudentDataByClass implements FromCollection, WithColumnWidths, WithEvents, WithMapping
{

    private $team;
    private $quantity;
    private $index = 0;
    const MAP = [
        "id" => "identificação",
        "name" => "nome",
        "cpf" => "cpf",
        "rg" => "rg",
        "expedient_body" => "órgão expedidor",
        "gender" => "gênero",
        "nationality" => "nacionalidade",
        "naturalness" => "naturalidade",
        "phone" => "telefone",
        "email" => "email",
        "name_mother" => "nome da mãe",
        "birth_date" => "data de nascimento",
        "age" => "idade"
    ];

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $this->team;

        $registrations = $this->team->registrations;

        $toArray = $registrations->toArray();
        $toArray = array_filter($toArray, fn ($item) => isset($item['student']['name']));

        usort($toArray, [GenerateReportFinancial::class, 'compareStudents']);

        $students = array_column($toArray, 'student');
        $students = array_filter($students);
        $students = array_map(function ($item) {
            $item['age'] = Carbon::createFromFormat('d/m/Y', $item['birth_date'])->age;
            return $item;
        }, $students);

        $this->quantity = count($students);

        $header = array_keys($students[0]);
        $header = array_map(fn ($i) => self::MAP[$i], $header);
        $header = array_map('mb_strtoupper', $header);

        array_unshift($students, $header);

        return collect($students);
    }

    public function map($item): array
    {
        array_shift($item);

        return [
            $this->index++,
            ...$item
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 32,
            'C' => 12,
            'D' => 12,
            'E' => 20,
            'F' => 15,
            'G' => 20,
            'H' => 20,
            'I' => 12,
            'J' => 12,
            'K' => 22,
            'L' => 12,
            'M' => 8,
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

                $event->sheet->mergeCells('A1:L1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->setCellValue('A1', 'RELATÓRIO COM DADOS DOS ALUNOS DA TURMA: ' . $this->team->name);
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
                $event->sheet->getRowDimension('1')->setRowHeight(20);

                $event->sheet->getStyle("A1:M2")->applyFromArray($aStylesHeader);

                $event->sheet->getStyle("A1:M" . $this->quantity + 2)->applyFromArray($stylesAllCells);
            }

        ];
    }
}
