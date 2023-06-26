<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use App\Http\Requests\StageRequest;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Stage::query();

        $search   = $request->input('search');
        $sortBy   = $request->input('sortBy');
        $sortDesc = $request->input('sortDesc');

        $perPage = $request->input('perPage') ?? 10;

        $page = $request->input('page') ?? 1;

        if ($search) {
            $query->where('stage', 'like', "%$search%");
        }

        if ($sortBy) {
            $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');
        }

        $itens = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($itens);
    }

    /**
     * Store a newly created resource in storage.
     * @param StageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StageRequest $request)
    {
        try {
            $record = Stage::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Registro criado com sucesso!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=>$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function show(Stage $stage)
    {
        return response()->json([$stage]);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request  $request
     * @param Request $stage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stage $stage)
    {
        try {
            $stage->update($request->all());
            return response()->json(['data'=> $stage, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stage $stage)
    {
        $stage->forceDelete();

        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
