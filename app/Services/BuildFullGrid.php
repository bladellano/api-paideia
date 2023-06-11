<?php

namespace App\Services;

use App\Models\Team;

class BuildFullGrid
{

    public function execute(Team $team): array
    {

        $rawData = \DB::select('SELECT
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

        if(!$rawData) {
            return response()->json([]);
        }

        $grid_name = $rawData[0]['grid_name'];
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

        $maxCount = count(array_unique(array_column($rawData, 'stage_id')));

        foreach ($result as &$subAr) {
            $subAr = array_pad($subAr, $maxCount, []);
        }

        unset($subAr);

        //Range
        $arrStages = [];
        foreach (range(1, $maxCount) as $number) {
            $arrStages[] = 'stage_' . $number;
        }

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

        return [
            'grid_name' => $grid_name,
            'course_name' => $course_name,
            'total_stage' => $maxCount,
            'total_workload' => $totalWorkload,
            'list' =>$result
        ];
    }
}
