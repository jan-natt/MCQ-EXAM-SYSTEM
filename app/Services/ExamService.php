<?php

// app/Services/ExamService.php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class ExamService
{
    /**
     * Create a new exam
     */
    public function createExam(array $data): Exam
    {
        return Exam::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'passing_marks' => $data['passing_marks'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Update existing exam
     */
    public function updateExam(Exam $exam, array $data): bool
    {
        return $exam->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'passing_marks' => $data['passing_marks'] ?? null,
            'is_active' => $data['is_active'] ?? $exam->is_active,
        ]);
    }

    /**
     * Delete exam and all related data
     */
    public function deleteExam(Exam $exam): bool
    {
        // Cascade delete will handle questions, options, attempts, and answers
        return $exam->delete();
    }

    /**
     * Start a new exam attempt for a student
     */
    public function startExamAttempt(Exam $exam, int $userId): ExamAttempt
    {
        // Calculate total marks from all questions
        $totalMarks = $exam->questions()->sum('marks');

        return ExamAttempt::create([
            'user_id' => $userId,
            'exam_id' => $exam->id,
            'started_at' => now(),
            'total_marks' => $totalMarks,
            'status' => 'in_progress',
        ]);
    }

    /**
     * Check if student can retake exam
     * (Optional: Can be configured to allow/disallow retakes)
     */
    public function canRetakeExam(Exam $exam, int $userId): bool
    {
        // For now, allow retakes
        // You can add business logic here to limit attempts
        return true;
    }

    /**
     * Get student's previous attempts for an exam
     */
    public function getStudentAttempts(Exam $exam, int $userId)
    {
        return ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}



