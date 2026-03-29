<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
  public const CODE_LAB = 'laboratory';
  public const CODE_WHOLESALE = 'supplier';

  protected $fillable = ['code', 'en', 'ar', 'fr'];

  public function businessProfiles()
  {
    return $this->hasMany(BusinessProfile::class);
  }
}
