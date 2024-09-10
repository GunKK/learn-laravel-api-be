<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'note',
    ];

    public function teacherToSubject(): HasMany
    {
        return $this->hasMany(TeacherToSubject::class);
    }
}
