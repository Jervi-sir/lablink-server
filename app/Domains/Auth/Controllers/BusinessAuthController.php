<?php

namespace App\Domains\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\BusinessProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class BusinessAuthController extends Controller
{
  public function login(Request $request): JsonResponse
  {
    $request->validate([
      'email' => ['required', 'string', 'email'],
      'password' => ['required', 'string'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      throw ValidationException::withMessages([
        'email' => ['The provided credentials do not match our records.'],
      ]);
    }

    // Ensure the user has a business-related role
    if (!$user->isBusiness()) {
      throw ValidationException::withMessages([
        'email' => ['This account is not registered as a business.'],
      ]);
    }

    return response()->json([
      'message' => 'Login successful',
      'user' => $user->load(['role', 'businessProfile.businessCategory', 'businessProfile.laboratoryCategory', 'businessProfile.wilaya'])->format(),
      'access_token' => $user->createToken('auth_token')->plainTextToken,
      'token_type' => 'Bearer',
    ]);
  }

  public function register(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'string', 'min:8'],
      'name' => ['required', 'string', 'max:255'],
      'nif' => ['nullable', 'string', 'max:40'],
      'business_registration_no' => ['nullable', 'string', 'max:40'],
      'type' => ['nullable', 'string', 'max:255'],
      'contact_name' => ['nullable', 'string', 'max:255'],
      'bio' => ['nullable', 'string'],
      'specializations' => ['nullable', 'array'],
      'laboratory_category_id' => ['nullable', 'integer', 'exists:laboratory_categories,id'],
      'business_category_id' => ['nullable', 'integer', 'exists:business_categories,id'],
      'wilaya_id' => ['nullable', 'integer', 'exists:wilayas,id'],
      'address' => ['nullable', 'string', 'max:500'],
      // Add more fields if needed
    ]);

    return DB::transaction(function () use ($validated) {
      $role = Role::where('code', 'business')->first();

      $user = User::create([
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role_id' => $role->id,
        'is_verified' => true,
      ]);

      $user->businessProfile()->create([
        'name' => $validated['name'],
        'nif' => $validated['nif'] ?? null,
        'bio' => $validated['bio'] ?? null,
        'laboratory_category_id' => $validated['laboratory_category_id'] ?? null,
        'business_category_id' => $validated['business_category_id'] ?? null,
        'wilaya_id' => $validated['wilaya_id'] ?? null,
        'address' => $validated['address'] ?? null,
        // We'll map 'type' and 'contact_name' to appropriate fields or metadata if available
        // For now, let's keep it simple
      ]);

      // Create default notification settings
      $user->notificationSetting()->create([]);

      return response()->json([
        'message' => 'Business registered successfully',
        'user' => $user->load(['role', 'businessProfile'])->format(),
        'access_token' => $user->createToken('auth_token')->plainTextToken,
        'token_type' => 'Bearer',
      ], 201);
    });
  }

  public function updatePushToken(Request $request): JsonResponse
  {
    $request->validate([
      'expo_push_token' => ['required', 'string'],
    ]);

    $request->user()->notificationSetting()->updateOrCreate(
      ['user_id' => $request->user()->id],
      ['expo_push_token' => $request->expo_push_token]
    );

    return response()->json([
      'message' => 'Push token updated successfully',
    ]);
  }

  public function getNotificationSettings(Request $request): JsonResponse
  {
    $settings = $request->user()->notificationSetting;

    if (!$settings) {
      $settings = $request->user()->notificationSetting()->create([]);
    }

    return response()->json([
      'settings' => $settings->format(),
    ]);
  }

  public function updateNotificationSettings(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'enabled' => ['sometimes', 'boolean'],
      'enable_order_status_updates' => ['sometimes', 'boolean'],
      'enable_chat_messages' => ['sometimes', 'boolean'],
      'enable_promotions' => ['sometimes', 'boolean'],
    ]);

    $settings = $request->user()->notificationSetting()->updateOrCreate(
      ['user_id' => $request->user()->id],
      $validated
    );

    return response()->json([
      'message' => 'Notification settings updated',
      'settings' => $settings->format(),
    ]);
  }

  public function logout(Request $request): JsonResponse
  {
    // Clear push token on logout
    $request->user()->notificationSetting()?->update([
      'expo_push_token' => null,
    ]);

    $request->user()->currentAccessToken()->delete();

    return response()->json([
      'message' => 'Logged out successfully',
    ]);
  }
}
