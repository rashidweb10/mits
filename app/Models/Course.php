<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'image',
        'brochure',
        'category_id',
        'is_active',
    ];

    // Relationship: A course belongs to a category
    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    // Relationship: A course has many materials
    public function materials()
    {
        return $this->hasMany(CourseMaterial::class);
    }

    // Relationship: A course has many quizzes
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    // Relationship: A course has one active quiz
    public function quiz()
    {
        return $this->hasOne(Quiz::class)->where('is_active', 1);
    }
}
