<?php
// app/Http/Controllers/Admin/QuestionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Services\QuestionService;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Show form to create new question
     */
    public function create(Exam $exam)
    {
        return view('admin.questions.create', compact('exam'));
    }

    /**
     * Store new question
     */
    public function store(StoreQuestionRequest $request)
    {
        $question = $this->questionService->createQuestion($request->validated());

        return redirect()
            ->route('admin.exams.show', $question->exam_id)
            ->with('success', 'Question added successfully!');
    }

    /**
     * Show form to edit question
     */
    public function edit(Question $question)
    {
        $question->load('options');
        $exam = $question->exam;

        return view('admin.questions.edit', compact('question', 'exam'));
    }

    /**
     * Update question
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $this->questionService->updateQuestion($question, $request->validated());

        return redirect()
            ->route('admin.exams.show', $question->exam_id)
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Delete question
     */
    public function destroy(Question $question)
    {
        $examId = $question->exam_id;
        $this->questionService->deleteQuestion($question);

        return redirect()
            ->route('admin.exams.show', $examId)
            ->with('success', 'Question deleted successfully!');
    }
}