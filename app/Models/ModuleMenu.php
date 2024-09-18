<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleMenu extends Model
{
    use HasFactory;

    protected $table = "module_menu";

    protected $fillable = ['module_id', 'action', 'path'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
