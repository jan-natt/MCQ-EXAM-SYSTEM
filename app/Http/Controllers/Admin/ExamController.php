<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\ExamService;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    /**
     * Display listing of all exams
     */
    public function index()
    {
        $exams = Exam::with('creator')
            ->withCount('questions')
            ->latest()
            ->paginate(15);

        return view('admin.exams.index', compact('exams'));
    }

    /**
     * Show form to create new exam
     */
    public function create()
    {
        return view('admin.exams.create');
    }

    /**
     * Store new exam
     */
    public function store(StoreExamRequest $request)
    {
        $exam = $this->examService->createExam($request->validated());

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('success', 'Exam created successfully! Now add questions.');
    }

    /**
     * Show single exam with questions
     */
    public function show(Exam $exam)
    {
        $exam->load(['questions.options', 'creator']);
        
        return view('admin.exams.show', compact('exam'));
    }

    /**
     * Show form to edit exam
     */
    public function edit(Exam $exam)
    {
        return view('admin.exams.edit', compact('exam'));
    }

    /**
     * Update exam
     */
    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $this->examService->updateExam($exam, $request->validated());

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('success', 'Exam updated successfully!');
    }

    /**
     * Delete exam
     */
    public function destroy(Exam $exam)
    {
        $this->examService->deleteExam($exam);

        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'Exam deleted successfully!');
    }

    /**
     * Toggle exam active status
     */
    public function toggleStatus(Exam $exam)
    {
        $exam->update(['is_active' => !$exam->is_active]);

        $status = $exam->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Exam {$status} successfully!");
    }

    /**
     * View exam statistics
     */
    public function statistics(Exam $exam)
    {
        $exam->load(['attempts' => function($query) {
            $query->where('status', 'completed');
        }]);

        $stats = [
            'total_attempts' => $exam->attempts->count(),
            'average_score' => $exam->attempts->avg('obtained_marks'),
            'highest_score' => $exam->attempts->max('obtained_marks'),
            'lowest_score' => $exam->attempts->min('obtained_marks'),
            'pass_rate' => $this->calculatePassRate($exam),
        ];

        return view('admin.exams.statistics', compact('exam', 'stats'));
    }

    private function calculatePassRate(Exam $exam): float
    {
        if ($exam->attempts->isEmpty() || !$exam->passing_marks) {
            return 0;
        }

        $passedCount = $exam->attempts->where('obtained_marks', '>=', $exam->passing_marks)->count();
        return round(($passedCount / $exam->attempts->count()) * 100, 2);
    }
}

