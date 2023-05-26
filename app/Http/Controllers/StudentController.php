<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\StudentRequest;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Student::query();
        $query->with('teams');

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
     * @param StudentRequest $request
     * @return void
     */
    public function store(StudentRequest $request)
    {
        try {
            $record = Student::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Record successfully created!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=>$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     * @param Student $student
     * @return void
     */
    public function show( Student $student)
    {
        $student->teams;
        return response()->json([$student]);
    }

    /**
     * Update the specified resource in storage.
    * @param StudentRequest $request
    * @param Student $student
    * @return void
    */
    public function update(StudentRequest $request, Student $student)
    {
        try {
            $student->update($request->all());
            return response()->json(['data'=>$student, 'message' => 'Registration successfully updated!']);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Record removed successfully.']);
    }
}
