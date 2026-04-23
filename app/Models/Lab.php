<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $fillable = [
        'user_id',
        'wilaya_id',
        'lab_category_id',
        'brand_name',
        'nif',
        'permission_path_url',
        'equipments_path_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function category()
    {
        return $this->belongsTo(LabCategory::class, 'lab_category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id', 'user_id');
    }
}
