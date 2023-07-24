<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Http\Requests\DisciplineRequest;
use App\Services\DisciplineService;
use Illuminate\Http\Response;

class DisciplineController extends Controller
{
    private $service;

    public function __construct(DisciplineService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request,['teaching']);
    }

    public function store(DisciplineRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return response()->json(['data'=> $data, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Discipline $discipline)
    {
        return $this->service->find($discipline->id);
    }

    public function update(DisciplineRequest $request, Discipline $discipline)
    {
        try {
            $data = $this->service->update($discipline->id, $request->all());
            return response()->json(['data' => $data, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Discipline $discipline)
    {
        $this->service->delete($discipline->id);
        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
