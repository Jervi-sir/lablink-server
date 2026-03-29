<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EstimationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'code',
        'status',
        'address',
        'department',
        'phone',
        'notes',
        'extra_fee',
        'quoting_notes',
    ];

    protected static function booted()
    {
        static::creating(function ($request) {
            if (!$request->code) {
                $request->code = 'EST-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_id');
    }

    public function items()
    {
        return $this->hasMany(EstimationRequestItem::class);
    }

    public function format(): array
    {
        $itemsTotal = (float) $this->items->sum(fn($item) => ((float) $item->price) * $item->quantity);
        $grandTotal = $itemsTotal + (float) $this->extra_fee;

        return [
            'id' => $this->id,
            'code' => $this->code,
            'status' => $this->status,
            'address' => $this->address,
            'department' => $this->department,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'quotingNotes' => $this->quoting_notes,
            'extraFee' => (float) $this->extra_fee,
            'itemCount' => (int) $this->items->sum('quantity'),
            'estimatedTotal' => $grandTotal,
            'student' => $this->user ? $this->user->format() : null,
            'business' => $this->business ? $this->business->format($this->user) : null,
            'items' => $this->items->map(fn($item) => $item->format()),
            'createdAt' => $this->created_at?->toIso8601String(),
            'updatedAt' => $this->updated_at?->toIso8601String(),
        ];
    }
}
