<?php
// app/Models/ExamAttempt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'started_at',
        'submitted_at',
        'total_marks',
        'obtained_marks',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'total_marks' => 'decimal:2',
            'obtained_marks' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    // Check if attempt is completed
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // Check if attempt is in progress
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    // Calculate percentage
    public function getPercentageAttribute()
    {
        if ($this->total_marks == 0) {
            return 0;
        }
        return round(($this->obtained_marks / $this->total_marks) * 100, 2);
    }

    // Check if passed
    public function hasPassed(): bool
    {
        if (is_null($this->exam->passing_marks)) {
            return true; // No passing marks set
        }
        return $this->obtained_marks >= $this->exam->passing_marks;
    }

    // Scope for completed attempts
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}

