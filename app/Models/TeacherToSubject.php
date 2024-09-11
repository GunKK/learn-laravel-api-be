<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherToSubject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $fillable = [
        'teacher_id',
        'subject_id',
        'semester',
        'year',
        'note',
    ];

    // scope filter transcripts
    public function scopeTranscriptsFilter($query, $request)
    {
        $year = $request->query('year');
        $semester = $request->query('semester');
        $teacherId = $request->query('teacher_id');
        $subjectId = $request->query('subject_id');

        if (!is_null($year)) {
            $query->where('year', $year);
        }
        if (!is_null($semester)) {
            $query->where('semester', $semester);
        }
        if (!is_null($teacherId)) {
            $query->where('teacher_id', $teacherId);
        }
        if (!is_null($subjectId)) {
            $query->where('subject_id', $subjectId);
        }
    }

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
