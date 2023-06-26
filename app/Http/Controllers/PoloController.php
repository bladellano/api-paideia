<?php

namespace App\Http\Controllers;

use App\Models\Polo;
use Illuminate\Http\Request;
use App\Http\Requests\PoloRequest;

class PoloController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Polo::query();

        $search   = $request->input('search');
        $sortBy   = $request->input('sortBy');
        $sortDesc = $request->input('sortDesc');

        $perPage = $request->input('perPage') ?? 10;

        $page = $request->input('page') ?? 1;

        if ($search)
            $query->where('name', 'like', "%$search%");

        if ($sortBy)
            $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');

        $itens = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($itens);
    }

   /**
    * Store a newly created resource in storage.
    * @param PoloRequest $request
    * @return void
    */
    public function store(PoloRequest $request)
    {
        try {
            $record = Polo::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Registro criado com sucesso!'], 201);

        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=>$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\Polo  $polo
     * @return \Illuminate\Http\Response
     */
    public function show(Polo $polo)
    {
        return response()->json([$polo]);
    }

    /**
     * Update the specified resource in storage.
     * @param PoloRequest $request
     * @param Polo $polo
     * @return void
     */
    public function update(PoloRequest $request, Polo $polo)
    {
        try {
            $polo->update($request->all());
            return response()->json(['data'=>$polo, 'message' => 'Cadastro atualizado com sucesso!']);

        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Polo  $polo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Polo $polo)
    {
        $polo->delete();
        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
