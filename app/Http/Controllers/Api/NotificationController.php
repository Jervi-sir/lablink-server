<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Send a push notification to a specific user by their ID.
     * For internal/admin testing or manual triggers.
     */
    public function sendToUser(Request $request)
    {
        $user = User::findOrFail(8);
        
        $notificationService = app(NotificationService::class);
        
        $notificationService->sendPushNotification(
            $user,
            'Manual Test Notification',
            'This is a test notification from the server',
            ['type' => 'test_manual']
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Notification sent request processed successfully'
        ]);
    }
}
