<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const STUDENT = 'student';
    public const BUSINESS = 'business';
    public const ADMIN = 'admin';

    protected $fillable = ['code', 'en', 'ar', 'fr'];
}
