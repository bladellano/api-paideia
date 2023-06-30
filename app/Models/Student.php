<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','email','phone','cpf','rg','expedient_body','nationality','naturalness','name_mother','birth_date','gender'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
        'birth_date' => 'datetime:d/m/Y',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'student_teams')
        ->withTimestamps()
        ->wherePivot('deleted_at', null);
    }

}
