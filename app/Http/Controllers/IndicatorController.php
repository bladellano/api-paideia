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
}
