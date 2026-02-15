<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Columns that are mass assignable
    protected $fillable = ['name'];
}
