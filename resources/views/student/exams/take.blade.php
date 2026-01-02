@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $attempt->exam->title }}</h5>
                    @if($remainingTime !== null)
                        <div id="timer" class="badge bg-warning text-dark fs-6">
                            <i class="bi bi-clock"></i> <span id="timeDisplay">{{ $remainingTime }}:00</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('student.exams.submit', $attempt) }}" method="POST" id="examForm">
                    @csrf
                    
                    @foreach($attempt->exam->questions as $index => $question)
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <strong>Question {{ $index + 1 }}</strong>
                                    <span class="badge bg-info">{{ $question->marks }} marks</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">{{ $question->question_text }}</p>
                                
                                <div class="options-list">
                                    @foreach($question->options as $option)
                                        <div class="form-check mb-2">
                                            @if($question->isSingleChoice())
                                                <input class="form-check-input" type="radio" 
                                                       name="answers[{{ $question->id }}][]" 
                                                       value="{{ $option->id }}" 
                                                       id="option_{{ $option->id }}">
                                            @else
                                                <input class="form-check-input" type="checkbox" 
                                                       name="answers[{{ $question->id }}][]" 
                                                       value="{{ $option->id }}" 
                                                       id="option_{{ $option->id }}">
                                            @endif
                                            <label class="form-check-label w-100" for="option_{{ $option->id }}">
                                                {{ $option->option_text }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($question->isMultipleChoice())
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> Multiple answers may be correct
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        <strong>Important:</strong> Please review your answers before submitting. 
                        Once submitted, you cannot change your answers.
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg" 
                                onclick="return confirm('Are you sure you want to submit? You cannot change answers after submission.')">
                            <i class="bi bi-check-circle"></i> Submit Exam
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 70px;">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-list-check"></i> Questions</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($attempt->exam->questions as $index => $question)
                        <div class="col-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100 question-nav" 
                                    data-question="{{ $index }}">
                                {{ $index + 1 }}
                            </button>
                        </div>
                    @endforeach
                </div>
                
                <hr>
                
                <div class="small">
                    <strong>Legend:</strong><br>
                    <span class="badge bg-secondary">Not Answered</span><br>
                    <span class="badge bg-success">Answered</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Timer functionality
if
($remainingTime !== null);
let remainingSeconds = '{{ $remainingTime * 60 }}';
const timerDisplay = document.getElementById('timeDisplay');

function updateTimer() {
    if (remainingSeconds <= 0) {
        document.getElementById('examForm').submit();
        return;
    }
    
    const minutes = Math.floor(remainingSeconds / 60);
    const seconds = remainingSeconds % 60;
    timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    
    if (remainingSeconds <= 60) {
        document.getElementById('timer').classList.remove('bg-warning', 'text-dark');
        document.getElementById('timer').classList.add('bg-danger', 'text-white');
    }
    
    remainingSeconds--;
}

setInterval(updateTimer, 1000);
endif

// Smooth scroll to questions
document.querySelectorAll('.question-nav').forEach(btn => {
    btn.addEventListener('click', function() {
        const questionIndex = this.getAttribute('data-question');
        const cards = document.querySelectorAll('.card.mb-4');
        if (cards[questionIndex]) {
            cards[questionIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});

// Track answered questions
document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
    input.addEventListener('change', function() {
        updateQuestionStatus();
    });
});

function updateQuestionStatus() {
    const questions = document.querySelectorAll('.card.mb-4');
    questions.forEach((card, index) => {
        const inputs = card.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');
        const navBtn = document.querySelector(`.question-nav[data-question="${index}"]`);
        
        if (inputs.length > 0) {
            navBtn.classList.remove('btn-outline-secondary');
            navBtn.classList.add('btn-success');
        } else {
            navBtn.classList.remove('btn-success');
            navBtn.classList.add('btn-outline-secondary');
        }
    });
}

// Warn before leaving page
window.addEventListener('beforeunload', function (e) {
    e.preventDefault();
    e.returnValue = '';
});

document.getElementById('examForm').addEventListener('submit', function() {
    window.removeEventListener('beforeunload', function() {});
});
</script>
@endpush