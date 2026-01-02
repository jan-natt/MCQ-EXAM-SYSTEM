<?php
// app/Services/ResultCalculationService.php

namespace App\Services;

use App\Models\ExamAttempt;
use App\Models\StudentAnswer;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class ResultCalculationService
{
    /**
     * Process exam submission and calculate results
     * 
     * Logic:
     * 1. For single choice questions: Give marks only if the correct option is selected
     * 2. For multiple choice questions: Give marks only if ALL correct options are selected
     *    and NO incorrect options are selected
     */
    public function processSubmission(ExamAttempt $attempt, array $answers): ExamAttempt
    {
        return DB::transaction(function () use ($attempt, $answers) {
            $totalObtainedMarks = 0;

            // Get all questions for this exam
            $questions = $attempt->exam->questions()->with('options')->get();

            foreach ($questions as $question) {
                $selectedOptionIds = $answers[$question->id] ?? [];
                
                // Convert to array if single value
                if (!is_array($selectedOptionIds)) {
                    $selectedOptionIds = [$selectedOptionIds];
                }

                $marksForQuestion = $this->calculateMarksForQuestion(
                    $question,
                    $selectedOptionIds
                );

                $totalObtainedMarks += $marksForQuestion;

                // Save each selected answer
                foreach ($selectedOptionIds as $optionId) {
                    $option = $question->options->firstWhere('id', $optionId);
                    
                    if ($option) {
                        StudentAnswer::create([
                            'exam_attempt_id' => $attempt->id,
                            'question_id' => $question->id,
                            'option_id' => $optionId,
                            'is_correct' => $option->is_correct,
                            // Marks distributed only if entire question is correct
                            'marks_obtained' => $marksForQuestion,
                        ]);
                    }
                }
            }

            // Update attempt with results
            $attempt->update([
                'submitted_at' => now(),
                'obtained_marks' => $totalObtainedMarks,
                'status' => 'completed',
            ]);

            return $attempt->fresh();
        });
    }

    /**
     * Calculate marks for a single question
     * 
     * Returns full marks only if answer is completely correct:
     * - Single choice: The one correct option is selected
     * - Multiple choice: All correct options selected, no incorrect ones
     */
    private function calculateMarksForQuestion(Question $question, array $selectedOptionIds): float
    {
        // Get correct option IDs
        $correctOptionIds = $question->options()
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();

        // Sort arrays for comparison
        sort($selectedOptionIds);
        sort($correctOptionIds);

        // Check if selected answers match correct answers exactly
        if ($selectedOptionIds === $correctOptionIds) {
            return (float) $question->marks;
        }

        return 0; // No partial marks
    }

    /**
     * Get detailed results for an attempt
     */
    public function getDetailedResults(ExamAttempt $attempt): array
    {
        $attempt->load(['exam.questions.options', 'studentAnswers']);

        $questionResults = [];

        foreach ($attempt->exam->questions as $question) {
            $studentAnswers = $attempt->studentAnswers
                ->where('question_id', $question->id);

            $selectedOptionIds = $studentAnswers->pluck('option_id')->toArray();
            $correctOptionIds = $question->options
                ->where('is_correct', true)
                ->pluck('id')
                ->toArray();

            sort($selectedOptionIds);
            sort($correctOptionIds);

            $isCorrect = $selectedOptionIds === $correctOptionIds;

            $questionResults[] = [
                'question' => $question,
                'selected_options' => $selectedOptionIds,
                'correct_options' => $correctOptionIds,
                'is_correct' => $isCorrect,
                'marks_obtained' => $isCorrect ? $question->marks : 0,
            ];
        }

        return [
            'attempt' => $attempt,
            'question_results' => $questionResults,
            'total_questions' => count($questionResults),
            'correct_answers' => collect($questionResults)->where('is_correct', true)->count(),
            'incorrect_answers' => collect($questionResults)->where('is_correct', false)->count(),
        ];
    }
}