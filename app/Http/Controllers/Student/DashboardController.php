<?php

// app/Http/Controllers/Student/DashboardController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        // Get available active exams
        $availableExams = Exam::with('questions')
            ->where('is_active', true)
            ->withCount('questions')
            ->latest()
            ->get();

        // Get student's recent attempts
        $recentAttempts = ExamAttempt::with('exam')
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        // Calculate statistics
        $stats = [
            'total_exams_taken' => ExamAttempt::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->count(),
            'average_score' => ExamAttempt::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->avg('obtained_marks'),
            'exams_passed' => ExamAttempt::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->get()
                ->filter(fn($attempt) => $attempt->hasPassed())
                ->count(),
        ];

        return view('student.dashboard', compact('availableExams', 'recentAttempts', 'stats'));
    }
}

