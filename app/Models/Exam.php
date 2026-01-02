<?php

// app/Models/Exam.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'passing_marks',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'duration_minutes' => 'integer',
            'passing_marks' => 'decimal:2',
        ];
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    // Calculated total marks
    public function getTotalMarksAttribute()
    {
        return $this->questions()->sum('marks');
    }

    // Check if exam has time limit
    public function hasTimeLimit(): bool
    {
        return !is_null($this->duration_minutes);
    }

    // Scope for active exams
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

