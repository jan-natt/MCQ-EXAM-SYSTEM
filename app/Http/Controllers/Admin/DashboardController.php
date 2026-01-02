<?php

// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamAttempt;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_exams' => Exam::count(),
            'active_exams' => Exam::where('is_active', true)->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_attempts' => ExamAttempt::count(),
            'completed_attempts' => ExamAttempt::where('status', 'completed')->count(),
        ];

        $recentExams = Exam::with('creator')
            ->latest()
            ->take(5)
            ->get();

        $recentAttempts = ExamAttempt::with(['user', 'exam'])
            ->where('status', 'completed')
            ->latest('submitted_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentExams', 'recentAttempts'));
    }
}

