<!-- resources/views/admin/questions/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pencil"></i> Edit Question
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.questions.update', $question) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="question_text" class="form-label">
                            Question <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                  id="question_text" name="question_text" rows="3" 
                                  required>{{ old('question_text', $question->question_text) }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="marks" class="form-label">
                                Marks <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.5" min="0.5" 
                                   class="form-control @error('marks') is-invalid @enderror" 
                                   id="marks" name="marks" 
                                   value="{{ old('marks', $question->marks) }}" required>
                            @error('marks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="question_type" class="form-label">
                                Question Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('question_type') is-invalid @enderror" 
                                    id="question_type" name="question_type" required>
                                <option value="single" {{ old('question_type', $question->question_type) == 'single' ? 'selected' : '' }}>
                                    Single Correct Answer
                                </option>
                                <option value="multiple" {{ old('question_type', $question->question_type) == 'multiple' ? 'selected' : '' }}>
                                    Multiple Correct Answers
                                </option>
                            </select>
                            @error('question_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">
                            Options <span class="text-danger">*</span>
                        </label>
                        
                        <div id="optionsContainer">
                            @foreach($question->options as $index => $option)
                                <div class="option-item mb-2">
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input type="checkbox" class="form-check-input"
                                                   name="options[{{ $index }}][is_correct]" value="{{ $index }}"
                                                   {{ old("options.{$index}.is_correct", $option->is_correct) ? 'checked' : '' }}>
                                        </div>
                                        <input type="text" class="form-control" 
                                               name="options[{{ $index }}][text]" 
                                               value="{{ old("options.{$index}.text", $option->option_text) }}"
                                               placeholder="Option {{ $index + 1 }}" required>
                                        @if($index >= 2)
                                            <button type="button" class="btn btn-outline-danger remove-option">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addOption">
                            <i class="bi bi-plus-circle"></i> Add Another Option
                        </button>
                        
                        @error('options')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let optionCount = {{ $question->options->count() }};

document.getElementById('addOption').addEventListener('click', function() {
    if (optionCount >= 10) {
        alert('Maximum 10 options allowed');
        return;
    }
    
    const container = document.getElementById('optionsContainer');
    const newOption = document.createElement('div');
    newOption.className = 'option-item mb-2';
    newOption.innerHTML = `
        <div class="input-group">
            <div class="input-group-text">
                <input type="checkbox" class="form-check-input"
                       name="options[${optionCount}][is_correct]" value="${optionCount}">
            </div>
            <input type="text" class="form-control"
                   name="options[${optionCount}][text]"
                   placeholder="Option ${optionCount + 1}">
            <button type="button" class="btn btn-outline-danger remove-option">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(newOption);
    optionCount++;
});

document.getElementById('optionsContainer').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-option') || e.target.parentElement.classList.contains('remove-option')) {
        const optionItem = e.target.closest('.option-item');
        optionItem.remove();
    }
});

// Handle question type change
document.getElementById('question_type').addEventListener('change', function() {
    const inputs = document.querySelectorAll('input[name*="is_correct"]');
    if (this.value === 'single') {
        inputs.forEach((input, index) => {
            input.type = 'radio';
            input.name = 'correct_option';
        });
    } else {
        inputs.forEach((input, index) => {
            input.type = 'checkbox';
            input.name = `options[${index}][is_correct]`;
        });
    }
});
</script>
@endpush