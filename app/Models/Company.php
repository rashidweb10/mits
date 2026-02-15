<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'logo', 'email', 'phone', 'whatsapp',
        'address', 'website_url', 'google_map', 'is_active',
    ];

    public function meta()
    {
        return $this->hasMany(CompanyMeta::class);
    }
}
