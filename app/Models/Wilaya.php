<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{
    protected $fillable = ['code', 'name'];

    public function businessProfiles()
    {
        return $this->hasMany(BusinessProfile::class);
    }
}
