<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'product_category_id',
        'name_ar',
        'name_en',
        'name_fr',
        'description_ar',
        'description_en',
        'description_fr',
        'price',
        'stock_quantity',
        'delivery_time',
        'warranty',
        'specifications',
        'type',
        'is_active',
        'is_available',
        'image_url',
        'images',
        'location',
        'supervisor',
        'working_hours',
        'min_booking_time',
    ];

    protected $casts = [
        'specifications' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
