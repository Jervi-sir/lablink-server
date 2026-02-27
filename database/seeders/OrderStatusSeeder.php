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
      ['code' => 'pending',    'is_final' => false],
      ['code' => 'processing', 'is_final' => false],
      ['code' => 'ready',      'is_final' => false],
      ['code' => 'done',       'is_final' => true],
      ['code' => 'cancelled',  'is_final' => true],
    ];

    foreach ($statuses as $status) {
      OrderStatus::firstOrCreate(['code' => $status['code']], ['is_final' => $status['is_final']]);
    }
  }
}
