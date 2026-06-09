<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['student_id', 'lab_id', 'status', 'total_price', 'notes', 'student_last_viewed_at', 'lab_last_viewed_at', 'contract_pdf_url'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function negotiations()
    {
        return $this->hasMany(OrderNegotiation::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lab()
    {
        return $this->belongsTo(User::class, 'lab_id');
    }
}
