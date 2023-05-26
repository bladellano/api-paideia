<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','workload','teaching_id'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:m:s',
    ];

    public function teaching()
    {
        return $this->belongsTo(Teaching::class);
    }

    public function grids()
    {
        return $this->hasMany(Grid::class);
    }

}
