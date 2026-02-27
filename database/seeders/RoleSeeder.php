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
        $roles = ['student', 'business', 'admin'];

        foreach ($roles as $role) {
            Role::create(['code' => $role]);
        }
    }
}
