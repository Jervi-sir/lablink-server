<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{
    protected $fillable = ['code', 'number', 'en', 'ar', 'fr'];

    public function businessProfiles()
    {
        return $this->hasMany(BusinessProfile::class);
    }

    public function getNameAttribute()
    {
        return $this->en;
    }

    public function universities()
    {
        return $this->hasMany(University::class);
    }
}
