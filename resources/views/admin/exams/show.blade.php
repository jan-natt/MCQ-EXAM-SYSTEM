@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2>{{ $exam->title }}</h2>
        <p class="text-muted">{{ $exam->description }}</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.questions.create', $exam) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Question
        </a>
        <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-secondary">
            <i class="bi bi-pencil"></i> Edit Exam
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <small class="text-muted">Total Questions</small>
                <h3>{{ $exam->questions->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <small class="text-muted">Total Marks</small>
                <h3>{{ $exam->questions->sum('marks') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <small class="text-muted">Duration</small>
                <h3>{{ $exam->duration_minutes ? $exam->duration_minutes . ' min' : 'No limit' }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <small class="text-muted">Status</small>
                <h3>
                    <span class="badge {{ $exam->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $exam->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ol"></i> Questions</h5>
    </div>
    <div class="card-body">
        @forelse($exam->questions as $index => $question)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0">
                            <span class="badge bg-primary">Q{{ $index + 1 }}</span>
                            {{ $question->question_text }}
                        </h6>
                        <div>
                            <span class="badge bg-info">{{ $question->marks }} marks</span>
                            <span class="badge bg-secondary">
                                {{ $question->question_type === 'single' ? 'Single' : 'Multiple' }} Choice
                            </span>
                        </div>
                    </div>
                    
                    <ul class="list-unstyled mb-2">
                        @foreach($question->options as $option)
                            <li class="mb-1">
                                <i class="bi {{ $option->is_correct ? 'bi-check-circle-fill text-success' : 'bi-circle' }}"></i>
                                {{ $option->option_text }}
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" 
                              class="d-inline" onsubmit="return confirm('Delete this question?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <p class="text-muted mb-0">No questions added yet. Add your first question!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection