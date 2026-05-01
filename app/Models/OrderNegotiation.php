<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderNegotiation extends Model
{
    protected $fillable = [
        'order_id',
        'suggested_price',
        'suggested_by', // 'student' or 'lab'
        'status', // 'pending', 'accepted', 'rejected'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
