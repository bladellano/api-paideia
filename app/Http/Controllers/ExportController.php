<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ClassDiaryExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function classDiary(Request $request)
    {

        //! @TODO criar um metodo nos servicos de turma...
        $sql = 'SELECT d.name AS dicipline
                FROM teams t 
                    INNER join grids g ON g.id = t.grid_id 
                    INNER join grid_templates gt ON gt.grid_id = g.id 
                    INNER join disciplines d ON d.id = gt.discipline_id 
                    WHERE t.id = ' . $request->team_id . '  GROUP BY d.name ORDER BY d.name ASC';

        $records = \DB::select($sql);

        $aMap = array_map(fn ($object) => (array)$object, $records);

        $disciplines = array_column($aMap, 'dicipline');

        return Excel::download(new ClassDiaryExport($request->team_id, $disciplines), __FUNCTION__ . '.xlsx');
    }
}
