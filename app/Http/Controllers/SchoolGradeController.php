<?php

namespace App\Http\Controllers;

use App\Models\SchoolGrade;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SchoolGradeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {

            $grades = (new \App\Services\SchoolGradeService)->execute($request);

            return response()->json(['data' => ['total' => $grades['total']], 'message' => 'Nota(s) criado/atualizado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {

            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getGradeByStudent($studentId)
    {
        return SchoolGrade::getGrade($studentId);
    }
}
