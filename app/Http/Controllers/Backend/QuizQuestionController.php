<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\Quiz;

class QuizQuestionController extends Controller
{
    protected $moduleName;

    public function __construct()
    {
        $this->moduleName = 'Quiz Questions';
        view()->share('moduleName', $this->moduleName);
    }

    /**
     * Display all questions for a quiz
     */
    public function index($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $questions = QuizQuestion::with('options')->where('quiz_id', $quizId)->get();
        return view('backend.quizzes.questions.index', compact('quiz', 'questions'));
    }

    /**
     * Show the form for creating a new question
     */
    public function create($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        return view('backend.quizzes.questions.create', compact('quiz'));
    }

    /**
     * Store a newly created question and options
     */
    public function store(Request $request, $quizId)
    {
        // Parse options if it's a JSON string
        $options = $request->input('options');
        if (is_string($options)) {
            $options = json_decode($options, true);
            $request->merge(['options' => $options]);
        }
        
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'marks' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string|max:255',
            'options.*.is_correct' => 'boolean',
        ]);

        // Get the quiz
        $quiz = Quiz::findOrFail($quizId);
        
        // Calculate the total marks of existing questions
        $existingTotalMarks = QuizQuestion::where('quiz_id', $quizId)->sum('marks');
        
        // Check if adding the new question's marks exceeds the quiz's total_marks
        if ($existingTotalMarks + $request->input('marks') > $quiz->total_marks) {
            return response()->json([
                'status' => false,
                'notification' => 'The total marks of all questions cannot exceed the quiz total marks of ' . $quiz->total_marks
            ], 200);
        }

        // Create the question
        $question = QuizQuestion::create([
            'quiz_id' => $quizId,
            'question' => $request->input('question'),
            'marks' => $request->input('marks'),
        ]);

        // Create the options
        foreach ($request->input('options') as $optionData) {
            QuizOption::create([
                'question_id' => $question->id,
                'option_text' => $optionData['option_text'],
                'is_correct' => !empty($optionData['is_correct']) ? 1 : 0,
            ]);
        }

        return response()->json(['status' => true, 'notification' => 'Question created successfully!']);
    }

    /**
     * Show the form for editing a question
     */
    public function edit($quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = QuizQuestion::with('options')->findOrFail($questionId);
        return view('backend.quizzes.questions.edit', compact('quiz', 'question'));
    }

    /**
     * Update a question and its options
     */
    public function update(Request $request, $quizId, $questionId)
    {
        // Parse options if it's a JSON string
        $options = $request->input('options');
        if (is_string($options)) {
            $options = json_decode($options, true);
            $request->merge(['options' => $options]);
        }
        
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'marks' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string|max:255',
            'options.*.is_correct' => 'boolean',
        ]);

        // Get the quiz
        $quiz = Quiz::findOrFail($quizId);
        
        // Get the current question
        $question = QuizQuestion::findOrFail($questionId);
        
        // Calculate the total marks of all questions except the current one
        $otherQuestionsTotalMarks = QuizQuestion::where('quiz_id', $quizId)->where('id', '!=', $questionId)->sum('marks');
        
        // Check if updating the current question's marks exceeds the quiz's total_marks
        if ($otherQuestionsTotalMarks + $request->input('marks') > $quiz->total_marks) {
            return response()->json([
                'status' => false,
                'notification' => 'The total marks of all questions cannot exceed the quiz total marks of ' . $quiz->total_marks
            ], 200);
        }

        // Update the question
        $question->update([
            'question' => $request->input('question'),
            'marks' => $request->input('marks'),
        ]);

        // Update or create options
        foreach ($request->input('options') as $optionData) {
            if (isset($optionData['id'])) {
                // Update existing option
                QuizOption::where('id', $optionData['id'])->where('question_id', $questionId)->update([
                    'option_text' => $optionData['option_text'],
                    'is_correct' => !empty($optionData['is_correct']) ? 1 : 0,
                ]);
            } else {
                // Create new option
                QuizOption::create([
                    'question_id' => $questionId,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => !empty($optionData['is_correct']) ? 1 : 0,
                ]);
            }
        }

        return response()->json(['status' => true, 'notification' => 'Question updated successfully!']);
    }

    /**
     * Remove a question and its options
     */
    public function destroy($quizId, $questionId)
    {
        try {
            QuizQuestion::destroy($questionId);
            return redirect()->route('quizzes.index')->with('success', 'Question deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error deleting QuizQuestion record', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'question_id' => $questionId
            ]);
            return redirect()->route('quizzes.questions.index', $quizId)->with('error', 'There was an error deleting the question.');
        }
    }
}
