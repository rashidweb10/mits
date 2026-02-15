<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'total_marks',
        'pass_marks',
        'is_active',
        'duration',
    ];

    // Relationship: A quiz belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Relationship: A quiz has many questions
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
