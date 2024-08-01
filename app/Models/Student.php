<?php

namespace App\Models;

use Carbon\Carbon;
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

    // Acessor para a idade
    public function getAgeAttribute()
    {
        if ($this->birth_date) {
            $birthDate = Carbon::parse($this->birth_date);
            $now = Carbon::now();
            $years = $birthDate->diffInYears($now);
            $months = $birthDate->diffInMonths($now) % 12;
            $days = $birthDate->diffInDays($now) % 30;
            $hours = $birthDate->diffInHours($now) % 24;

            return "{$years} anos, {$months} meses, {$days} dias e {$hours} horas";
        }

        return null;
    }
}
