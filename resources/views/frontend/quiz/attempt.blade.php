@extends('frontend.layouts.app')

@section('meta.title', 'Attempt Quiz: ' . ($quiz->title ?? 'Quiz'))
@section('meta.description', 'Attempt the quiz for ' . ($quiz->course->name ?? 'course'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">{{ $quiz->title ?? 'Quiz' }}</h3>
                </div>
                <div class="card-body">
                    @if($hasPassed)
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i>
                            You have already passed this quiz! Your certificate is available.
                        </div>
                    @endif

                    <form id="quiz-form" method="POST" action="{{ route('auth.quiz-attempt.store', $quiz->id) }}">
                        @csrf

                    <div class="mb-4">
                        <h5>Course: {{ $quiz->course->name ?? 'N/A' }}</h5>
                        <p class="text-muted">Total Marks: {{ $quiz->total_marks ?? 0 }}</p>
                        <p class="text-muted">Pass Marks: {{ $quiz->pass_marks ?? 0 }}</p>
                        <p class="text-muted">Duration: {{ $quiz->duration ?? 60 }} minutes</p>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="mb-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-clock me-2"></i>Time Remaining
                                </h5>
                                <div id="countdown" class="display-4 font-weight-bold">
                                    {{ gmdate('H:i:s', ($quiz->duration ?? 60) * 60) }}
                                </div>
                            </div>
                        </div>
                    </div>

                        @foreach($quiz->questions as $index => $question)
                            <div class="question-card mb-4 p-4 bg-light rounded">
                                <h5 class="mb-3">
                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                    {{ $question->question }}
                                    @if($question->marks)
                                        <span class="badge bg-info">{{ $question->marks }} marks</span>
                                    @endif
                                </h5>

                                @foreach($question->options as $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="answers[{{ $question->id }}]"
                                               id="question_{{ $question->id }}_option_{{ $option->id }}"
                                               value="{{ $option->id }}"
                                               required>
                                        <label class="form-check-label" for="question_{{ $question->id }}_option_{{ $option->id }}">
                                            {{ $option->option_text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Submit Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Countdown timer functionality
        const durationInSeconds = {{ $quiz->duration ?? 60 }} * 60;
        let timeRemaining = durationInSeconds;
        const countdownElement = document.getElementById('countdown');
        const quizForm = document.querySelector('#quiz-form');

        // Update countdown display
        function updateCountdown() {
            const hours = Math.floor(timeRemaining / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = timeRemaining % 60;

            // Format as HH:MM:SS
            const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            countdownElement.textContent = formattedTime;

            // Change color when time is running low
            if (timeRemaining <= 60) {
                countdownElement.classList.remove('text-white');
                countdownElement.classList.add('text-danger');
            }
        }

        // Auto-submit form when time is up
        function autoSubmitForm() {
            quizForm.submit();
        }

        // Countdown timer interval
        const countdownInterval = setInterval(() => {
            timeRemaining--;
            updateCountdown();

            if (timeRemaining <= 0) {
                clearInterval(countdownInterval);
                autoSubmitForm();
            }
        }, 1000);

        // Initialize countdown
        updateCountdown();
    });
</script>
@endsection