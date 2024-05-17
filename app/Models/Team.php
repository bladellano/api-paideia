<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date', 'polo_id', 'course_id', 'grid_id'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_teams');
    }

    public function grid()
    {
        return $this->belongsTo(Grid::class);
    }

    public function polo()
    {
        return $this->belongsTo(Polo::class);
    }

    protected $casts = [
        'start_date' => 'date:d/m/Y',
        'end_date' => 'date:d/m/Y',
    ];

    public function getCreatedAtAttribute($value)
    {
        \Carbon\Carbon::setLocale('pt_BR');
        return Carbon::parse($value)->diffForHumans();
    }

    public function getDisciplines($teamId)
    {
        $result = $this->select('disciplines.name AS discipline')
            ->join('grids', 'grids.id', '=', 'teams.grid_id')
            ->join('grid_templates', 'grid_templates.grid_id', '=', 'grids.id')
            ->join('disciplines', 'disciplines.id', '=', 'grid_templates.discipline_id')
            ->where('teams.id', $teamId)
            ->groupBy('disciplines.name')
            ->orderBy('disciplines.name', 'asc')
            ->get();

        $disciplines = $result->toArray();

        return array_column($disciplines, 'discipline');
    }

    public static function getStudentsByTeam($teamId)
    {
        $sql = "
            SELECT 
                LPAD(r.id, 6, '0') AS registration,
                s.name AS student,
                t.name AS team,
                g.name AS grid,
                __gt.course,
                __gt.teaching
            FROM 
                registrations r
            INNER JOIN 
                teams t ON t.id = r.team_id
            INNER JOIN 
                students s ON s.id = r.student_id
            INNER JOIN 
                grids g ON g.id = t.grid_id
            INNER JOIN (
                SELECT 
                    gt.grid_id, 
                    gt.course_id, 
                    c.name AS course, 
                    teaching.name AS teaching 
                FROM 
                    grid_templates gt
                INNER JOIN 
                    courses c ON c.id = gt.course_id
                INNER JOIN 
                    teachings teaching ON teaching.id = c.teaching_id
                GROUP BY 
                    gt.grid_id, 
                    gt.course_id, 
                    c.name,
                    teaching.name
            ) __gt ON __gt.grid_id = t.grid_id
            WHERE 
                r.team_id = ?
            ORDER BY 
                s.name ASC";

        return \DB::select($sql, [$teamId]);
    }
}
