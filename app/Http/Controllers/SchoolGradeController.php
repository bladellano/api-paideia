<?php

namespace App\Http\Controllers;

use App\Models\SchoolGrade;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SchoolGradeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $arLines = $this->generateHistoryLines($request->all());

        $teamId = $arLines[0]['team_id'];
        $studentId = $arLines[0]['student_id'];

        $record = SchoolGrade::where('student_id', $studentId)
            ->where('team_id', $teamId)
            ->get();

        if (!$record->isEmpty()) {

            SchoolGrade::where('student_id', $studentId)
                ->where('team_id', $teamId)
                ->delete();
        }

        try {

            foreach ($arLines as $row)
                SchoolGrade::create($row);

            return response()->json(['data' => ['total' => count($arLines)], 'message' => 'Nota(s) criado/atualizado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {

            return response()->json(['error' => true, 'message' => 'Erro ao criar a(s) nota(s): ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function generateHistoryLines($data)
    {
        $rows = [];

        foreach ($data['grade'] as $studentId => $gradeData) {
            foreach ($gradeData as $stageId => $subGradeData) {
                foreach ($subGradeData as $disciplineId => $subSubGradeData) {
                    foreach ($subSubGradeData as $turmaId => $grade) {
                        $rows[] = [
                            'grade' => $grade ?? 0,
                            'student_id' => $studentId,
                            'stage_id' => $stageId,
                            'discipline_id' => $disciplineId,
                            'team_id' => $turmaId,
                        ];
                    }
                }
            }
        }

        return $rows;
    }

    public function getGradeByStudent($studentId)
    {
        return SchoolGrade::getGrade($studentId);
    }
}
