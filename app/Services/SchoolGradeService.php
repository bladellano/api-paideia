<?php

namespace App\Services;

use App\Models\SchoolGrade;
use Illuminate\Http\Request;

class SchoolGradeService
{
    
    /**
     * Execute the function with the given request.
     *
     * @param Request $request The request data
     * @throws \Exception Error creating grade(s): message
     * @return array
     */
    public function execute(Request $request)
    {
        $arLines = self::generateHistoryLines($request->all());
        
        $teamId = $arLines[0]['team_id'];
        $studentId = $arLines[0]['student_id'];
        
        SchoolGrade::where('student_id', $studentId)
        ->where('team_id', $teamId)
        ->delete();
        
        try {
            SchoolGrade::insert($arLines);
            
            return ['total' => count($arLines)];
            
        } catch (\Exception $e) {
            throw new \Exception('Error creating grade(s): ' . $e->getMessage());
        }
    }
    
    private static function generateHistoryLines($data)
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
                            'created_at' => date('Y-m-d H:m:s'),
                            'user_id' => auth()->check() ?  auth()->id() : NULL
                        ];
                    }
                }
            }
        }
        
        return $rows;
    }
}
