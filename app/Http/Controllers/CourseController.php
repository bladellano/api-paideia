<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Services\CourseService;
use Illuminate\Http\Response;

class CourseController extends Controller
{
    private $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request, ['teaching']);
    }

    public function store(CourseRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return response()->json(['data'=> $data, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Course $course)
    {
        return $this->service->find($course->id);
    }

    public function update(CourseRequest $request, Course $course)
    {
        try {
            $data = $this->service->update($course->id, $request->all());
            return response()->json(['data' => $data, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Course $course)
    {
        $this->service->delete($course->id);
        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
