<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'image', 'phone', 'cpf', 'rg', 'expedient_body', 'nationality', 'naturalness', 'name_mother', 'birth_date', 'gender'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
        'birth_date' => 'datetime:d/m/Y',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class, 'student_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'student_id')
            ->with('team:id,name')
            ->with('student:id,name')
            ->with('financials')
            ->with('user:id,name');
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'student_id');
    }
}
