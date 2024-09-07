<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class IndicatorController extends Controller
{
    public function numberRegistrationsPerMonth()
    {
        $query = DB::table('registrations')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') AS month"), DB::raw("COUNT(*) AS total_registrations"))
            ->whereNotNull('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"));

        return $query->get();
    }

    public function numberStudentsPerMonth()
    {
        $query = DB::table('students')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') AS month"), DB::raw("COUNT(*) AS total_students"))
            ->whereNotNull('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"));

        return $query->get();
    }

    public function numberStudentsPerTeams()
    {
        return DB::table('registrations as r')
            ->join('teams as t', 't.id', '=', 'r.team_id')
            ->select('t.name', DB::raw('count(*) as total_students'))
            ->groupBy('t.name', 'r.team_id')
            ->orderBy('t.name', 'asc')
            ->get();
    }
}
