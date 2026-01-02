@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $exam->title }}</h4>
            </div>
            <div class="card-body">
                <p class="lead">{{ $exam->description }}</p>
                
                <hr>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Exam Details:</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-question-circle text-primary"></i> 
                                <strong>Total Questions:</strong> {{ $exam->questions_count }}
                            </li>
                            <li><i class="bi bi-star text-warning"></i> 
                                <strong>Total Marks:</strong> {{ $exam->total_marks }}
                            </li>
                            <li><i class="bi bi-clock text-info"></i> 
                                <strong>Duration:</strong> 
                                {{ $exam->duration_minutes ? $exam->duration_minutes . ' minutes' : 'No time limit' }}
                            </li>
                            @if($exam->passing_marks)
                                <li><i class="bi bi-check-circle text-success"></i> 
                                    <strong>Passing Marks:</strong> {{ $exam->passing_marks }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Instructions:</h6>
                        <ul>
                            <li>Read each question carefully</li>
                            <li>Select your answer(s) before proceeding</li>
                            @if($exam->duration_minutes)
                                <li class="text-danger">Timer will start when you begin</li>
                            @endif
                            <li>Click "Submit" when you're done</li>
                            <li>No negative marking</li>
                        </ul>
                    </div>
                </div>
                
                @if($previousAttempts->isNotEmpty())
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Previous Attempts</h6>
                        @foreach($previousAttempts->take(3) as $attempt)
                            <div class="d-flex justify-content-between">
                                <span>
                                    {{ $attempt->submitted_at ? $attempt->submitted_at->format('M d, Y h:i A') : 'In Progress' }}
                                </span>
                                @if($attempt->isCompleted())
                                    <span>
                                        <strong>{{ $attempt->obtained_marks }}/{{ $attempt->total_marks }}</strong>
                                        ({{ $attempt->percentage }}%)
                                        <span class="badge {{ $attempt->hasPassed() ? 'bg-success' : 'bg-danger' }}">
                                            {{ $attempt->hasPassed() ? 'Passed' : 'Failed' }}
                                        </span>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <div class="d-grid gap-2">
                    <form action="{{ route('student.exams.start', $exam) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-play-circle"></i> Start Exam
                        </button>
                    </form>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
