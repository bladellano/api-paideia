<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public function generalReportOfStudents()
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST["start"];

        $WHERE = " WHERE TRUE ";

        if(!empty($_REQUEST['search']['value'])):
            $WHERE .= " AND s.name LIKE '".$_REQUEST['search']['value']."%' ";
            $WHERE .= " OR t.name LIKE '".$_REQUEST['search']['value']."%' ";
            $WHERE .= " OR p.name LIKE '".$_REQUEST['search']['value']."%' ";
            $WHERE .= " OR p.responsible LIKE '".$_REQUEST['search']['value']."%' ";
            $WHERE .= " OR g.name LIKE '".$_REQUEST['search']['value']."%' ";
        endif;

        $SQL = '
        SELECT
        s.id as student_id,
        s.name,
        s.email,
        t.name as team,
        p.name as polo,
        p.responsible,
        g.name as grid
        FROM students s
            LEFT JOIN (SELECT * FROM student_teams WHERE registered = 1) ST ON ST.student_id = s.id
            LEFT JOIN teams t ON t.id = ST.team_id
            LEFT JOIN polos p ON p.id = t.polo_id
            LEFT JOIN grids g ON g.id = t.grid_id ';

        $SQL_TOTAL = $SQL;

        $SQL .= $WHERE;
        $SQL .= " ORDER BY ". ($_REQUEST['order'][0]['column'] + 1) . " " . strtoupper($_REQUEST['order'][0]['dir']);

        $LIMIT = " LIMIT " . $start . "," . $length;
        $LIMIT = ($length < 0) ? "" : $LIMIT;

        $records = \DB::select($SQL . $LIMIT);

        $totalQuery  = count(\DB::select($SQL_TOTAL));
        $lengthQuery = count($records);

        $response = [];
        $response["draw"] = $_REQUEST["draw"];
        $response["length"] = $lengthQuery;
        $response["recordsTotal"] = $totalQuery;
        $response["recordsFiltered"] = $totalQuery;
        $response["data"] = $records;

        return $response;

    }

}
