<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Http\Request;

class BuildFullGrid
{

    public function execute(Team $team, Request $request): array
    {
        $rawData = \DB::select('SELECT \''. $team->name .'\' as team_name,
        gt.id,
        g.id as grid_id,
        g.name as grid_name,
        --
        c.id as course_id,
        c.name as course_name,
        --
        s.id as stage_id,
        CONCAT(\'stage_\', s.stage) as stage_name,
        --
        d.id as discipline_id,
        d.name as discipline_name,
        gt.workload
        FROM grid_templates gt
        INNER JOIN grids g ON g.id = gt.grid_id
        INNER JOIN courses c ON c.id = gt.course_id
        INNER JOIN stages s ON s.id = gt.stage_id
        INNER JOIN disciplines d ON d.id = gt.discipline_id WHERE gt.grid_id = ?', [$team->grid_id]);

        $rawData = array_map(function ($item) {
            return (array) $item;
        }, $rawData);

        if(!$rawData)
            return response()->json([]);

        $grid_name = $rawData[0]['grid_name'];
        $team_name = $rawData[0]['team_name'];
        $course_name = $rawData[0]['course_name'];
        $totalWorkload = array_column($rawData, 'workload');
        $totalWorkload = array_sum($totalWorkload);

        $result = array_reduce($rawData, function ($acc, $item) {
            $discipline = $item['discipline_name'];
            $stage = $item['stage_name'];

            if(!array_key_exists($discipline, $acc)) {
                $acc[$discipline] = [];
            }

            if(!array_key_exists($stage, $acc[$discipline])) {
                $acc[$discipline][$stage] = $item;
            }

            return $acc;
        }, []);

        if(isset($request->discipline_id))
            $result = self::filterArrayByDisciplineId($result, $request->discipline_id);

        $arrStages = [];

        foreach($result as $v)
            foreach($v as $p => $k)
                $arrStages[] = ($p);
        $arrStages = array_unique($arrStages);

        $maxCount = count(array_unique(array_column($rawData, 'stage_id')));

        foreach ($result as &$subAr)
            $subAr = array_pad($subAr, $maxCount, []);

        unset($subAr);

        $result = array_map(function ($item) use ($arrStages) {

            foreach ($item as $k => &$array) {

                if(!in_array($k, $arrStages)) {
                    unset($item[$k]) ;
                } else {
                    $arrToRemove = array($k);
                    $arrStages = array_diff($arrStages, $arrToRemove);
                }
            }

            foreach ($arrStages as $stage) {
                $item[$stage] = [];
            }

            ksort($item);
            return $item;

        }, $result);

        $stagesNumbers =  array_map(fn ($item) => (int) preg_replace('/[^0-9]/','',$item), $arrStages);
        sort($stagesNumbers);
        return [
            'team_name' => $team_name,
            'grid_name' => $grid_name,
            'course_name' => $course_name,
            'total_stage' => $maxCount,
            'total_workload' => $totalWorkload,
            'list' => $result,
            'stagesNumbers' => $stagesNumbers,
        ];
    }

    private static function filterArrayByDisciplineId($array, $disciplineId) {
        $filteredArray = [];
    
        foreach ($array as $disciplineName => $stages) {
            foreach ($stages as $stageName => $details) {
                if ($details['discipline_id'] == $disciplineId) {
                    if (!isset($filteredArray[$disciplineName])) {
                        $filteredArray[$disciplineName] = [];
                    }
                    $filteredArray[$disciplineName][$stageName] = $details;
                }
            }
        }
    
        return $filteredArray;
    }
}
