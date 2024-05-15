<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\StudentService;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\StudentUpdateRequest;

class StudentController extends Controller
{
    private $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request, ['registrations']);
    }

    public function store(StudentRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return response()->json(['data'=> $data, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Student $student)
    {
        return $this->service->find($student->id);
    }

    public function update(StudentUpdateRequest $request, Student $student)
    {
        try {
            $data = $this->service->update($student->id, $request->all());
            return response()->json(['data' => $data, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Student $student)
    {
        try {
            $this->service->delete($student->id);
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'A remoção desse registro não é viável devido ao fato de que ele já está associado a outra tabela'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
