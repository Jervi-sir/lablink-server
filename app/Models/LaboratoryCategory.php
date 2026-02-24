<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratoryCategory extends Model
{
  protected $fillable = ['code'];

  public function businessProfiles()
  {
    return $this->hasMany(BusinessProfile::class);
  }
}
