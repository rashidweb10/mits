<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnrolment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'validity',
        'is_active',
    ];

    // Relationship: Enrolment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: Enrolment belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


}
