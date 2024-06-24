<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'team_id', 'user_id', 'ativo',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:m:s',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function financials()
    {
        return $this->hasMany(Financial::class, 'registration_id')
            ->with('user:id,name')
            ->with('paymentType:id,name')
            ->with('serviceType:id,name')
            ->orderBy('service_type_id', 'desc')
            ->orderBy('due_date', 'asc');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {

            $existingRegistration = self::where([
                'student_id' => $registration->student_id,
                'team_id' => $registration->team_id,
            ])->first();

            if ($existingRegistration) {
                // Se já existir, impede a criação do novo registro
                return false;
            }

            if (auth()->check())
                $registration->user_id = auth()->id();
        });
    }
}
