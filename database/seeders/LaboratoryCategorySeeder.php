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
      ['code' => 'chemistry',      'en' => 'Chemistry',      'ar' => 'كيمياء',       'fr' => 'Chimie'],
      ['code' => 'biology',        'en' => 'Biology',        'ar' => 'بيولوجيا',      'fr' => 'Biologie'],
      ['code' => 'physics',        'en' => 'Physics',        'ar' => 'فيزياء',       'fr' => 'Physique'],
      ['code' => 'biochemistry',   'en' => 'Biochemistry',   'ar' => 'كيمياء حيوية',   'fr' => 'Biochimie'],
      ['code' => 'microbiology',   'en' => 'Microbiology',   'ar' => 'علم الأحياء الدقيقة', 'fr' => 'Microbiologie'],
      ['code' => 'geology',        'en' => 'Geology',        'ar' => 'جيولوجيا',      'fr' => 'Géologie'],
      ['code' => 'environmental',  'en' => 'Environmental',  'ar' => 'بيئي',         'fr' => 'Environnemental'],
      ['code' => 'pharmaceutical', 'en' => 'Pharmaceutical', 'ar' => 'صيدلاني',      'fr' => 'Pharmaceutique'],
      ['code' => 'general',       'en' => 'General',       'ar' => 'عام',          'fr' => 'Général'],
    ];

    foreach ($types as $type) {
      LaboratoryCategory::firstOrCreate(
        ['code' => $type['code']],
        ['en' => $type['en'], 'ar' => $type['ar'], 'fr' => $type['fr']]
      );
    }
  }
}
