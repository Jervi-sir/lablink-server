<?php

namespace Database\Seeders;

use App\Models\LabCategory;
use Illuminate\Database\Seeder;

class LabCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['code' => 'medical_analysis', 'en' => 'Medical Analysis Lab', 'fr' => 'Laboratoire d\'analyses médicales', 'ar' => 'مخبر تحاليل طبية'],
            ['code' => 'biological_research', 'en' => 'Biological Research Lab', 'fr' => 'Laboratoire de recherche biologique', 'ar' => 'مخبر أبحاث بيولوجية'],
            ['code' => 'chemistry', 'en' => 'Chemistry Lab', 'fr' => 'Laboratoire de chimie', 'ar' => 'مخبر كيمياء'],
            ['code' => 'physics', 'en' => 'Physics Lab', 'fr' => 'Laboratoire de physique', 'ar' => 'مخبر فيزياء'],
            ['code' => 'genetic_engineering', 'en' => 'Genetic Engineering Lab', 'fr' => 'Laboratoire de génie génétique', 'ar' => 'مخبر الهندسة الوراثية'],
            ['code' => 'microbiology', 'en' => 'Microbiology Lab', 'fr' => 'Laboratoire de microbiologie', 'ar' => 'مخبر الميكروبيولوجيا'],
            ['code' => 'multidisciplinary', 'en' => 'Multidisciplinary Lab', 'fr' => 'Laboratoire multidisciplinaire', 'ar' => 'مخبر متعدد التخصصات'],
        ];

        foreach ($categories as $category) {
            LabCategory::create($category);
        }
    }
}
