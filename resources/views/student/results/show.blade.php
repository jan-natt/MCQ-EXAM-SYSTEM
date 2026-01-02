@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <!-- Result Summary Card -->
        <div class="card mb-4 {{ $attempt->hasPassed() ? 'border-success' : 'border-danger' }}">
            <div class="card-header {{ $attempt->hasPassed() ? 'bg-success' : 'bg-danger' }} text-white">
                <h4 class="mb-0">
                    <i class="bi {{ $attempt->hasPassed() ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                    {{ $attempt->exam->title }} - Results
                </h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h6 class="text-muted">Total Score</h6>
                        <h2 class="mb-0">{{ $attempt->obtained_marks }}/{{ $attempt->total_marks }}</h2>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Percentage</h6>
                        <h2 class="mb-0">{{ $attempt->percentage }}%</h2>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Correct Answers</h6>
                        <h2 class="mb-0 text-success">{{ $correct_answers }}</h2>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Incorrect Answers</h6>
                        <h2 class="mb-0 text-danger">{{ $incorrect_answers }}</h2>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1">
                            <strong>Total Questions:</strong> {{ $total_questions }}
                        </p>
                        <p class="mb-1">
                            <strong>Submitted:</strong> {{ $attempt->submitted_at->format('M d, Y h:i A') }}
                        </p>
                        <p class="mb-1">
                            <strong>Time Taken:</strong> 
                            {{ $attempt->started_at->diffInMinutes($attempt->submitted_at) }} minutes
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        @if($attempt->exam->passing_marks)
                            <p class="mb-1">
                                <strong>Passing Marks:</strong> {{ $attempt->exam->passing_marks }}
                            </p>
                            <h3>
                                <span class="badge {{ $attempt->hasPassed() ? 'bg-success' : 'bg-danger' }} fs-5">
                                    {{ $attempt->hasPassed() ? 'PASSED' : 'FAILED' }}
                                </span>
                            </h3>
                        @else
                            <h3>
                                <span class="badge bg-info fs-5">COMPLETED</span>
                            </h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Question Review -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Detailed Answer Review</h5>
            </div>
            <div class="card-body">
                @foreach($question_results as $index => $result)
                    <div class="card mb-3 {{ $result['is_correct'] ? 'border-success' : 'border-danger' }}">
                        <div class="card-header {{ $result['is_correct'] ? 'bg-success' : 'bg-danger' }} bg-opacity-10">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>
                                    <i class="bi {{ $result['is_correct'] ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i>
                                    Question {{ $index + 1 }}
                                </strong>
                                <span class="badge {{ $result['is_correct'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $result['is_correct'] ? '+' : '0' }}{{ $result['marks_obtained'] }} marks
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="fw-bold mb-3">{{ $result['question']->question_text }}</p>
                            
                            <div class="options-review">
                                @foreach($result['question']->options as $option)
                                    @php
                                        $isSelected = in_array($option->id, $result['selected_options']);
                                        $isCorrect = $option->is_correct;
                                    @endphp
                                    
                                    <div class="form-check mb-2 p-2 rounded
                                        @if($isCorrect && $isSelected) bg-success bg-opacity-10
                                        @elseif($isCorrect) bg-success bg-opacity-10
                                        @elseif($isSelected) bg-danger bg-opacity-10
                                        @endif">
                                        <div class="d-flex align-items-center">
                                            @if($result['question']->isSingleChoice())
                                                <span class="form-check-input position-static me-2">
                                                    @if($isSelected)
                                                        <i class="bi bi-record-circle-fill {{ $isCorrect ? 'text-success' : 'text-danger' }}"></i>
                                                    @else
                                                        <i class="bi bi-circle"></i>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="form-check-input position-static me-2">
                                                    @if($isSelected)
                                                        <i class="bi bi-check-square-fill {{ $isCorrect ? 'text-success' : 'text-danger' }}"></i>
                                                    @else
                                                        <i class="bi bi-square"></i>
                                                    @endif
                                                </span>
                                            @endif
                                            
                                            <label class="form-check-label flex-grow-1">
                                                {{ $option->option_text }}
                                                
                                                @if($isCorrect)
                                                    <span class="badge bg-success ms-2">Correct Answer</span>
                                                @endif
                                                
                                                @if($isSelected && !$isCorrect)
                                                    <span class="badge bg-danger ms-2">Your Answer</span>
                                                @elseif($isSelected && $isCorrect)
                                                    <span class="badge bg-success ms-2">Your Answer</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if(!$result['is_correct'])
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="bi bi-info-circle"></i> 
                                    <strong>Explanation:</strong> 
                                    @if($result['question']->isSingleChoice())
                                        You need to select the correct option.
                                    @else
                                        You need to select ALL correct options and NO incorrect options to get marks.
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between mt-4 mb-4">
            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-house"></i> Back to Dashboard
            </a>
            <a href="{{ route('student.results.index') }}" class="btn btn-primary">
                <i class="bi bi-clock-history"></i> View All Results
            </a>
        </div>
    </div>
</div>
@endsection
