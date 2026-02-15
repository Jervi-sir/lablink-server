<?php

namespace Database\Seeders;

use App\Models\LabType;
use Illuminate\Database\Seeder;

class LabTypeSeeder extends Seeder
{
    /**
     * Seed the lab_types table.
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
            LabType::create(['code' => $type]);
        }
    }
}
