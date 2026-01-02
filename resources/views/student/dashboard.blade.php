@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2><i class="bi bi-mortarboard"></i> Student Dashboard</h2>
        <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Exams Taken</h6>
                <h2>{{ $stats['total_exams_taken'] }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Average Score</h6>
                <h2>{{ $stats['average_score'] ? number_format($stats['average_score'], 1) : 'N/A' }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Exams Passed</h6>
                <h2>{{ $stats['exams_passed'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Available Exams</h5>
            </div>
            <div class="card-body">
                @forelse($availableExams as $exam)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title">{{ $exam->title }}</h5>
                                    <p class="card-text text-muted">{{ $exam->description }}</p>
                                    
                                    <div class="mb-2">
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-question-circle"></i> 
                                            {{ $exam->questions_count }} Questions
                                        </span>
                                        <span class="badge bg-info">
                                            <i class="bi bi-clock"></i> 
                                            {{ $exam->duration_minutes ? $exam->duration_minutes . ' min' : 'No time limit' }}
                                        </span>
                                        @if($exam->passing_marks)
                                            <span class="badge bg-warning text-dark">
                                                Passing: {{ $exam->passing_marks }} marks
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('student.exams.show', $exam) }}" 
                                       class="btn btn-primary">
                                        Start Exam
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4">No exams available at the moment.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Results</h5>
            </div>
            <div class="card-body">
                @forelse($recentAttempts as $attempt)
                    <div class="mb-3 pb-3 border-bottom">
                        <h6 class="mb-1">{{ $attempt->exam->title }}</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <strong>{{ $attempt->obtained_marks }}/{{ $attempt->total_marks }}</strong>
                                <span class="text-muted">({{ $attempt->percentage }}%)</span>
                            </span>
                            <span class="badge {{ $attempt->hasPassed() ? 'bg-success' : 'bg-danger' }}">
                                {{ $attempt->hasPassed() ? 'Passed' : 'Failed' }}
                            </span>
                        </div>
                        <small class="text-muted">{{ $attempt->submitted_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted">No results yet. Take your first exam!</p>
                @endforelse
                
                @if($recentAttempts->isNotEmpty())
                    <a href="{{ route('student.results.index') }}" class="btn btn-sm btn-outline-primary w-100">
                        View All Results
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
