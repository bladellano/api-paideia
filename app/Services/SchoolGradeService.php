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
        
        try {

            foreach($arLines as $note) {

                $hasGrade = SchoolGrade::where('student_id', $note['student_id'])
                ->where('team_id', $note['team_id'])
                ->where('discipline_id', $note['discipline_id'])
                ->where('stage_id', $note['stage_id'])
                ->get()->first();
    
                if($hasGrade) {
                    
                    $updated = SchoolGrade::find($hasGrade->id);
                    $updated->update($note);

                } else {

                    SchoolGrade::insert($note);

                }
    
            }
            
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
