<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price'        => 'decimal:2',
            'safety_level' => 'integer',
            'stock'        => 'integer',
        ];
    }

    // ─── Boot ───────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        // Auto-generate a unique slug from the product name on creation
        static::creating(function (Product $product) {
            $product->slug = $product->generateUniqueSlug($product->name);
        });

        // Regenerate slug if the name is updated
        static::updating(function (Product $product) {
            if ($product->isDirty('name')) {
                $product->slug = $product->generateUniqueSlug($product->name);
            }
        });
    }

    /**
     * Generate a unique slug from the given name.
     */
    protected function generateUniqueSlug(string $name): string
    {
        $slug     = Str::slug($name);
        $original = $slug;
        $counter  = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    // ─── Relationships ──────────────────────────────────────

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_products')
                     ->withPivot(['quantity', 'price'])
                     ->withTimestamps();
    }
}
