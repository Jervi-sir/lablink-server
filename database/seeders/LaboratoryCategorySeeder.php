<?php

namespace Database\Seeders;

use App\Models\LaboratoryCategory;
use Illuminate\Database\Seeder;

class LaboratoryCategorySeeder extends Seeder
{
  /**
   * Seed the laboratory_categories table.
   */
  public function run(): void
  {
    $types = [
      'chemistry',
      'biology',
      'physics',
      'biochemistry',
      'microbiology',
      'geology',
      'environmental',
      'pharmaceutical',
      'general',
    ];

    foreach ($types as $type) {
      LaboratoryCategory::firstOrCreate(['code' => $type]);
    }
  }
}
