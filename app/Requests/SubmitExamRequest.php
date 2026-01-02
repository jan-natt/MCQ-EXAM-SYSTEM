<?php
// app/Http/Requests/SubmitExamRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ExamAttempt;

class SubmitExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Check if user is student and attempt belongs to them
        if (!auth()->check() || !auth()->user()->isStudent()) {
            return false;
        }

        $attemptId = $this->route('attempt');
        $attempt = ExamAttempt::find($attemptId);

        return $attempt && $attempt->user_id === auth()->id() && $attempt->isInProgress();
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array'],
            'answers.*' => ['array'], // Each question can have multiple selected options
            'answers.*.*' => ['exists:options,id'], // Each option must exist
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'Please select at least one answer before submitting.',
            'answers.*.*.exists' => 'Invalid option selected.',
        ];
    }
}