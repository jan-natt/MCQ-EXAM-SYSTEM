<?php
// app/Services/QuestionService.php

namespace App\Services;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class QuestionService
{
    /**
     * Create a new question with options
     */
    public function createQuestion(array $data): Question
    {
        return DB::transaction(function () use ($data) {
            // Create the question
            $question = Question::create([
                'exam_id' => $data['exam_id'],
                'question_text' => $data['question_text'],
                'marks' => $data['marks'],
                'question_type' => $data['question_type'],
                'order' => $data['order'] ?? null,
            ]);

            // Create options
            foreach ($data['options'] as $index => $optionData) {
                $isCorrect = false;
                if ($data['question_type'] === 'single') {
                    // For single choice, check if this index matches correct_option
                    $isCorrect = isset($data['correct_option']) && $data['correct_option'] == $index;
                } else {
                    // For multiple choice, use the is_correct value
                    $isCorrect = $optionData['is_correct'] ?? false;
                }

                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['text'],
                    'is_correct' => $isCorrect,
                    'order' => $index + 1,
                ]);
            }

            return $question->load('options');
        });
    }

    /**
     * Update existing question and options
     */
    public function updateQuestion(Question $question, array $data): Question
    {
        return DB::transaction(function () use ($question, $data) {
            // Update question
            $question->update([
                'question_text' => $data['question_text'],
                'marks' => $data['marks'],
                'question_type' => $data['question_type'],
                'order' => $data['order'] ?? $question->order,
            ]);

            // Delete existing options
            $question->options()->delete();

            // Create new options
            foreach ($data['options'] as $index => $optionData) {
                $isCorrect = false;
                if ($data['question_type'] === 'single') {
                    // For single choice, check if this index matches correct_option
                    $isCorrect = isset($data['correct_option']) && $data['correct_option'] == $index;
                } else {
                    // For multiple choice, use the is_correct value
                    $isCorrect = $optionData['is_correct'] ?? false;
                }

                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['text'],
                    'is_correct' => $isCorrect,
                    'order' => $index + 1,
                ]);
            }

            return $question->fresh()->load('options');
        });
    }

    /**
     * Delete question (cascade will delete options)
     */
    public function deleteQuestion(Question $question): bool
    {
        return $question->delete();
    }

    /**
     * Reorder questions within an exam
     */
    public function reorderQuestions(array $questionOrders): void
    {
        DB::transaction(function () use ($questionOrders) {
            foreach ($questionOrders as $questionId => $order) {
                Question::where('id', $questionId)->update(['order' => $order]);
            }
        });
    }
}