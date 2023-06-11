<?php

namespace App\Http\Controllers;

use App\Models\Grid;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Requests\GridRequest;
use App\Models\GridTemplate;
use App\Services\BuildFullGrid;

class GridController extends Controller
{
    private $buildFullGrid;

    public function __construct(BuildFullGrid $buildFullGrid)
    {
        $this->buildFullGrid = $buildFullGrid;
    }

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
    public function getFullGrid(Team $team)
    {
        $fullGrid = $this->buildFullGrid->execute($team);
        return response()->json($fullGrid);
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
            return response()->json(['message' => 'Registros removidos com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }
}
