<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a push notification to a specific user.
     */
    public function sendPushNotification(User $user, string $title, string $body, array $data = [])
    {
        $tokens = $user->pushTokens()->pluck('token')->toArray();

        if (empty($tokens)) {
            Log::info("No push tokens found for user ID: {$user->id}");
            return;
        }

        $messages = [];
        foreach ($tokens as $token) {
            // Simple check to ensure we are sending to an Expo token
            if (!str_contains($token, 'ExponentPushToken') && !str_contains($token, 'ExpoPushToken')) {
                Log::warning("Skipping invalid token format: $token");
                continue;
            }

            $msg = [
                'to' => $token,
                'title' => $title,
                'body' => $body,
            ];

            if (!empty($data)) {
                $msg['data'] = $data;
            }

            $messages[] = $msg;
        }

        if (empty($messages)) {
            Log::info("No valid tokens after filtering for user ID: {$user->id}");
            return;
        }

        try {
            // Expo accepts a single object OR an array.
            // Let's send exactly what matches the curl if there's only one token.
            $payload = count($messages) === 1 ? $messages[0] : $messages;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', $payload);
            
            if (!$response->successful()) {
                Log::error("Failed to send push notifications to user ID: {$user->id}. Response: " . $response->body());
            }
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error("Error sending push notification: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
