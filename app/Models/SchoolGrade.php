<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'discipline_id',
        'stage_id',
        'team_id',
        'grade',
    ];

    /**
     * Mutator para formatar o valor de grade com duas casas decimais.
     *
     * @param  string  $value
     * @return float
     */
    public function getGradeAttribute($value)
    {
        return number_format($value, 1);
    }

    public static function getGrade($studentId)
    {
        return self::where('student_id', $studentId)->get();
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
