<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polo extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:m:s',
    ];

    protected $fillable = ['name','city','uf','responsible','address','email','phone'];
}
