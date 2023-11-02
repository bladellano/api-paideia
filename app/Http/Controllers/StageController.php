<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use App\Http\Requests\StageRequest;
use App\Http\Requests\StageRequestUpdate;
use App\Services\StageService;
use Illuminate\Http\Response;

class StageController extends Controller
{
    private $service;

    public function __construct(StageService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request);
    }

    public function store(StageRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return response()->json(['data'=> $data, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Stage $stage)
    {
        return $this->service->find($stage->id);
    }

    public function update(StageRequestUpdate $request, Stage $stage)
    {
        try {
            $data = $this->service->update($stage->id, $request->all());
            return response()->json(['data' => $data, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Stage $stage)
    {
        try {
            $this->service->delete($stage->id);
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'A remoção desse registro não é viável devido ao fato de que ele já está associado a outra tabela'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
