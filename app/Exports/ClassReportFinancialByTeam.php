<?php

namespace App\Exports;

use App\Helpers\GenerateReportFinancial;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ClassReportFinancialByTeam implements FromCollection, WithMapping, WithColumnWidths, WithEvents
{

    private $financialReport;
    private $index = 0;
    private $quantityColumns;
    private $generateReportFinancial;

    private const COLORS = [
        'SUCCESS' => 'D1E7DD',
        'DANGER' => 'F8D7DA',
        'DEFAULT' => 'EEEEEE',
    ];

    public function __construct(
        array $financialReport,
        string $teamName,
    ) {

        $preparedReport = [];

        foreach ($financialReport as $fnc) {

            $preparedReport[] = [
                'ALUNOS' => $fnc['student_name'],
                ...$fnc['financials']
            ];
        }

        $header = array_keys($preparedReport[0]);
        $header[0] = "{$header[0]} - " . $teamName;
        $header = array_map('mb_strtoupper', $header);

        $this->quantityColumns = count($header);

        array_unshift($preparedReport, $header);

        $this->financialReport = $preparedReport;
        $this->generateReportFinancial = new GenerateReportFinancial();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        return collect($this->financialReport);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 40,
        ];
    }

    public function map($item): array
    {

        $item = array_map(function ($item) {
            if (isset($item['value']))
                return $item['value'];
            return $item;
        }, $item);

        return [
            $this->index++,
            ...$item
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

                $arFinancials = $event->getConcernable()->financialReport;
                $arFinancials = $this->generateReportFinancial->reorderIndexes($arFinancials, 1);

                for ($i = 1; $i <= count($arFinancials); $i++) {

                    $onlyNumbersIndex = array_values($arFinancials[$i]);
                    $matrixFinTwo = $this->generateReportFinancial->reorderIndexes($onlyNumbersIndex, 2);

                    foreach ($matrixFinTwo as $k => $v) {

                        $letter = $this->generateReportFinancial->getColumnLetter($k);

                        $range = "{$letter}{$i}:{$letter}{$i}";

                        if (!is_array($v))
                            continue;

                        $color = self::COLORS['DEFAULT'];

                        if ($v['paid'])
                            $color = self::COLORS['SUCCESS'];

                        if (!$v['paid'] && $v['overdue'])
                            $color = self::COLORS['DANGER'];

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

                /** APLICAÇÃO DOS ESTILOS */
                $column = $this->generateReportFinancial->getColumnLetter($this->quantityColumns + 1);

                #$event->sheet->getStyle("A1:{$column}1")->applyFromArray($aStylesHeader);
                $event->sheet->getStyle("A1:{$column}" . count($arFinancials))->applyFromArray($stylesAllCells);
            }

        ];
    }
}
