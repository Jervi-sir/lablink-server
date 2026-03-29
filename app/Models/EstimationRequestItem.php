<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimationRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimation_request_id',
        'product_id',
        'quantity',
        'price',
        'product_name',
        'product_type',
        'unit',
    ];

    public function estimationRequest()
    {
        return $this->belongsTo(EstimationRequest::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function format(): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price !== null ? (float) $this->price : null,
            'productName' => $this->product_name,
            'productType' => $this->product_type,
            'unit' => $this->unit,
            'product' => $this->product ? $this->product->format() : null,
        ];
    }
}
