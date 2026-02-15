<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'form_name',
        'name',
        'email',
        'phone',
        'form_data',
        'ip',
        'company_id',
        'edu_response',
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    /**
     * Check if user with this email is registered.
     *
     * @return bool
     */
    public function getIsRegisteredAttribute()
    {
        return User::where('email', $this->email)->exists();
    }
}
