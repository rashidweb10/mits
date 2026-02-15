<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuizAttemptController extends Controller
{
    /**
     * Show quiz attempt page
     */
    public function showQuizAttempt(Quiz $quiz)
    {
        $user = Auth::user();

        // Check if user is enrolled in the course
        $isEnrolled = $user->enrolledCourses()->where('course_id', $quiz->course_id)->exists();
        if (!$isEnrolled) {
            return redirect()->route('auth.enrolled-courses')->with('error', 'You are not enrolled in this course.');
        }

        // Check if user has already attempted this quiz
        $existingAttempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('is_attempt', 1)
            ->first();

        if ($existingAttempt) {
            return redirect()->route('auth.quiz-result', $existingAttempt->id);
        }

        // Check if user has already passed this quiz
        $hasPassed = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('is_passed', 1)
            ->exists();

        // Load quiz with questions and options
        $quiz->load(['questions.options']);

        return view('frontend.quiz.attempt', compact('quiz', 'hasPassed'));
    }

    /**
     * Store quiz attempt
     */
    public function storeQuizAttempt(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        // Validate request
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|exists:quiz_options,id',
        ]);

        // Check if user is enrolled in the course
        $isEnrolled = $user->enrolledCourses()->where('course_id', $quiz->course_id)->exists();
        if (!$isEnrolled) {
            return redirect()->route('auth.enrolled-courses')->with('error', 'You are not enrolled in this course.');
        }

        // Check if user has already passed this quiz
        $hasPassed = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('is_passed', 1)
            ->exists();

        if ($hasPassed) {
            return redirect()->route('auth.quiz-attempt', $quiz->id)->with('error', 'You have already passed this quiz.');
        }

        // Calculate marks
        $totalMarks = 0;
        $obtainedMarks = 0;
        $answersData = [];

        foreach ($request->answers as $questionId => $optionId) {
            $question = $quiz->questions()->find($questionId);
            if ($question) {
                $totalMarks += $question->marks;

                $selectedOption = $question->options()->find($optionId);
                if ($selectedOption && $selectedOption->is_correct) {
                    $obtainedMarks += $question->marks;
                    $answersData[] = [
                        'question_id' => $questionId,
                        'selected_option_id' => $optionId,
                        'is_correct' => 1,
                    ];
                } else {
                    $answersData[] = [
                        'question_id' => $questionId,
                        'selected_option_id' => $optionId,
                        'is_correct' => 0,
                    ];
                }
            }
        }

        // Determine if passed
        $isPassed = $obtainedMarks >= $quiz->pass_marks;

        // Create quiz attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'is_passed' => $isPassed,
            'attempted_at' => now(),
            'is_attempt' => 1, // Set to 1 when quiz is submitted
        ]);

        // Create quiz attempt answers
        foreach ($answersData as $answerData) {
            $attempt->answers()->create($answerData);
        }

        // Generate certificate if passed
        if ($isPassed) {
            $certificateNo = 'CERT-' . strtoupper(Str::random(10)) . '-' . $user->id;

            Certificate::create([
                'user_id' => $user->id,
                'course_id' => $quiz->course_id,
                'quiz_id' => $quiz->id,
                'certificate_no' => $certificateNo,
                'issued_at' => now(),
            ]);
        }

        return redirect()->route('auth.quiz-result', $attempt->id);
    }

    /**
     * Show quiz result
     */
    public function showQuizResult(QuizAttempt $attempt)
    {
        $user = Auth::user();

        // Check if this attempt belongs to the user
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Load attempt with quiz, answers, and certificate
        $attempt->load(['quiz.course', 'answers.question.options', 'answers.selectedOption']);
        $certificate = Certificate::where('user_id', $user->id)
            ->where('quiz_id', $attempt->quiz_id)
            ->first();

        return view('frontend.quiz.result', compact('attempt', 'certificate'));
    }

    /**
     * Download certificate
     */
    public function downloadCertificate(Certificate $certificate)
    {
        $user = Auth::user();

        // Check if this certificate belongs to the user
        if ($certificate->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Load certificate with related data
        $certificate->load(['user', 'course', 'quiz']);
        
        // Get the quiz attempt that earned this certificate
        $quizAttempt = QuizAttempt::where('user_id', $certificate->user_id)
            ->where('quiz_id', $certificate->quiz_id)
            ->where('is_passed', 1)
            ->first();
            
        return view('frontend.certificate.show', compact('certificate', 'quizAttempt'));
    }
}