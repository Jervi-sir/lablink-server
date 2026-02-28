<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable = ['name', 'wilaya_id', 'address'];

    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }
}
