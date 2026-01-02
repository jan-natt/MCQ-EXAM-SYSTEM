<?php
// app/Http/Controllers/Student/ResultController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use App\Services\ResultCalculationService;

class ResultController extends Controller
{
    protected $resultService;

    public function __construct(ResultCalculationService $resultService)
    {
        $this->resultService = $resultService;
    }

    /**
     * Show result for a specific attempt
     */
    public function show(ExamAttempt $attempt)
    {
        // Ensure attempt belongs to authenticated student
        if ($attempt->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this result.');
        }

        // Redirect if not completed
        if (!$attempt->isCompleted()) {
            return redirect()
                ->route('student.exams.take', $attempt)
                ->with('info', 'Please complete the exam first.');
        }

        // Get detailed results
        $results = $this->resultService->getDetailedResults($attempt);

        return view('student.results.show', $results);
    }

    /**
     * Show all results history for student
     */
    public function index()
    {
        $attempts = ExamAttempt::with('exam')
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->latest('submitted_at')
            ->paginate(15);

        return view('student.results.index', compact('attempts'));
    }
}