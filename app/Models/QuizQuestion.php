<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'question',
        'marks',
    ];

    // Relationship: A quiz question belongs to a quiz
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Relationship: A quiz question has many options
    public function options()
    {
        return $this->hasMany(QuizOption::class, 'question_id');
    }
}
