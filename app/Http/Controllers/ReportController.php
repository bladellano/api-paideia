<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public function generalReportOfStudents()
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST["start"];
        $searchValue = $_GET['search']['value'];
        $draw = $_REQUEST["draw"];
        $order = $_REQUEST['order'][0];

        $WHERE = "";

        if(!empty($searchValue)):
            $WHERE .= " WHERE TRUE ";
            $WHERE .= " AND s.name LIKE '".$searchValue."%' ";
            $WHERE .= " OR t.name LIKE '".$searchValue."%' ";
            $WHERE .= " OR p.name LIKE '".$searchValue."%' ";
            $WHERE .= " OR p.responsible LIKE '".$searchValue."%' ";
            $WHERE .= " OR g.name LIKE '".$searchValue."%' ";
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

        $SQL .= $WHERE;

        $SQL_TOTAL = $SQL;

        $LIMIT = " LIMIT " . $start . "," . $length;

        $SQL .= " ORDER BY ". ($order['column'] + 1) . " " . strtoupper($order['dir']);
        $SQL .= $LIMIT;

        $records = \DB::select($SQL);

        $totalRecords  = count(\DB::select($SQL_TOTAL));

        $response = [];
        $response["draw"] = intval($draw);
        $response["recordsTotal"] = $totalRecords;
        $response["recordsFiltered"] = $totalRecords;
        $response["data"] = $records;

        return $response;
    }

}
