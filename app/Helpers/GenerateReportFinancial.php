<?php

namespace App\Helpers;

use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;

class GenerateReportFinancial
{

    public static function compareStudents($a, $b)
    {
        return strcmp($a['student']['name'], $b['student']['name']);
    }

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

    /** Função para obter todas as datas de vencimento */
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

    /** Função para converter data no formato DD/MM/YYYY para objeto DateTime */
    private static function convertToDateTime($date)
    {
        $parts = explode('/', $date);
        return new DateTime("$parts[2]-$parts[1]-$parts[0]");
    }

    /** Função para gerar todos os meses entre duas datas */
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

    /** Função para gerar a matriz financeira */
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

    /** Organiza as notas para o formato de boletim */
    public static function organizeNotesForBulletin(array $arGrades): array
    {

        $organizedGrades = [];
        $stageIds = [];

        foreach ($arGrades as $grade) {
            $disciplineId = $grade['discipline_name'];
            $stageId = $grade['stage_id'];

            if (!in_array($stageId, $stageIds))
                $stageIds[] = $stageId;

            $organizedGrades[$disciplineId]['discipline_name'] = $disciplineId;
            $organizedGrades[$disciplineId]['stages'][$stageId] = $grade['grade'];
        }

        sort($stageIds);

        $finalGrades = [];

        foreach ($organizedGrades as $disciplineId => $grades) {
            $disciplineGrades = ['discipline_name' => $disciplineId];
            $totalGrades = 0;
            $numGrades = 0;

            foreach ($stageIds as $stageId) {
                if (isset($grades['stages'][$stageId])) {
                    $disciplineGrades['stage_id ' . $stageId] = $grades['stages'][$stageId];
                    $totalGrades += $grades['stages'][$stageId];
                    $numGrades++;
                } else {
                    $disciplineGrades['stage_id ' . $stageId] = null;
                }
            }

            if ($numGrades > 0) {
                $disciplineGrades['media'] = $totalGrades / $numGrades;
            } else {
                $disciplineGrades['media'] = null;
            }

            $finalGrades[] = $disciplineGrades;
        }

        return $finalGrades;
    }

    /** Organiza em formato de repasse */
    public static function organizesInTheFormOfTransfer(array $registrations, $team, $start_date, $end_date): array
    {

        Carbon::setLocale('pt_BR');

        $start_timestamp = \DateTime::createFromFormat('d/m/Y', $start_date)->getTimestamp();
        $end_timestamp = \DateTime::createFromFormat('d/m/Y', $end_date)->getTimestamp();

        $report = [];

        foreach ($registrations as $registration) {
            foreach ($registration['financials'] as $financial) {

                $byExtense = mb_strtoupper(Carbon::createFromFormat('d/m/Y', $financial['due_date'])->translatedFormat('F/Y'));

                $due_date_timestamp = \DateTime::createFromFormat('d/m/Y', $financial['due_date'])->getTimestamp();
                if ($due_date_timestamp >= $start_timestamp && $due_date_timestamp <= $end_timestamp) {
                    $description = ($financial['quota'] ? "({$financial['quota']}) " : "") . $financial['service_type']['name'];
                    $value = $financial['value'];
                    $due_date = \DateTime::createFromFormat('d/m/Y', $financial['due_date'])->format('d/m/Y');
                    $payment_type = $financial['payment_type']['name'];
                    $student_name = $registration['student']['name'];

                    $report[] = [
                        'Matrícula/Cliente' => '',
                        'Turma' => $team->name,
                        'Aluno' => mb_strtoupper($student_name),
                        'Obs' => '',
                        'Descrição' => mb_strtoupper("{$description} - {$byExtense}"),
                        'Valor até venc.' => number_format($value, 2, ',', '.'),
                        'Dt. venc.' => $due_date,
                        'Valor recebido' => '',
                        'Repasse' => '',
                        'Dt. recebido' => '',
                        'Forma pgto' => $payment_type,
                        'Usuário recebimento' => '',
                    ];
                }
            }
        }

        return $report;
    }
}
