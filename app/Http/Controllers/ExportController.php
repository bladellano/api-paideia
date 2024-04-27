<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ClassDiaryExport;
use App\Models\Team;
use App\Exports\ClassStudentsPerClass;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function classDiary(Request $request)
    {
        $team = Team::findOrFail($request->team_id);
        $disciplines = $team->getDisciplines($request->team_id);

        return Excel::download(new ClassDiaryExport($request->team_id, $disciplines), __FUNCTION__ . "_" . \Str::random(10), \Maatwebsite\Excel\Excel::XLSX);
    }

    public function studentsPerClass(Request $request)
    {
        return Excel::download(new ClassStudentsPerClass($request->team_id, $request->extra_lines), __FUNCTION__ . "_" . \Str::random(5), \Maatwebsite\Excel\Excel::XLSX);
    }
}
