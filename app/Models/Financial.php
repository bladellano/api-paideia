<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'service_type_id',
        'value',
        'due_date',
        'pay_day',
        'paid',
        'observations',
        'gateway_response',
        'payment_type',
        'user_id'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class)
        ->with('team:id,name');
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (auth()->check())
                $course->user_id = auth()->id();
        });
    }

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:m:s',
        'due_date' => 'datetime:d/m/Y',
        'pay_day' => 'datetime:d/m/Y',
    ];
    
}
