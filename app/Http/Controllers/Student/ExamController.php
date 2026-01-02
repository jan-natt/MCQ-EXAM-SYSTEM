<?php
// app/Http/Controllers/Student/ExamController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\ExamService;
use App\Services\ResultCalculationService;
use App\Http\Requests\SubmitExamRequest;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;
    protected $resultService;

    public function __construct(
        ExamService $examService,
        ResultCalculationService $resultService
    ) {
        $this->examService = $examService;
        $this->resultService = $resultService;
    }

    /**
     * Show exam details before starting
     */
    public function show(Exam $exam)
    {
        // Check if exam is active
        if (!$exam->is_active) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'This exam is currently not available.');
        }

        $exam->loadCount('questions');

        // Get student's previous attempts
        $previousAttempts = $this->examService->getStudentAttempts($exam, auth()->id());

        return view('student.exams.show', compact('exam', 'previousAttempts'));
    }

    /**
     * Start exam and create attempt
     */
    public function start(Exam $exam)
    {
        if (!$exam->is_active) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'This exam is not available.');
        }

        // Check if student has an in-progress attempt
        $inProgressAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', auth()->id())
            ->where('status', 'in_progress')
            ->first();

        if ($inProgressAttempt) {
            return redirect()->route('student.exams.take', $inProgressAttempt);
        }

        // Create new attempt
        $attempt = $this->examService->startExamAttempt($exam, auth()->id());

        return redirect()->route('student.exams.take', $attempt);
    }

    /**
     * Display exam taking interface
     */
    public function take(ExamAttempt $attempt)
    {
        // Ensure attempt belongs to authenticated student
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Redirect if already completed
        if ($attempt->isCompleted()) {
            return redirect()->route('student.results.show', $attempt);
        }

        // Load exam with questions and options
        $attempt->load(['exam.questions.options']);

        // Calculate remaining time if exam has time limit
        $remainingTime = null;
        if ($attempt->exam->hasTimeLimit()) {
            $elapsedMinutes = now()->diffInMinutes($attempt->started_at);
            $remainingTime = max(0, $attempt->exam->duration_minutes - $elapsedMinutes);

            // Auto-submit if time expired
            if ($remainingTime <= 0) {
                return $this->autoSubmit($attempt);
            }
        }

        return view('student.exams.take', compact('attempt', 'remainingTime'));
    }

    /**
     * Submit exam answers
     */
    public function submit(SubmitExamRequest $request, ExamAttempt $attempt)
    {
        // Ensure attempt belongs to authenticated student
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if already submitted
        if ($attempt->isCompleted()) {
            return redirect()
                ->route('student.results.show', $attempt)
                ->with('info', 'This exam has already been submitted.');
        }

        // Process submission and calculate results
        $answers = $request->validated()['answers'];
        $this->resultService->processSubmission($attempt, $answers);

        return redirect()
            ->route('student.results.show', $attempt)
            ->with('success', 'Exam submitted successfully!');
    }

    /**
     * Auto-submit when time expires
     */
    private function autoSubmit(ExamAttempt $attempt)
    {
        // Submit with no answers (or partial answers if any were saved)
        $this->resultService->processSubmission($attempt, []);

        return redirect()
            ->route('student.results.show', $attempt)
            ->with('warning', 'Time expired! Exam auto-submitted.');
    }
}

