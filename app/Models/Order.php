<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Order extends Model
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
            'total_price' => 'decimal:2',
        ];
    }

    // ─── Boot ───────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        // Auto-generate a unique, random characteristic ID upon creation
        // Format: ORD-XXXX-YYYY  (e.g., ORD-A7K3-2026)
        static::creating(function (Order $order) {
            if (empty($order->code)) {
                $order->code = static::generateOrderCode();
            }
        });
    }

    /**
     * Generate a unique order code.
     *
     * Format: ORD-{4 random alphanumeric chars}-{current year}
     * Example: ORD-A7K3-2026
     */
    public static function generateOrderCode(): string
    {
        do {
            $randomPart = strtoupper(Str::random(4));
            $year       = date('Y');
            $code       = "ORD-{$randomPart}-{$year}";
        } while (static::where('code', $code)->exists());

        return $code;
    }

    // ─── Relationships ──────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wilaya(): BelongsTo
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products')
                     ->withPivot(['quantity', 'price'])
                     ->withTimestamps();
    }
}
