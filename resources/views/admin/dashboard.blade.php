<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create New Exam
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Total Exams</h6>
                <h2>{{ $stats['total_exams'] }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Active Exams</h6>
                <h2>{{ $stats['active_exams'] }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6 class="card-title">Total Students</h6>
                <h2>{{ $stats['total_students'] }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6 class="card-title">Total Attempts</h6>
                <h2>{{ $stats['total_attempts'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Recent Exams</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($recentExams as $exam)
                        <a href="{{ route('admin.exams.show', $exam) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">{{ $exam->title }}</h6>
                                <span class="badge {{ $exam->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $exam->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <small class="text-muted">Created {{ $exam->created_at->diffForHumans() }}</small>
                        </a>
                    @empty
                        <p class="text-muted">No exams created yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Submissions</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($recentAttempts as $attempt)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $attempt->user->name }}</h6>
                                    <small>{{ $attempt->exam->title }}</small>
                                </div>
                                <div class="text-end">
                                    <strong>{{ $attempt->obtained_marks }}/{{ $attempt->total_marks }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $attempt->submitted_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No submissions yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection