<?php

use App\Domains\Auth\Controllers\StudentAuthController;
use App\Domains\Auth\Controllers\BusinessAuthController;
use App\Domains\Auth\Controllers\MeController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth/student')->group(function () {
  Route::post('register', [StudentAuthController::class, 'register']);
  Route::post('login', [StudentAuthController::class, 'login']);

  // Protected routes
  Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', MeController::class);
    Route::post('logout', [StudentAuthController::class, 'logout']);
    Route::post('push-token', [StudentAuthController::class, 'updatePushToken']);
    Route::get('notification-settings', [StudentAuthController::class, 'getNotificationSettings']);
    Route::put('notification-settings', [StudentAuthController::class, 'updateNotificationSettings']);
  });
});

Route::prefix('auth/business')->group(function () {
  Route::post('register', [BusinessAuthController::class, 'register']);
  Route::post('login', [BusinessAuthController::class, 'login']);

  // Protected routes
  Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', MeController::class);
    Route::post('logout', [BusinessAuthController::class, 'logout']);
    Route::post('push-token', [BusinessAuthController::class, 'updatePushToken']);
    Route::get('notification-settings', [BusinessAuthController::class, 'getNotificationSettings']);
    Route::put('notification-settings', [BusinessAuthController::class, 'updateNotificationSettings']);
  });
});
