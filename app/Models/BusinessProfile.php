<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
  use HasFactory;

  protected $table = 'businesses';

  protected $fillable = [
    'user_id',
    'name',
    'nif',
    'logo',
    'bio',
    'certificate_url',
    'phone_numbers',
    'address',
    'business_category_id',
    'laboratory_category_id',
    'wilaya_id',
  ];

  protected $casts = [
    'phone_numbers' => 'array',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function businessCategory()
  {
    return $this->belongsTo(BusinessCategory::class);
  }

  public function laboratoryCategory()
  {
    return $this->belongsTo(LaboratoryCategory::class);
  }

  public function wilaya()
  {
    return $this->belongsTo(Wilaya::class);
  }

  public function products()
  {
    return $this->hasMany(Product::class, 'seller_id', 'user_id');
  }
}
