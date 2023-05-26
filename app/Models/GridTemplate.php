<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GridTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['grid_id','workload','course_id','stage_id','discipline_id'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:m:s',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function grid()
    {
        return $this->belongsTo(Grid::class);
    }
}
