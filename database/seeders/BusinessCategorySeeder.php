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
      ['code' => 'laboratory',       'en' => 'Laboratory', 'ar' => 'مختبر',     'fr' => 'Laboratoire'],
      ['code' => 'supplier', 'en' => 'Wholesaler', 'ar' => 'تاجر جملة', 'fr' => 'Grossiste'],
    ];

    foreach ($categories as $category) {
      BusinessCategory::firstOrCreate(
        ['code' => $category['code']],
        ['en' => $category['en'], 'ar' => $category['ar'], 'fr' => $category['fr']]
      );
    }
  }
}
