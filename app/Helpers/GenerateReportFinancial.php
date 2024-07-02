<?php

namespace App\Helpers;

use DateTime;
use DatePeriod;
use DateInterval;

class GenerateReportFinancial
{

    public static function execute(array $data): array
    {

        // 1. Obter todas as datas de vencimento
        $dueDates = self::getDueDates($data);
        $dueDatesDateTime = array_map([self::class, 'convertToDateTime'], $dueDates);

        // 3. Encontrar a menor e maior data
        $minDate = min($dueDatesDateTime);
        $maxDate = max($dueDatesDateTime);

        // 4. Gerar intervalo de meses
        $months = self::generateMonthRange($minDate, $maxDate);

        // 5. Criar a matriz financeira
        return self::createFinancialMatrix($data, $months);
    }

    // Função para obter todas as datas de vencimento
    private static function getDueDates($data)
    {
        $dates = [];
        foreach ($data as $item) {
            foreach ($item['financials'] as $financial) {
                $dates[] = $financial['due_date'];
            }
        }
        return $dates;
    }

    // Função para converter data no formato DD/MM/YYYY para objeto DateTime
    private static function convertToDateTime($date)
    {
        $parts = explode('/', $date);
        return new DateTime("$parts[2]-$parts[1]-$parts[0]");
    }

    // Função para gerar todos os meses entre duas datas
    private static function generateMonthRange($startDate, $endDate)
    {
        $interval = new DateInterval('P1M');
        $period = new DatePeriod($startDate, $interval, $endDate->modify('+1 month'));
        $months = [];
        foreach ($period as $dt) {
            $months[] = $dt->format("M/Y");
        }
        return $months;
    }

    // Função para gerar a matriz financeira
    private static function createFinancialMatrix($data, $months)
    {
        $matrix = [];

        foreach ($data as $student) {

            $row = [
                'student_name' => mb_strtoupper($student['student']['name']),
                'financials' => array_fill_keys($months, null)
            ];

            foreach ($student['financials'] as $financial) {
                $monthYear = (self::convertToDateTime($financial['due_date']))->format("M/Y");

                $dueDate = \DateTime::createFromFormat('d/m/Y', $financial['due_date']);

                $row['financials'][$monthYear] = [
                    'value' => number_format($financial['value'], 2, ',', '.'),
                    'paid' => $financial['paid'],
                    'overdue' => !$financial['paid'] && $dueDate < new \DateTime() ? 1 : 0,
                ];
            }

            $matrix[] = $row;
        }

        return $matrix;
    }

    public static function getColumnLetter($colIndex)
    {
        $colLetter = '';
        while ($colIndex > 0) {
            $colIndex--;
            $colLetter = chr(($colIndex % 26) + 65) . $colLetter;
            $colIndex = (int)($colIndex / 26);
        }
        return $colLetter;
    }

    public static function reorderIndexes(array $data, $start): array
    {
        $startColumn = $start;
        $numElements = count($data);
        $index = range($startColumn, $startColumn + $numElements - 1);
        return array_combine($index, $data);
    }
}
