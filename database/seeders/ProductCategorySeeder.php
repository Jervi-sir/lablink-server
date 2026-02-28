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
      ['code' => 'chemicals',  'en' => 'Chemicals',  'ar' => 'مواد كيميائية', 'fr' => 'Produits chimiques'],
      ['code' => 'reagents',   'en' => 'Reagents',   'ar' => 'كواشف',       'fr' => 'Réactifs'],
      ['code' => 'equipment',  'en' => 'Equipment',  'ar' => 'معدات',       'fr' => 'Équipement'],
      ['code' => 'glassware',  'en' => 'Glassware',  'ar' => 'أواني زجاجية', 'fr' => 'Verrerie'],
      ['code' => 'biologicals', 'en' => 'Biologicals', 'ar' => 'مواد بيولوجية', 'fr' => 'Produits biologiques'],
      ['code' => 'consumables', 'en' => 'Consumables', 'ar' => 'مستهلكات',    'fr' => 'Consommables'],
      ['code' => 'instruments', 'en' => 'Instruments', 'ar' => 'أدوات',       'fr' => 'Instruments'],
    ];

    foreach ($categories as $category) {
      ProductCategory::firstOrCreate(
        ['code' => $category['code']],
        ['en' => $category['en'], 'ar' => $category['ar'], 'fr' => $category['fr']]
      );
    }
  }
}
