<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'teacher_code',
        'department',
        'faculty',
        'address',
        'phone',
        'note',
    ];

    /**
     * Attributes
     *
     * Get the student's full name
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['last_name'] . $attributes['first_name']
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacherToSubjects(): HasMany
    {
        return $this->hasMany(TeacherToSubject::class);
    }
}
