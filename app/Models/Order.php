<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'total_price',
        'shipping_address',
        'wilaya_id',
        'order_status_id',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($order) {
            if (!$order->code) {
                $order->code = 'ORD-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }
}
