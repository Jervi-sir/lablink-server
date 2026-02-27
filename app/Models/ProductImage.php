<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'url',
        'path',
        'is_main',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function format()
    {
        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'url' => $this->url,
            'path' => $this->path,
            'isMain' => (bool)$this->is_main,
        ];
    }
}
