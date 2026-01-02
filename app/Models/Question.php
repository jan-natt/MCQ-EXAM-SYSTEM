<?php
// app/Models/Question.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'question_text',
        'marks',
        'question_type',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'marks' => 'decimal:2',
            'order' => 'integer',
        ];
    }

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class)->orderBy('order');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    // Get correct options
    public function correctOptions()
    {
        return $this->options()->where('is_correct', true);
    }

    // Check if question is single choice
    public function isSingleChoice(): bool
    {
        return $this->question_type === 'single';
    }

    // Check if question is multiple choice
    public function isMultipleChoice(): bool
    {
        return $this->question_type === 'multiple';
    }
}

