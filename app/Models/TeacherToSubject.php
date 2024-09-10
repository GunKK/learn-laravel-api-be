<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherToSubject extends Model
{
    use HasFactory;

    public $fillable = [
        'teacher_id', 'subject_id', 'semester', 'year', 'note',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
