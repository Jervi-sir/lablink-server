<?php

namespace Database\Seeders;

use App\Models\BusinessProfile;
use App\Models\BusinessCategory;
use App\Models\LaboratoryCategory;
use App\Models\Department;
use App\Models\Role;
use App\Models\StudentProfile;
use App\Models\User;
use App\Models\Wilaya;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed users with their corresponding profiles.
     * Creates: 1 admin, 5 labs, 3 wholesalers, 10 students.
     */
    public function run(): void
    {
        $roles    = Role::all()->keyBy('code');
        $wilayas  = Wilaya::all();
        $businessCategories = BusinessCategory::all()->keyBy('code');
        $laboratoryCategories = LaboratoryCategory::all();
        $departments = Department::all();

        // ─── Admin ──────────────────────────────────────────
        User::create([
            'role_id'      => $roles['admin']->id,
            'email'        => 'admin@labmarket.dz',
            'phone_number' => '+213 550 000 000',
            'password'     => Hash::make('password'),
            'avatar'       => null,
            'is_verified'  => true,
        ]);

        // ─── Laboratory Users ───────────────────────────────
        $labNames = [
            ['name' => 'Laboratoire Biopharma Plus',   'nif' => '00016001234567', 'description' => 'Leading supplier of pharmaceutical-grade reagents in Algeria.'],
            ['name' => 'ChemLab Solutions',             'nif' => '00031009876543', 'description' => 'Specialized in organic and inorganic chemical supplies.'],
            ['name' => 'BioScience Algérie',            'nif' => '00025005551234', 'description' => 'Research-grade biological reagents and lab consumables.'],
            ['name' => 'PhysLab Instruments',            'nif' => '00019003214567', 'description' => 'Precision instruments for physics and engineering labs.'],
            ['name' => 'MicroTech Diagnostics',          'nif' => '00009007654321', 'description' => 'Microbiological testing kits and diagnostic reagents.'],
        ];

        foreach ($labNames as $i => $lab) {
            $user = User::create([
                'role_id'      => $roles['business']->id,
                'email'        => 'lab' . ($i + 1) . '@labmarket.dz',
                'phone_number' => '+213 55' . rand(1000000, 9999999),
                'password'     => Hash::make('password'),
                'password_plaintext' => 'password',
                'avatar'       => null,
                'is_verified'  => true,
            ]);

            BusinessProfile::create([
                'user_id'         => $user->id,
                'name'            => $lab['name'],
                'nif'             => $lab['nif'],
                'logo'            => 'https://images.unsplash.com/photo-1514416432279-d0faae13f4dd?q=80&w=200&h=200&auto=format&fit=crop', // Lab logo placeholder
                'description'             => $lab['description'],
                'certificate_url' => 'certificates/lab_' . ($i + 1) . '.pdf',
                'address'         => 'Zone Industrielle, Lot ' . rand(1, 50) . ', ' . $wilayas->random()->name,
                'business_category_id'   => $businessCategories['lab']->id,
                'laboratory_category_id' => $laboratoryCategories->random()->id,
                'wilaya_id'       => $wilayas->random()->id,
            ]);
        }

        // ─── Wholesaler Users ───────────────────────────────
        $wholesalerNames = [
            ['name' => 'SciSupply DZ',        'nif' => '00042001112233', 'description' => 'Bulk scientific supplies at competitive prices.'],
            ['name' => 'LabEquip Wholesale',   'nif' => '00031004445566', 'description' => 'National distributor for lab equipment and glassware.'],
            ['name' => 'Reactif Plus',          'nif' => '00016007778899', 'description' => 'Wholesale reagents and chemicals for industrial labs.'],
        ];

        foreach ($wholesalerNames as $i => $ws) {
            $user = User::create([
                'role_id'      => $roles['business']->id,
                'email'        => 'wholesale' . ($i + 1) . '@labmarket.dz',
                'phone_number' => '+213 56' . rand(1000000, 9999999),
                'password'     => Hash::make('password'),
                'password_plaintext' => 'password',
                'avatar'       => null,
                'is_verified'  => true,
            ]);

            BusinessProfile::create([
                'user_id'         => $user->id,
                'name'            => $ws['name'],
                'nif'             => $ws['nif'],
                'logo'            => 'https://images.unsplash.com/photo-1532187875605-1ef6ec2360ee?q=80&w=200&h=200&auto=format&fit=crop', // Wholesale logo placeholder
                'description'             => $ws['description'],
                'certificate_url' => 'certificates/wholesale_' . ($i + 1) . '.pdf',
                'address'         => 'Boulevard du Commerce, N° ' . rand(1, 200) . ', ' . $wilayas->random()->name,
                'business_category_id'   => $businessCategories['wholesale']->id,
                'laboratory_category_id' => $laboratoryCategories->random()->id,
                'wilaya_id'       => $wilayas->random()->id,
            ]);
        }

        // ─── Student Users ──────────────────────────────────
        $studentFirstNames = ['Amine', 'Yasmine', 'Mehdi', 'Sara', 'Karim', 'Nadia', 'Omar', 'Lina', 'Rami', 'Amira'];
        $studentLastNames  = ['Benali', 'Khelifi', 'Bouzid', 'Medjdoub', 'Hadj', 'Mansouri', 'Saidi', 'Zerhouni', 'Boudiaf', 'Rahmani'];

        foreach ($studentFirstNames as $i => $firstName) {
            $lastName = $studentLastNames[$i];
            $user = User::create([
                'role_id'      => $roles['student']->id,
                'email'        => strtolower($firstName) . '.' . strtolower($lastName) . '@etu.dz',
                'phone_number' => '+213 7' . rand(10000000, 99999999),
                'password'     => Hash::make('password'),
                'avatar'       => null,
                'is_verified'  => $i < 7, // first 7 are verified
            ]);

            StudentProfile::create([
                'user_id'         => $user->id,
                'fullname'        => $firstName . ' ' . $lastName,
                'student_card_id' => 'STU-' . date('Y') . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'department_id'   => $departments->random()->id,
            ]);
        }
    }
}
