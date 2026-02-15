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
        $roles = ['student', 'lab', 'wholesale', 'admin'];

        foreach ($roles as $role) {
            Role::create(['code' => $role]);
        }
    }
}
