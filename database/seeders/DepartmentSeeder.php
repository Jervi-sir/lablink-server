<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\University;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Seed departments — attaches a random subset to each university.
     */
    public function run(): void
    {
        $departmentNames = [
            'Chimie',
            'Biologie',
            'Physique',
            'Mathématiques',
            'Informatique',
            'Génie Civil',
            'Génie Mécanique',
            'Génie Électrique',
            'Pharmacie',
            'Médecine',
            'Sciences de la Terre',
            'Biochimie',
            'Microbiologie',
            'Génie des Procédés',
            'Architecture',
        ];

        $universities = University::all();

        foreach ($universities as $university) {
            // Give each university 4–8 random departments
            $selected = collect($departmentNames)->shuffle()->take(rand(4, 8));

            foreach ($selected as $name) {
                Department::create([
                    'name'          => $name,
                    'university_id' => $university->id,
                ]);
            }
        }
    }
}
