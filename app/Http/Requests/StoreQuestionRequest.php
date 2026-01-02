<?php
// app/Http/Requests/StoreQuestionRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user() !== null && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'exam_id' => ['required', 'exists:exams,id'],
            'question_text' => ['required', 'string', 'max:5000'],
            'marks' => ['required', 'numeric', 'min:0.5', 'max:1000'],
            'question_type' => ['required', 'in:single,multiple'],
            'options' => ['required', 'array', 'min:2', 'max:10'],
            'options.*.text' => ['required', 'string', 'max:1000'],
            'options.*.is_correct' => ['sometimes', 'boolean'],
            'correct_option' => ['required_if:question_type,single', 'nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'question_text.required' => 'Question text is required.',
            'marks.required' => 'Marks for this question are required.',
            'marks.min' => 'Marks must be at least 0.5.',
            'options.required' => 'At least 2 options are required.',
            'options.min' => 'Minimum 2 options are required for each question.',
            'options.max' => 'Maximum 10 options are allowed per question.',
            'options.*.text.required' => 'Option text cannot be empty.',
            'options.*.is_correct.required' => 'Please specify if the option is correct.',
        ];
    }

    /**
     * Custom validation logic
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $options = $this->input('options', []);
            $questionType = $this->input('question_type');

            if ($questionType === 'single') {
                $correctOption = $this->input('correct_option');
                if ($correctOption === null || !isset($options[$correctOption])) {
                    $validator->errors()->add(
                        'correct_option',
                        'Please select the correct answer for single choice questions.'
                    );
                }
            } elseif ($questionType === 'multiple') {
                $correctCount = collect($options)->where('is_correct', true)->count();
                if ($correctCount < 1) {
                    $validator->errors()->add(
                        'options',
                        'Multiple choice questions must have at least one correct answer.'
                    );
                }
            }
        });
    }
}
