<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    protected $fillable = [
        'name',
        'image',
        'description',
        'is_active',
    ];

    // Relationship: A course category has many courses
    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}
