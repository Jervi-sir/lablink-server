<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{
    protected $fillable = [
        'number',
        'code',
        'en',
        'fr',
        'ar',
    ];

    public function labs()
    {
        return $this->hasMany(Lab::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
