
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Add Question to: {{ $exam->title }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.questions.store') }}" method="POST" id="questionForm">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                    
                    <div class="mb-3">
                        <label for="question_text" class="form-label">
                            Question <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                  id="question_text" name="question_text" rows="3" 
                                  required>{{ old('question_text') }}</textarea>
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
                                   id="marks" name="marks" value="{{ old('marks', 1) }}" required>
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
                                <option value="single" {{ old('question_type') == 'single' ? 'selected' : '' }}>
                                    Single Correct Answer
                                </option>
                                <option value="multiple" {{ old('question_type') == 'multiple' ? 'selected' : '' }}>
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
                            <small class="text-muted">(Minimum 2 options required)</small>
                        </label>

                        @php
                            $oldOptions = old('options', array_fill(0, 4, ['text' => '', 'is_correct' => false]));
                            $initialOptionCount = count($oldOptions);
                        @endphp

                        <div id="optionsContainer" data-initial-count="{{ $initialOptionCount }}">
                            @foreach($oldOptions as $index => $option)
                                <div class="option-item mb-2">
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            @if(old('question_type') == 'single')
                                                <input type="radio" class="form-check-input"
                                                       name="correct_option" value="{{ $index }}"
                                                       {{ (old('correct_option') == $index || (!old('correct_option') && $index == 0)) ? 'checked' : '' }}>
                                            @else
                                                <input type="hidden" name="options[{{ $index }}][is_correct]" value="0">
                                                <input type="checkbox" class="form-check-input"
                                                       name="options[{{ $index }}][is_correct]" value="1"
                                                       {{ $option['is_correct'] ? 'checked' : '' }}>
                                            @endif
                                        </div>
                                        <input type="text" class="form-control"
                                               name="options[{{ $index }}][text]"
                                               value="{{ $option['text'] }}"
                                               placeholder="Option {{ $index + 1 }}"
                                               {{ $index < 2 ? 'required' : '' }}>
                                        @if($index >= 2)
                                            <button type="button" class="btn btn-outline-danger remove-option">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                    @error("options.{$index}.text")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addOption">
                            <i class="bi bi-plus-circle"></i> Add Another Option
                        </button>
                        
                        @error('options')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror

                        @error('correct_option')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        
                        <small class="d-block mt-2 text-muted">
                            <i class="bi bi-info-circle"></i> Check the box to mark an option as correct
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let optionCount = parseInt(document.getElementById('optionsContainer').dataset.initialCount);

document.getElementById('addOption').addEventListener('click', function() {
    if (optionCount >= 10) {
        alert('Maximum 10 options allowed');
        return;
    }

    const questionType = document.getElementById('question_type').value;
    const container = document.getElementById('optionsContainer');
    const newOption = document.createElement('div');
    newOption.className = 'option-item mb-2';

    let inputHtml;
    if (questionType === 'single') {
        inputHtml = `<input type="radio" class="form-check-input" name="correct_option" value="${optionCount}">`;
    } else {
        inputHtml = `<input type="hidden" name="options[${optionCount}][is_correct]" value="0">
                     <input type="checkbox" class="form-check-input" name="options[${optionCount}][is_correct]" value="1">`;
    }

    newOption.innerHTML = `
        <div class="input-group">
            <div class="input-group-text">
                ${inputHtml}
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
    const container = document.getElementById('optionsContainer');
    const optionItems = container.querySelectorAll('.option-item');

    // Collect current checked states
    const checkedIndices = [];
    optionItems.forEach((item, index) => {
        const input = item.querySelector('.input-group-text input[type="radio"], .input-group-text input[type="checkbox"]');
        if (input && input.checked) {
            checkedIndices.push(index);
        }
    });

    // Change types and renumber
    optionItems.forEach((item, index) => {
        const inputGroupText = item.querySelector('.input-group-text');
        let input = inputGroupText.querySelector('input[type="radio"], input[type="checkbox"]');

        if (this.value === 'single') {
            // Remove hidden input if exists
            const hiddenInput = inputGroupText.querySelector('input[type="hidden"]');
            if (hiddenInput) hiddenInput.remove();

            // Change to radio
            if (input) {
                input.type = 'radio';
                input.name = 'correct_option';
                input.value = index;
                input.checked = false; // Will set later
            }
        } else {
            // Add hidden input if not exists
            let hiddenInput = inputGroupText.querySelector('input[type="hidden"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.value = '0';
                inputGroupText.insertBefore(hiddenInput, inputGroupText.firstChild);
            }
            hiddenInput.name = `options[${index}][is_correct]`;

            // Change to checkbox
            if (input) {
                input.type = 'checkbox';
                input.name = `options[${index}][is_correct]`;
                input.value = '1';
                input.checked = false; // Will set later
            }
        }

        // Renumber text input
        const textInput = item.querySelector('input[type="text"]');
        if (textInput) {
            textInput.name = `options[${index}][text]`;
        }
    });

    // Set checked states
    if (this.value === 'single') {
        if (checkedIndices.length > 0) {
            const firstCheckedIndex = checkedIndices[0];
            const radio = optionItems[firstCheckedIndex]?.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        }
    } else {
        checkedIndices.forEach(idx => {
            const checkbox = optionItems[idx]?.querySelector('input[type="checkbox"]');
            if (checkbox) checkbox.checked = true;
        });
    }
});
</script>
@endpush

