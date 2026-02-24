<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
  /**
   * Seed the product_categories table.
   */
  public function run(): void
  {
    $categories = [
      'chemicals',
      'reagents',
      'equipment',
      'glassware',
      'biologicals',
      'consumables',
      'instruments',
    ];

    foreach ($categories as $category) {
      ProductCategory::firstOrCreate(['code' => $category]);
    }
  }
}
