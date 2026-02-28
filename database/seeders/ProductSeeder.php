<?php

namespace Database\Seeders;

use App\Models\BusinessProfile;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the products table with realistic scientific supplies.
     */
    public function run(): void
    {
        // Get all business profiles
        $sellers       = BusinessProfile::all();
        $categories    = ProductCategory::all()->keyBy('code');

        $scientificImages = [
            'chemicals' => 'https://images.unsplash.com/photo-1583912267550-d44d7a129820?q=80&w=800&auto=format&fit=crop',
            'equipment' => 'https://images.unsplash.com/photo-1581093196277-9f608ebab48c?q=80&w=800&auto=format&fit=crop',
            'glassware' => 'https://images.unsplash.com/photo-1582719202047-76d3432ee323?q=80&w=800&auto=format&fit=crop',
            'instruments' => 'https://images.unsplash.com/photo-1576086213369-97a306d36557?q=80&w=800&auto=format&fit=crop',
            'consumables' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=800&auto=format&fit=crop',
            'biologicals' => 'https://images.unsplash.com/photo-1530213786676-41ad9f7736f6?q=80&w=800&auto=format&fit=crop',
            'reagents' => 'https://images.unsplash.com/photo-1581093806997-124204d9ad9d?q=80&w=800&auto=format&fit=crop',
        ];

        $products = [
            // Chemicals & Reagents
            ['name' => 'Acide Chlorhydrique 37%',        'category' => 'chemicals', 'offer_type' => 'sale', 'unit' => 'L',     'price' => 2500.00,  'safety_level' => 4, 'stock' => 120],
            ['name' => 'Hydroxyde de Sodium NaOH',       'category' => 'chemicals', 'offer_type' => 'sale', 'unit' => 'kg',    'price' => 1800.00,  'safety_level' => 3, 'stock' => 200],
            ['name' => 'Éthanol Absolu 99.8%',            'category' => 'chemicals', 'offer_type' => 'sale', 'unit' => 'L',     'price' => 3200.00,  'safety_level' => 3, 'stock' => 80],
            ['name' => 'Acide Sulfurique Concentré',      'category' => 'chemicals', 'offer_type' => 'sale', 'unit' => 'L',     'price' => 2800.00,  'safety_level' => 5, 'stock' => 50],
            ['name' => 'Acétone Pure',                     'category' => 'chemicals', 'offer_type' => 'sale', 'unit' => 'L',     'price' => 1500.00,  'safety_level' => 3, 'stock' => 150],
            ['name' => 'Phénolphtaléine Indicateur',       'category' => 'reagents',  'offer_type' => 'sale', 'unit' => 'g',     'price' => 4500.00,  'safety_level' => 2, 'stock' => 60],
            ['name' => 'Bleu de Méthylène',                'category' => 'reagents',  'offer_type' => 'sale', 'unit' => 'g',     'price' => 3800.00,  'safety_level' => 1, 'stock' => 90],
            ['name' => 'Nitrate d\'Argent AgNO3',          'category' => 'reagents',  'offer_type' => 'sale', 'unit' => 'g',     'price' => 12000.00, 'safety_level' => 3, 'stock' => 25],

            // Lab Equipment (for sale)
            ['name' => 'Bécher en Verre Borosilicate 500ml', 'category' => 'glassware', 'offer_type' => 'sale', 'unit' => 'piece', 'price' => 850.00,   'safety_level' => 1, 'stock' => 300],
            ['name' => 'Erlenmeyer 250ml',                    'category' => 'glassware', 'offer_type' => 'sale', 'unit' => 'piece', 'price' => 650.00,   'safety_level' => 1, 'stock' => 250],
            ['name' => 'Pipette Graduée 10ml',                'category' => 'glassware', 'offer_type' => 'sale', 'unit' => 'piece', 'price' => 450.00,   'safety_level' => 1, 'stock' => 400],
            ['name' => 'Burette Automatique 50ml',            'category' => 'glassware', 'offer_type' => 'sale', 'unit' => 'piece', 'price' => 7500.00,  'safety_level' => 1, 'stock' => 45],
            ['name' => 'Lames de Microscope (boîte de 50)',   'category' => 'consumables', 'offer_type' => 'sale', 'unit' => 'box',   'price' => 1200.00,  'safety_level' => 1, 'stock' => 180],
            ['name' => 'Gants Nitrile (boîte de 100)',        'category' => 'consumables', 'offer_type' => 'sale', 'unit' => 'box',   'price' => 2200.00,  'safety_level' => 1, 'stock' => 500],
            ['name' => 'Lunettes de Protection Chimie',       'category' => 'consumables', 'offer_type' => 'sale', 'unit' => 'piece', 'price' => 1800.00,  'safety_level' => 1, 'stock' => 200],

            // Lab Equipment (for rent)
            ['name' => 'Microscope Optique Binoculaire',      'category' => 'instruments', 'offer_type' => 'rent', 'unit' => 'piece', 'price' => 5000.00,  'safety_level' => 1, 'stock' => 15],
            ['name' => 'Balance Analytique Précision 0.1mg',  'category' => 'instruments', 'offer_type' => 'rent', 'unit' => 'piece', 'price' => 3500.00,  'safety_level' => 1, 'stock' => 10],
            ['name' => 'Spectrophotomètre UV-Visible',        'category' => 'instruments', 'offer_type' => 'rent', 'unit' => 'piece', 'price' => 12000.00, 'safety_level' => 1, 'stock' => 5],
            ['name' => 'Centrifugeuse de Laboratoire',         'category' => 'instruments', 'offer_type' => 'rent', 'unit' => 'piece', 'price' => 8000.00,  'safety_level' => 2, 'stock' => 8],
            ['name' => 'pH-mètre Digital',                     'category' => 'instruments', 'offer_type' => 'rent', 'unit' => 'piece', 'price' => 2000.00,  'safety_level' => 1, 'stock' => 20],

            // Biologicals
            ['name' => 'Gélose Nutritive (500g)',              'category' => 'biologicals', 'offer_type' => 'sale', 'unit' => 'g',     'price' => 6500.00,  'safety_level' => 1, 'stock' => 70],
            ['name' => 'Bouillon Mueller-Hinton',              'category' => 'biologicals', 'offer_type' => 'sale', 'unit' => 'L',     'price' => 4200.00,  'safety_level' => 1, 'stock' => 40],
            ['name' => 'Kit de Coloration de Gram',            'category' => 'biologicals', 'offer_type' => 'sale', 'unit' => 'kit',   'price' => 5500.00,  'safety_level' => 2, 'stock' => 55],
            ['name' => 'Boîtes de Pétri Stériles (paquet 20)', 'category' => 'biologicals', 'offer_type' => 'sale', 'unit' => 'pack',  'price' => 1800.00,  'safety_level' => 1, 'stock' => 350],
            ['name' => 'Anse de Platine',                      'category' => 'biologicals', 'offer_type' => 'sale', 'unit' => 'piece', 'price' => 3000.00,  'safety_level' => 1, 'stock' => 100],
        ];

        foreach ($products as $productData) {
            $category = $categories[$productData['category']];

            $product = Product::create([
                'business_id'         => $sellers->random()->id,
                'product_category_id' => $category->id,
                'name'                => $productData['name'],
                // slug is auto-generated by Product::boot()
                'offer_type'          => $productData['offer_type'],
                'unit'                => $productData['unit'],
                'price'               => $productData['price'],
                'safety_level'        => $productData['safety_level'],
                'msds_path'           => $productData['safety_level'] >= 3 ? 'msds/' . \Illuminate\Support\Str::slug($productData['name']) . '.pdf' : null,
                'documentations'      => rand(0, 1) ? 'docs/' . \Illuminate\Support\Str::slug($productData['name']) . '.pdf' : null,
                'stock'               => $productData['stock'],
                'is_available'        => true,
            ]);

            // Add main image
            ProductImage::create([
                'product_id' => $product->id,
                'url'        => $scientificImages[$productData['category']] ?? 'https://images.unsplash.com/photo-1532187875605-1ef6ec2360ee?q=80&w=800&auto=format&fit=crop',
                'is_main'    => true,
            ]);

            // Add a secondary image for some products
            if (rand(0, 1)) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'url'        => 'https://images.unsplash.com/photo-1579154235602-3c37ef401140?q=80&w=800&auto=format&fit=crop',
                    'is_main'    => false,
                ]);
            }
        }
    }
}
