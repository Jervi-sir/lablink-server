<?php

use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\Auth\LabAuthenticationController;
use App\Http\Controllers\Api\Auth\StudentAuthenticationController;
use App\Http\Controllers\Api\Lab\StatsController;
use App\Http\Controllers\Api\LabController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PushTokenController;
use App\Http\Controllers\Api\TaxonomyController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\LabOrderController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('student/register', [StudentAuthenticationController::class, 'register']);
    Route::post('business/register', [LabAuthenticationController::class, 'register']);

    Route::post('login', [AuthenticationController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('student/push-token', [PushTokenController::class, 'store']);
        Route::post('business/push-token', [PushTokenController::class, 'store']);
        Route::get('me', [AuthenticationController::class, 'me'])->middleware('auth:sanctum');
        Route::post('logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
    });
});

Route::get('/taxonomies', TaxonomyController::class);

Route::prefix('labs')->group(function () {
    Route::get('/', [LabController::class, 'index']);
    Route::get('{lab}', [LabController::class, 'show']);
    Route::get('{lab}/products', [LabController::class, 'products']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/', [ProductController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum');
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::delete('/{id}', [ProductController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/', [OrderController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/{id}', [OrderController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/{id}/signature', [OrderController::class, 'signature'])->middleware('auth:sanctum');
    Route::post('/{id}/negotiate', [OrderController::class, 'negotiate'])->middleware('auth:sanctum');
    Route::post('/{id}/read', [OrderController::class, 'markAsRead'])->middleware('auth:sanctum');
    Route::delete('/{id}', [OrderController::class, 'destroy'])->middleware('auth:sanctum');
});

// Lab routes
Route::prefix('lab')->middleware('auth:sanctum')->group(function () {
    Route::get('/products', [App\Http\Controllers\Api\Lab\ProductController::class, 'index']);
    // Lab Stats
    Route::get('/stats', [StatsController::class, 'index']);
    // Lab Orders
    Route::get('/orders', [LabOrderController::class, 'index']);
    Route::get('/orders/{id}', [LabOrderController::class, 'show']);
    Route::post('/orders/{id}/status', [LabOrderController::class, 'updateStatus']);
    Route::post('/orders/{id}/negotiate', [LabOrderController::class, 'negotiate']);
    Route::post('/orders/{id}/read', [LabOrderController::class, 'markAsRead']);
});

Route::post('upload-temp', [UploadController::class, 'temp']);

// Profile routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
});
