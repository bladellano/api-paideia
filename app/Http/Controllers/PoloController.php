<?php

namespace App\Http\Controllers;

use App\Models\Polo;
use Illuminate\Http\Request;
use App\Http\Requests\PoloRequest;
use App\Services\PoloService;
use Illuminate\Http\Response;

class PoloController extends Controller
{
    private $service;

    public function __construct(PoloService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request);
    }

    public function store(PoloRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return response()->json(['data'=> $data, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Polo $polo)
    {
        return $this->service->find($polo->id);
    }

    public function update(PoloRequest $request, Polo $polo)
    {
        try {
            $data = $this->service->update($polo->id, $request->all());
            return response()->json(['data' => $data, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Polo $polo)
    {
        $this->service->delete($polo->id);
        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
