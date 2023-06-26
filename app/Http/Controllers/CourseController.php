<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Course::query();
        $query->with('teaching');

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
     * @param CourseRequest $request
     * @return void
     */
    public function store(CourseRequest $request)
    {
        try {
            $record = Course::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Registro criado com sucesso!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=>$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        $course->teaching;
        return response()->json([$course]);
    }

    /**
     * Update the specified resource in storage.
     * @param CourseRequest $request
     * @param Course $course
     * @return void
     */
    public function update(CourseRequest $request, Course $course)
    {
        try {
            $course->update($request->all());
            return response()->json(['data'=>$course, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
