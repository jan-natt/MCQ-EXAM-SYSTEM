@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2><i class="bi bi-clock-history"></i> My Results History</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Exam</th>
                        <th>Date</th>
                        <th>Score</th>
                        <th>Percentage</th>
                        <th>Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td>
                                <strong>{{ $attempt->exam->title }}</strong>
                            </td>
                            <td>{{ $attempt->submitted_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <strong>{{ $attempt->obtained_marks }}</strong> / {{ $attempt->total_marks }}
                            </td>
                            <td>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar
                                        {{ $attempt->percentage >= 75 ? 'bg-success' :
                                           ($attempt->percentage >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                         role="progressbar"
                                         style="width: {{ $attempt->percentage }}%">
                                        {{ $attempt->percentage }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($attempt->exam->passing_marks)
                                    <span class="badge {{ $attempt->hasPassed() ? 'bg-success' : 'bg-danger' }}">
                                        {{ $attempt->hasPassed() ? 'Passed' : 'Failed' }}
                                    </span>
                                @else
                                    <span class="badge bg-info">Completed</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('student.results.show', $attempt) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-0">No exam results yet. Take your first exam!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $attempts->links() }}
    </div>
</div>

@if($attempts->isNotEmpty())
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Performance Summary</h5>
                </div>
                <div class="card-body">
                    @php
                        $totalAttempts = $attempts->total();
                        $avgScore = $attempts->avg('obtained_marks');
                        $avgPercentage = $attempts->avg(fn($a) => $a->percentage);
                        $passedCount = $attempts->filter(fn($a) => $a->hasPassed())->count();
                    @endphp
                    
                    <div class="mb-3">
                        <strong>Total Exams Taken:</strong> {{ $totalAttempts }}
                    </div>
                    <div class="mb-3">
                        <strong>Average Score:</strong> {{ number_format($avgScore, 2) }} marks
                    </div>
                    <div class="mb-3">
                        <strong>Average Percentage:</strong> {{ number_format($avgPercentage, 2) }}%
                    </div>
                    <div class="mb-3">
                        <strong>Pass Rate:</strong> 
                        {{ $totalAttempts > 0 ? number_format(($passedCount / $totalAttempts) * 100, 2) : 0 }}%
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-trophy"></i> Best Performance</h5>
                </div>
                <div class="card-body">
                    @php
                        $bestAttempt = $attempts->sortByDesc('percentage')->first();
                    @endphp
                    
                    @if($bestAttempt)
                        <h6>{{ $bestAttempt->exam->title }}</h6>
                        <p class="mb-2">
                            <strong>Score:</strong> {{ $bestAttempt->obtained_marks }}/{{ $bestAttempt->total_marks }}
                            ({{ $bestAttempt->percentage }}%)
                        </p>
                        <p class="mb-2">
                            <strong>Date:</strong> {{ $bestAttempt->submitted_at->format('M d, Y') }}
                        </p>
                        <a href="{{ route('student.results.show', $bestAttempt) }}" 
                           class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@endsection