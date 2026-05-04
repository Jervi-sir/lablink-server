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
        'avatar_url',
        'nif',
        'permission_path_url',
        'equipments_path_url',
    ];

    /**
     * Resolve the route binding for the model.
     *
     * Supports looking up by both internal 'id' or owner 'user_id'.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)
            ->orWhere('user_id', $value)
            ->firstOrFail();
    }

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

    /**
     * Get the products owned by this lab.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
