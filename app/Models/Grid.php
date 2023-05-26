<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grid extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','obs'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:m:s',
    ];

    public function gridTemplates()
    {
        return $this->hasMany(GridTemplate::class);
    }


}
