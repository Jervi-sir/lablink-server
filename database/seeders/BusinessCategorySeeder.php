<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;

class BusinessCategorySeeder extends Seeder
{
  /**
   * Seed the business_categories table.
   */
  public function run(): void
  {
    $categories = [
      'lab',
      'wholesale',
    ];

    foreach ($categories as $category) {
      BusinessCategory::firstOrCreate(['code' => $category]);
    }
  }
}
