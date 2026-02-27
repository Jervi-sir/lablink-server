<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function format()
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'user' => $this->user ? $this->user->format() : null,
            'productId' => $this->product_id,
            'rating' => (int)$this->rating,
            'comment' => $this->comment,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
