<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeachingRequest;
use App\Models\Teaching;
use Illuminate\Http\Request;

class TeachingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Teaching::query();

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
     * @param TeachingRequest $request
     * @return void
     */
    public function store(TeachingRequest $request)
    {
        try {
            $record = Teaching::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Registro criado com sucesso!'], 201);

        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=>$e->getMessage()], 500);
        }
    }

   /**
   * Display the specified resource.
   * @param Teaching $teaching
   * @return void
   */
    public function show(Teaching $teaching)
    {
        return response()->json([$teaching]);
    }

    /**
     * Update the specified resource in storage.
     * @param TeachingRequest $request
     * @param Teaching $teaching
     * @return void
     */
    public function update(TeachingRequest $request, Teaching $teaching)
    {
        try {
            $teaching->update($request->all());
            return response()->json(['data'=>$teaching, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teaching $teaching)
    {
        $teaching->delete();
        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
