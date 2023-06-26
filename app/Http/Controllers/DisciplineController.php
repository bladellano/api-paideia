<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisciplineRequest;
use App\Models\Discipline;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Discipline::query();

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
     * @param DisciplineRequest $request
     * @return void
     */
    public function store(DisciplineRequest $request)
    {
        try {
            $record = Discipline::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Registro criado com sucesso!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=>$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discipline  $discipline
     * @return \Illuminate\Http\Response
     */
    public function show(Discipline $discipline)
    {
        return response()->json([$discipline]);
    }

    /**
     * Update the specified resource in storage.
     * @param DisciplineRequest $request
     * @param Discipline $discipline
     * @return void
     */
    public function update(DisciplineRequest $request, Discipline $discipline)
    {
        try {
            $discipline->update($request->all());
            return response()->json(['data'=> $discipline, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Discipline  $discipline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discipline $discipline)
    {
        $discipline->delete();
        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
