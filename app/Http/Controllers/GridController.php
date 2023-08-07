<?php

namespace App\Http\Controllers;

use App\Models\Grid;
use App\Models\Team;
use App\Models\GridTemplate;
use Illuminate\Http\Request;
use App\Services\GridService;
use Illuminate\Http\Response;
use App\Services\BuildFullGrid;
use App\Http\Requests\GridRequest;

class GridController extends Controller
{
    private $buildFullGrid;
    private $service;

    public function __construct(BuildFullGrid $buildFullGrid, GridService $service)
    {
        $this->buildFullGrid = $buildFullGrid;
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request, ['gridTemplates']);
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
            return response()->json(['data' => $record, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
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
            return response()->json(['data' => $grid, 'message' => 'Cadastro atualizado com sucesso!']);

        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Grid  $grid
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grid $grid)
    {
        try {
            $this->service->delete($grid->id);
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Não é possível remover esta grade, pois ela está relacionada a uma turma.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
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
            return response()->json(['data' => $record->get()]);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=>$e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function removeTemplatesFromGrid(Grid $grid)
    {
        try {
            GridTemplate::where('grid_id', $grid->id)->forceDelete();
            return response()->json(['message' => 'Registros removidos com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
