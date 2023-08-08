<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name','start_date','end_date','polo_id','course_id','grid_id'];

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
}
