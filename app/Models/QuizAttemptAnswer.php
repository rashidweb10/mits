<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_option_id',
        'is_correct',
    ];

    // Relationship: An answer belongs to an attempt
    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    // Relationship: An answer belongs to a question
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    // Relationship: An answer belongs to a selected option
    public function selectedOption()
    {
        return $this->belongsTo(QuizOption::class, 'selected_option_id');
    }
}