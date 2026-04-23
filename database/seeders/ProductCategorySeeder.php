<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'code' => 'physical',
                'en' => 'Physical Product',
                'fr' => 'Produit Physique',
                'ar' => 'منتج مادي',
            ],
            [
                'code' => 'service',
                'en' => 'Service',
                'fr' => 'Service',
                'ar' => 'خدمة',
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
