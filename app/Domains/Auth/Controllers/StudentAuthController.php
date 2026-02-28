<?php

namespace App\Domains\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentAuthController extends Controller
{
  public function login(Request $request): JsonResponse
  {
    $request->validate([
      'email' => ['required', 'string', 'email'],
      'password' => ['required', 'string'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
      throw ValidationException::withMessages([
        'email' => ['The provided credentials do not match our records.'],
      ]);
    }

    return response()->json([
      'message' => 'Login successful',
      'user' => $user->load(['role', 'studentProfile.department.university.wilaya'])->format(),
      'access_token' => $user->createToken('auth_token')->plainTextToken,
      'token_type' => 'Bearer',
    ]);
  }

  public function register(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'string', 'min:8'],
      'full_name' => ['required', 'string', 'max:255'],
      'student_card_id' => ['nullable', 'string', 'max:40', 'unique:student_profiles,student_card_id'],
      'department_id' => ['nullable', 'integer', 'exists:departments,id'],
    ]);

    return DB::transaction(function () use ($validated) {
      $role = Role::where('code', 'student')->first();

      $user = User::create([
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'password_plaintext' => $validated['password'],
        'role_id' => $role->id,
        'is_verified' => true,
      ]);

      $user->studentProfile()->create([
        'fullname' => $validated['full_name'],
        'student_card_id' => $validated['student_card_id'] ?? null,
        'department_id' => $validated['department_id'] ?? null,
      ]);

      // Create default notification settings
      $user->notificationSetting()->create([]);

      return response()->json([
        'message' => 'User registered successfully',
        'user' => $user->load(['role', 'studentProfile.department.university.wilaya'])->format(),
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
