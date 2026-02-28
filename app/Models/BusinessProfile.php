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
    'description',
    'certificate_url',
    'phone_numbers',
    'address',
    'business_category_id',
    'laboratory_category_id',
    'wilaya_id',
    'is_featured',
    'operating_hours',
    'website',
    'business_email',
    'support_email',
    'whatsapp',
    'linkedin',
  ];

  protected $casts = [
    'phone_numbers' => 'array',
    'operating_hours' => 'array',
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
    return $this->hasMany(Product::class, 'business_id');
  }

  public function followers()
  {
    return $this->belongsToMany(User::class, 'followers', 'business_id', 'user_id');
  }

  public function savedBy()
  {
    return $this->belongsToMany(User::class, 'saved_businesses', 'business_id', 'user_id');
  }

  public function format($user = null)
  {
    return [
      'id' => $this->id,
      'userId' => $this->user_id,
      'name' => $this->name,
      'nif' => $this->nif,
      'logo' => $this->logo,
      'description' => $this->description,
      'certificateUrl' => $this->certificate_url,
      'phoneNumbers' => $this->phone_numbers,
      'address' => $this->address,
      'website' => $this->website,
      'businessEmail' => $this->business_email,
      'supportEmail' => $this->support_email,
      'whatsapp' => $this->whatsapp,
      'linkedin' => $this->linkedin,
      'category' => $this->businessCategory ? [
        'id' => $this->businessCategory->id,
        'code' => $this->businessCategory->code,
      ] : null,
      'labCategory' => $this->laboratoryCategory ? [
        'id' => $this->laboratoryCategory->id,
        'code' => $this->laboratoryCategory->code,
      ] : null,
      'wilaya' => $this->wilaya ? [
        'id' => $this->wilaya->id,
        'name' => $this->wilaya->name,
      ] : null,
      'isFeatured' => (bool)$this->is_featured,
      'operatingHours' => $this->operating_hours,
      'productCount' => $this->products()->count(),
      'followerCount' => $this->followers()->count(),
      'isFollowed' => $user ? $this->followers()->where('user_id', $user->id)->exists() : false,
      'isSaved' => $user ? $this->savedBy()->where('user_id', $user->id)->exists() : false,
    ];
  }
}
