<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
  protected $fillable = [
    'user_id',
    'expo_push_token',
    'enabled',
    'enable_order_status_updates',
    'enable_chat_messages',
    'enable_promotions',
  ];

  protected function casts(): array
  {
    return [
      'enabled' => 'boolean',
      'enable_order_status_updates' => 'boolean',
      'enable_chat_messages' => 'boolean',
      'enable_promotions' => 'boolean',
    ];
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function format(): array
  {
    return [
      'id' => $this->id,
      'expoPushToken' => $this->expo_push_token,
      'enabled' => $this->enabled,
      'enableOrderStatusUpdates' => $this->enable_order_status_updates,
      'enableChatMessages' => $this->enable_chat_messages,
      'enablePromotions' => $this->enable_promotions,
    ];
  }
}
