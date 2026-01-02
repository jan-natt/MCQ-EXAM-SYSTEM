<?php
// app/Http/Requests/UpdateQuestionRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    protected function prepareForValidation()
    {
        if ($this->input('question_type') === 'single') {
            $correctIndex = $this->input('correct_option');
            $options = $this->input('options', []);
            foreach ($options as $i => &$option) {
                $option['is_correct'] = ((string)$i === (string)$correctIndex);
            }
            $this->merge(['options' => $options]);
        }
    }

    public function rules(): array
    {
        return [
            'question_text' => ['required', 'string', 'max:5000'],
            'marks' => ['required', 'numeric', 'min:0.5', 'max:1000'],
            'question_type' => ['required', 'in:single,multiple'],
            'options' => ['required', 'array', 'min:2', 'max:10'],
            'options.*.text' => ['required', 'string', 'max:1000'],
            'options.*.is_correct' => ['required', 'boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $options = $this->input('options', []);
            $correctCount = collect($options)->where('is_correct', true)->count();

            if ($this->input('question_type') === 'single' && $correctCount !== 1) {
                $validator->errors()->add(
                    'options',
                    'Single choice questions must have exactly one correct answer.'
                );
            }

            if ($this->input('question_type') === 'multiple' && $correctCount < 1) {
                $validator->errors()->add(
                    'options',
                    'Multiple choice questions must have at least one correct answer.'
                );
            }
        });
    }
}
