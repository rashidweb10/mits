@extends('frontend.layouts.app')

@section('meta.title', 'Quiz Result')
@section('meta.description', 'View your quiz attempt results')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Quiz Result</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>{{ $attempt->quiz->title ?? 'Quiz' }}</h4>
                            <p class="text-muted">Course: {{ $attempt->quiz->course->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="h4">
                                @if($attempt->is_passed)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-2"></i>Passed
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-2"></i>Failed
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Your Score</h5>
                                    <div class="display-4 fw-bold text-primary">
                                        {{ $attempt->obtained_marks }}/{{ $attempt->total_marks }}
                                    </div>
                                    <p class="card-text">
                                        @if($attempt->is_passed)
                                            <span class="text-success">Congratulations! You passed the quiz.</span>
                                        @else
                                            <span class="text-danger">You need {{ $attempt->quiz->pass_marks }} marks to pass.</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Performance Summary</h5>
                                    <div class="progress mb-3">
                                        <div class="progress-bar " 
                                             role="progressbar" 
                                             style="width: {{ ($attempt->obtained_marks / $attempt->total_marks) * 100 }}%;
                                             background-color: {{ $attempt->is_passed ? '#28a745' : '#dc3545' }};">
                                            {{ round(($attempt->obtained_marks / $attempt->total_marks) * 100) }}%
                                        </div>
                                    </div>
                                    <p class="card-text">
                                        <i class="fas fa-info-circle me-2"></i>
                                        {{ $attempt->answers->where('is_correct', 1)->count() }} correct out of {{ $attempt->answers->count() }} questions
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Question Details</h5>
                        </div>
                        <div class="card-body">
                            @foreach($attempt->answers as $index => $answer)
                                <div class="question-review mb-4 p-3 {{ $answer->is_correct ? 'bg-light' : 'bg-light border border-danger' }}">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6>
                                            <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                            {{ $answer->question->question_text ?? 'Question' }}
                                        </h6>
                                        @if($answer->is_correct)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Correct
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Incorrect
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mb-2">
                                        <strong>Your Answer:</strong>
                                        <span class="ms-2">{{ $answer->selectedOption->option_text ?? 'N/A' }}</span>
                                    </div>

                                    @if(!$answer->is_correct)
                                        <div class="text-muted">
                                            <strong>Correct Answer:</strong>
                                            <span class="ms-2">
                                                {{ $answer->question->options->where('is_correct', 1)->first()->option_text ?? 'N/A' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        @if($attempt->is_passed && $certificate)
                            <a href="{{ route('auth.certificate.download', $certificate->id) }}" 
                               class="btn btn-success btn-lg me-md-2">
                                <i class="fas fa-certificate me-2"></i>View Certificate
                            </a>
                        @endif
                        <a href="{{ route('auth.enrolled-courses') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Back to Courses
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add any result-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // You can add interactive features here
    });
</script>
@endsection