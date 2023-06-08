<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['path','code','type','student_id'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
