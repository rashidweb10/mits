<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'quiz_id',
        'certificate_no',
        'issued_at',
    ];

    protected $dates = ['issued_at'];

    // Relationship: A certificate belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: A certificate belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Relationship: A certificate belongs to a quiz
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    // Relationship: A certificate has one quiz attempt (the one that earned it)
    public function quizAttempt()
    {
        return $this->hasOne(QuizAttempt::class, 'quiz_id', 'quiz_id')
            ->whereColumn('user_id', 'certificates.user_id')
            ->where('is_passed', 1);
    }
}