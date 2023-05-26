<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTeam extends Model
{
    use HasFactory;

    protected $fillable = ['student_id','team_id'];

}
