<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'en',
    'ar',
    'fr',
    'icon',
  ];

  public function businessContacts()
  {
    return $this->hasMany(BusinessContact::class);
  }
}
