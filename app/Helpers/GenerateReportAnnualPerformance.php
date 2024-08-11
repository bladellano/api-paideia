<?php

namespace App\Helpers;

class GenerateReportAnnualPerformance
{
    public static function execute(\Illuminate\Support\Collection $grades, $resultOnly = FALSE): array
    {
        $data = [];
        $header = [];
        $subHeader = ['Nº', 'ALUNOS'];
        $header = ['', '']; // Espaço reservado para "ALUNOS"

        $disciplines = $grades->pluck('discipline_id')->unique();

        foreach ($disciplines as $discipline_id) {

            $discipline_name = $grades->where('discipline_id', $discipline_id)->first()->discipline_name;

            $header[] = $discipline_name;

            if (!$resultOnly) {

                $header[] = '';
                $header[] = '';

                $subHeader[] = "1ª ETAPA";
                $subHeader[] = "2ª ETAPA";
            }

            $subHeader[] = "RESULTADO";
        }

        $data[] = $subHeader;
        array_unshift($data, $header);

        $students = $grades->pluck('student_id')->unique();

        $rows = [];
        $rowIndex = 1;

        foreach ($students as $student_id) {

            $student_name = mb_strtoupper($grades->where('student_id', $student_id)->first()->student_name);

            $row = [$rowIndex, $student_name];

            foreach ($disciplines as $discipline_id) {

                $stage1 = $grades->where('student_id', $student_id)
                    ->where('discipline_id', $discipline_id)
                    ->where('stage_id', 1)
                    ->first();

                $stage2 = $grades->where('student_id', $student_id)
                    ->where('discipline_id', $discipline_id)
                    ->where('stage_id', 2)
                    ->first();

                $grade1 = $stage1 ? $stage1->grade : 0;
                $grade2 = $stage2 ? $stage2->grade : 0;

                $average = ($grade1 + $grade2) / 2;

                if (!$resultOnly) {
                    $row[] = (string)$grade1;
                    $row[] = (string)$grade2;
                }

                $row[] = (string)$average;
            }

            $rows[] = $row;

            $rowIndex++;
        }

        // Ordenar o array $rows pela coluna dos alunos (primeira coluna)
        usort($rows, function ($a, $b) {
            return strcmp($a[0], $b[0]);
        });

        $data = array_merge($data, $rows);

        return $data;
    }
}
