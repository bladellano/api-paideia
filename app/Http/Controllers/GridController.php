<?php

namespace App\Http\Controllers;

use App\Models\Grid;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Requests\GridRequest;
use App\Models\GridTemplate;

class GridController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Grid::query();
        $query->with('gridTemplates');

        $search   = $request->input('search');
        $sortBy   = $request->input('sortBy');
        $sortDesc = $request->input('sortDesc');

        $perPage = $request->input('perPage') ?? 10;

        $page = $request->input('page') ?? 1;

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        if ($sortBy) {
            $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');
        }

        $itens = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($itens);
    }

    /**
     * Store a newly created resource in storage.
     * @param GridRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(GridRequest $request)
    {
        try {
            $record = Grid::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Record successfully created!'], 201);

        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=>$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\Grid  $grid
     * @return \Illuminate\Http\Response
     */
    public function show(Grid $grid)
    {
        $grid->gridTemplates;
        return response()->json([$grid]);
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grid  $grid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grid $grid)
    {
        try {
            $grid->update($request->all());
            return response()->json(['data'=>$grid, 'message' => 'Registration successfully updated!']);

        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Grid  $grid
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grid $grid)
    {
        $grid->delete();
        return response()->json(['message' => 'Record removed successfully.']);
    }

    /**
    * Retorna através do grid_id da turma
    * o grid template (grade completa - com todos nomes)
    * @param Team $team
    * @return \Illuminate\Http\Respons
    */
    public function listGrid(Team $team)
    {
        /** @todo criar um service para isso! */

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

        return response()->json([
            'grid_name' => $grid_name,
            'course_name' => $course_name,
            'total_stage' => $maxCount,
            'total_workload' => $totalWorkload,
            'list' =>$result
        ]);
    }

    public function getGridTemplate(Grid $grid)
    {
        try {
            $record = GridTemplate::where('grid_id', $grid->id);
            return response()->json(['data'=> $record->get()]);
        } catch (\Exception $e) {
            return response()->json(['error'=>true, 'message'=>$e->getMessage()], 500);
        }
    }

    public function removeTemplatesFromGrid(Grid $grid)
    {
        try {
            GridTemplate::where('grid_id', $grid->id)->forceDelete();
            return response()->json(['message' => 'Records removed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }
}
