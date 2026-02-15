<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters — lookup tables first, then users/profiles, then marketplace.
     */
    public function run(): void
    {
        $this->call([
            // 1. Core Lookups
            WilayaSeeder::class,
            RoleSeeder::class,
            LabTypeSeeder::class,
            UniversitySeeder::class,
            DepartmentSeeder::class,

            // 2. Users & Profiles
            UserSeeder::class,

            // 3. Marketplace
            ProductSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
