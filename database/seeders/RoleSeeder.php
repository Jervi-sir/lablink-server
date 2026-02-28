<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the roles table.
     */
    public function run(): void
    {
        $roles = [
            ['code' => 'student',  'en' => 'Student',  'ar' => 'طالب', 'fr' => 'Étudiant'],
            ['code' => 'business', 'en' => 'Business', 'ar' => 'عمل',   'fr' => 'Entreprise'],
            ['code' => 'admin',    'en' => 'Admin',    'ar' => 'مدير', 'fr' => 'Admin'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['code' => $role['code']],
                ['en' => $role['en'], 'ar' => $role['ar'], 'fr' => $role['fr']]
            );
        }
    }
}
