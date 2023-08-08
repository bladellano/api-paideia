<?php

namespace App\Http\Controllers;

use App\Models\Teaching;
use Illuminate\Http\Request;
use App\Http\Requests\TeachingRequest;
use App\Services\TeachingService;
use Illuminate\Http\Response;

class TeachingController extends Controller
{
    private $service;

    public function __construct(TeachingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request);
    }

    public function store(TeachingRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return response()->json(['data'=> $data, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Teaching $teaching)
    {
        return $this->service->find($teaching->id);
    }

    public function update(TeachingRequest $request, Teaching $teaching)
    {
        try {
            $data = $this->service->update($teaching->id, $request->all());
            return response()->json(['data' => $data, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Teaching $teaching)
    {
        try {
            $this->service->delete($teaching->id);
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'A remoção desse registro não é viável devido ao fato de que ele já está associado a outra tabela'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
