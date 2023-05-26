<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discipline extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','amount_of_reviews','workload'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:m:s',
    ];

}
