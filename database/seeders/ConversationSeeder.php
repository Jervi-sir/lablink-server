<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get some users
    $users = User::inRandomOrder()->take(5)->get();

    if ($users->count() < 2) {
      return;
    }

    // Create a conversation between the first two users
    $conversation = Conversation::create([
      'user1_id' => $users[0]->id,
      'user2_id' => $users[1]->id,
    ]);

    // Add some messages
    Message::create([
      'conversation_id' => $conversation->id,
      'sender_id' => $users[0]->id,
      'message' => 'Hello there!',
      'type' => 'text',
    ]);

    Message::create([
      'conversation_id' => $conversation->id,
      'sender_id' => $users[1]->id,
      'message' => 'Hi, how are you?',
      'type' => 'text',
    ]);

    // create a few more random conversations
    for ($i = 0; $i < 3; $i++) {
      $u1 = $users->random();
      $u2 = $users->where('id', '!=', $u1->id)->random();

      if (!$u2) {
        continue;
      }

      $conv = Conversation::firstOrCreate([
        'user1_id' => min($u1->id, $u2->id),
        'user2_id' => max($u1->id, $u2->id),
      ]);

      Message::create([
        'conversation_id' => $conv->id,
        'sender_id' => $u1->id,
        'message' => 'Random test message from user: ' . $u1->id,
        'type' => 'text',
      ]);
    }
  }
}
