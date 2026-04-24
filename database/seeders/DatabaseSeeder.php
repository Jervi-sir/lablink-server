<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WilayaSeeder::class,
            ProductCategorySeeder::class,
            LabCategorySeeder::class,
        ]);

        // User::factory()->create([
        //     'phone_number' => '0558054300',
        //     'email' => 'test@example.com',
        // ]);
    }
}
