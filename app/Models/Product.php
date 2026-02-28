<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id',
        'product_category_id',
        'name',
        'product_type',
        'sku',
        'slug',
        'description',
        'summary',
        'specifications',
        'offer_type',
        'unit',
        'price',
        'safety_level',
        'msds_path',
        'documentations',
        'stock',
        'is_available',
        'is_trending',
    ];

    protected $casts = [
        'specifications' => 'array',
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
        return $this->belongsToMany(User::class, 'saved_products');
    }

    public function format($user = null)
    {

        return [
            'id' => $this->id,
            'businessId' => $this->business_id,
            'business' => $this->business ? $this->business->format() : null,
            'productCategoryId' => $this->product_category_id,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'code' => $this->category->code,
            ] : null,
            'name' => $this->name,
            'productType' => $this->product_type ?? 'product',
            'sku' => $this->sku,
            'slug' => $this->slug,
            'description' => $this->description,
            'summary' => $this->summary,
            'specifications' => $this->specifications,
            'offerType' => $this->offer_type,
            'unit' => $this->unit,
            'price' => (float)$this->price,
            'safetyLevel' => (int)$this->safety_level,
            'msdsPath' => $this->msds_path,
            'documentations' => $this->documentations,
            'stock' => (int)$this->stock,
            'isAvailable' => (bool)$this->is_available,
            'isTrending' => (bool)$this->is_trending,
            'images' => $this->images->map(fn($image) => $image->format()),
            'reviews' => $this->reviews->map(fn($review) => $review->format()),
            'avgRating' => $this->reviews->count() > 0 ? round($this->reviews->avg('rating'), 1) : null,
            'reviewCount' => $this->reviews->count(),
            'isSaved' => $user ? $this->favorites()->where('user_id', $user->id)->exists() : false,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
