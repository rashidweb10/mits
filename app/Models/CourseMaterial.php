<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    protected $fillable = [
        'category_id',
        'course_id',
        'title',
        'description',
        'attachments',
        'youtube_url',
        'sorting_id',
        'is_active',
    ];

    // Relationship: Each material belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }    
}
