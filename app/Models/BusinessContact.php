<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessContact extends Model
{
  use HasFactory;

  protected $fillable = [
    'business_id',
    'platform_id',
    'content',
    'label',
  ];

  public function business()
  {
    return $this->belongsTo(BusinessProfile::class, 'business_id');
  }

  public function platform()
  {
    return $this->belongsTo(Platform::class);
  }
}
