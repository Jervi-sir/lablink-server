<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabCategory extends Model
{
    protected $fillable = ['code', 'en', 'fr', 'ar'];

    public function labs()
    {
        return $this->hasMany(Lab::class);
    }
}
