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
    'address',
    'business_category_id',
    'laboratory_category_id',
    'wilaya_id',
    'is_featured',
    'operating_hours',
    'specializations',
    'registration_no',
  ];

  protected $casts = [
    'operating_hours' => 'array',
    'specializations' => 'array',
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

  public function contacts()
  {
    return $this->hasMany(BusinessContact::class, 'business_id');
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
      'registrationNo' => $this->registration_no,
      'logo' => $this->logo,
      'description' => $this->description,
      'certificateUrl' => $this->certificate_url,
      'address' => $this->address,
      'contacts' => $this->contacts->map(function ($contact) {
        return [
          'id' => $contact->id,
          'platform' => [
            'id' => $contact->platform->id,
            'code' => $contact->platform->code,
            'icon' => $contact->platform->icon,
          ],
          'content' => $contact->content,
          'label' => $contact->label,
        ];
      }),
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
      'specializations' => $this->specializations ?? [],
      'productCount' => $this->products()->count(),
      'followerCount' => $this->followers()->count(),
      'isFollowed' => $user ? $this->followers()->where('user_id', $user->id)->exists() : false,
      'isSaved' => $user ? $this->savedBy()->where('user_id', $user->id)->exists() : false,
    ];
  }
}
