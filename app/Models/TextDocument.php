<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextDocument extends Model
{
    protected $fillable = ['name','content','teaching_id'];

    use HasFactory;

    public function teaching()
    {
        return $this->belongsTo(Teaching::class);
    }

    public function getCreatedAtAttribute($value)
    {
        \Carbon\Carbon::setLocale('pt_BR');
        return Carbon::parse($value)->diffForHumans();
    }
}
