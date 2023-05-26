<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teaching extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','description'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:m:s',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
