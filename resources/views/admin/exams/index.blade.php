<!-- resources/views/admin/exams/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2><i class="bi bi-file-earmark-text"></i> Manage Exams</h2>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create New Exam
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Questions</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td>
                                <strong>{{ $exam->title }}</strong>
                                @if($exam->description)
                                    <br><small class="text-muted">{{ Str::limit($exam->description, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $exam->questions_count }}</td>
                            <td>{{ $exam->duration_minutes ? $exam->duration_minutes . ' min' : 'No limit' }}</td>
                            <td>
                                <span class="badge {{ $exam->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $exam->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $exam->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.exams.show', $exam) }}" 
                                       class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.exams.edit', $exam) }}" 
                                       class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.exams.toggle-status', $exam) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning" 
                                                title="{{ $exam->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.exams.destroy', $exam) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this exam? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-0">No exams created yet. Create your first exam!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $exams->links() }}
    </div>
</div>
@endsection

<!-- resources/views/admin/exams/create.blade.php -->


<!-- resources/views/admin/exams/show.blade.php -->
