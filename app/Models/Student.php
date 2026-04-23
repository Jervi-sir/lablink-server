<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'wilaya_id',
        'full_name',
        'university_registry_number',
        'specialty',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }
}
