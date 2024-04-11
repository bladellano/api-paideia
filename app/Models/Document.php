<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'code', 'type', 'student_id'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:m:s',
    ];

    protected $appends = ['file_name', 'folder']; // Adiciona o accessor virtual aos atributos

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getFileNameAttribute()
    {
        if (!empty($this->path)) {
            $pathParts = explode('/', $this->path);
            return end($pathParts);
        } else {
            return null;
        }
    }

    public function getFolderAttribute()
    {
        if (!empty($this->path)) {
            $pathParts = explode('/', $this->path);
            return current($pathParts);
        } else {
            return null;
        }
    }
}
