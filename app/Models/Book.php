<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'book_number',
        'page_number',
        'issue_date',
        'certificate_seal_number',
        'history_seal_number',
        'student_id',
        'team_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
