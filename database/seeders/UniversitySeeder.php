<?php

namespace Database\Seeders;

use App\Models\University;
use App\Models\Wilaya;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Seed the universities table with notable Algerian universities.
     */
    public function run(): void
    {
        $universities = [
            // Alger (16)
            ['name' => 'Université des Sciences et de la Technologie Houari Boumediene (USTHB)', 'wilaya_code' => '16'],
            ['name' => 'Université d\'Alger 1 Benyoucef Benkhedda', 'wilaya_code' => '16'],
            ['name' => 'Université d\'Alger 2 Abou El Kacem Saad Allah', 'wilaya_code' => '16'],
            ['name' => 'Université d\'Alger 3 Ibrahim Sultan Chebbout', 'wilaya_code' => '16'],

            // Oran (31)
            ['name' => 'Université des Sciences et de la Technologie d\'Oran Mohamed Boudiaf (USTO-MB)', 'wilaya_code' => '31'],
            ['name' => 'Université d\'Oran 1 Ahmed Ben Bella', 'wilaya_code' => '31'],
            ['name' => 'Université d\'Oran 2 Mohamed Ben Ahmed', 'wilaya_code' => '31'],

            // Constantine (25)
            ['name' => 'Université des Frères Mentouri Constantine 1', 'wilaya_code' => '25'],
            ['name' => 'Université Constantine 2 Abdelhamid Mehri', 'wilaya_code' => '25'],
            ['name' => 'Université Constantine 3 Salah Boubnider', 'wilaya_code' => '25'],

            // Annaba (23)
            ['name' => 'Université Badji Mokhtar Annaba', 'wilaya_code' => '23'],

            // Tlemcen (13)
            ['name' => 'Université Abou Bakr Belkaïd Tlemcen', 'wilaya_code' => '13'],

            // Sétif (19)
            ['name' => 'Université Ferhat Abbas Sétif 1', 'wilaya_code' => '19'],

            // Béjaïa (06)
            ['name' => 'Université Abderrahmane Mira de Béjaïa', 'wilaya_code' => '06'],

            // Blida (09)
            ['name' => 'Université Saad Dahlab Blida 1', 'wilaya_code' => '09'],

            // Tizi Ouzou (15)
            ['name' => 'Université Mouloud Mammeri de Tizi Ouzou', 'wilaya_code' => '15'],

            // Batna (05)
            ['name' => 'Université Mostefa Ben Boulaïd Batna 2', 'wilaya_code' => '05'],

            // Biskra (07)
            ['name' => 'Université Mohamed Khider Biskra', 'wilaya_code' => '07'],

            // Boumerdès (35)
            ['name' => 'Université M\'Hamed Bougara Boumerdès', 'wilaya_code' => '35'],

            // Médéa (26)
            ['name' => 'Université Yahia Farès de Médéa', 'wilaya_code' => '26'],
        ];

        foreach ($universities as $uni) {
            $wilaya = Wilaya::where('code', $uni['wilaya_code'])->first();

            if ($wilaya) {
                University::create([
                    'name'      => $uni['name'],
                    'wilaya_id' => $wilaya->id,
                ]);
            }
        }
    }
}
