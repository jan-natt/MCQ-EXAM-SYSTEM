<?php
// app/Models/StudentAnswer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_attempt_id',
        'question_id',
        'option_id',
        'is_correct',
        'marks_obtained',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'marks_obtained' => 'decimal:2',
        ];
    }

    // Relationships
    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}