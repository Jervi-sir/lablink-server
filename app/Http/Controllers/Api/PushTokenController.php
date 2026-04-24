<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushToken;
use Illuminate\Http\Request;

class PushTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'expo_push_token' => 'required|string',
        ]);

        $user = $request->user();

        PushToken::firstOrCreate([
            'user_id' => $user->id,
            'token' => $request->expo_push_token,
        ]);

        return response()->json(['message' => 'Push token stored successfully']);
    }
}
