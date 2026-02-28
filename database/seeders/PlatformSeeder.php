<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $platforms = [
      ['code' => 'phone', 'en' => 'Phone', 'ar' => 'هاتف', 'fr' => 'Téléphone', 'icon' => 'phone'],
      ['code' => 'whatsapp', 'en' => 'WhatsApp', 'ar' => 'واتساب', 'fr' => 'WhatsApp', 'icon' => 'whatsapp'],
      ['code' => 'email', 'en' => 'Email', 'ar' => 'بريد إلكتروني', 'fr' => 'Email', 'icon' => 'email'],
      ['code' => 'website', 'en' => 'Website', 'ar' => 'موقع إلكتروني', 'fr' => 'Site Web', 'icon' => 'web'],
      ['code' => 'linkedin', 'en' => 'LinkedIn', 'ar' => 'لينكد إن', 'fr' => 'LinkedIn', 'icon' => 'linkedin'],
      ['code' => 'instagram', 'en' => 'Instagram', 'ar' => 'إنستغرام', 'fr' => 'Instagram', 'icon' => 'instagram'],
      ['code' => 'facebook', 'en' => 'Facebook', 'ar' => 'فيسبوك', 'fr' => 'Facebook', 'icon' => 'facebook'],
    ];

    foreach ($platforms as $platform) {
      Platform::firstOrCreate(
        ['code' => $platform['code']],
        $platform
      );
    }
  }
}
