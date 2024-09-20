<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'email',
        'cnpj',
        'address',
        'phones',
        'owner',
        'slogan',
        'main_service',
        'website_name',
        'colored_logo',
        'black_white_logo'
    ];

    // Cast 'phones' as an array (JSON)
    protected $casts = [
        'phones' => 'array',
    ];
}
