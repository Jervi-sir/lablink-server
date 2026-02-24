<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'product_category_id',
        'name',
        'slug',
        'offer_type',
        'unit',
        'price',
        'safety_level',
        'msds_path',
        'documentations',
        'stock',
        'is_available',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(5);
            }
        });
    }

    public function business()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'product_user_favorites');
    }
}
