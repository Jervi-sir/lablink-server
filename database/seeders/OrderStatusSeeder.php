<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
  /**
   * Seed the order_statuses table.
   */
  public function run(): void
  {
    $statuses = [
      ['code' => 'pending',    'en' => 'Pending',    'ar' => 'قيد الانتظار', 'fr' => 'En attente', 'is_final' => false],
      ['code' => 'processing', 'en' => 'Processing', 'ar' => 'قيد المعالجة', 'fr' => 'En cours',   'is_final' => false],
      ['code' => 'ready',      'en' => 'Ready',      'ar' => 'جاهز',        'fr' => 'Prêt',       'is_final' => false],
      ['code' => 'done',       'en' => 'Done',       'ar' => 'مكتمل',       'fr' => 'Terminé',    'is_final' => true],
      ['code' => 'cancelled',  'en' => 'Cancelled',  'ar' => 'ملغى',        'fr' => 'Annulé',     'is_final' => true],
    ];

    foreach ($statuses as $status) {
      OrderStatus::firstOrCreate(
        ['code' => $status['code']],
        [
          'en' => $status['en'],
          'ar' => $status['ar'],
          'fr' => $status['fr'],
          'is_final' => $status['is_final']
        ]
      );
    }
  }
}
