<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::post('student/register', [\App\Http\Controllers\Api\Auth\StudentAuthenticationController::class, 'register']);
    Route::post('business/register', [\App\Http\Controllers\Api\Auth\LabAuthenticationController::class, 'register']);

    Route::post('login', [\App\Http\Controllers\Api\Auth\AuthenticationController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('student/push-token', [\App\Http\Controllers\Api\PushTokenController::class, 'store']);
        Route::post('business/push-token', [\App\Http\Controllers\Api\PushTokenController::class, 'store']);
        Route::get('me', [\App\Http\Controllers\Api\Auth\AuthenticationController::class, 'me'])->middleware('auth:sanctum');
        Route::post('logout', [\App\Http\Controllers\Api\Auth\AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
    });
});

Route::get('/taxonomies', \App\Http\Controllers\Api\TaxonomyController::class);

Route::prefix('labs')->group(function() {
    Route::get('/', [\App\Http\Controllers\Api\LabController::class, 'index']);
    Route::get('{id}', [\App\Http\Controllers\Api\LabController::class, 'show']);
    Route::get('{id}/products', [\App\Http\Controllers\Api\LabController::class, 'products']);
});

Route::prefix('products')->group(function() {
    Route::get('/', [\App\Http\Controllers\Api\ProductController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/', [\App\Http\Controllers\Api\ProductController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{id}', [\App\Http\Controllers\Api\ProductController::class, 'update'])->middleware('auth:sanctum');
    Route::get('/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\ProductController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('orders')->group(function() {
    Route::get('/', [\App\Http\Controllers\OrderController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/', [\App\Http\Controllers\OrderController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/{id}', [\App\Http\Controllers\OrderController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/{id}/signature', [\App\Http\Controllers\OrderController::class, 'signature'])->middleware('auth:sanctum');
});

// Lab routes
Route::prefix('lab')->middleware('auth:sanctum')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Api\Lab\ProductController::class, 'index']);
    // Lab Stats
    Route::get('/stats', [\App\Http\Controllers\Api\Lab\StatsController::class, 'index']);
    // Lab Orders
    Route::get('/orders', [\App\Http\Controllers\LabOrderController::class, 'index']);
    Route::get('/orders/{id}', [\App\Http\Controllers\LabOrderController::class, 'show']);
    Route::post('/orders/{id}/status', [\App\Http\Controllers\LabOrderController::class, 'updateStatus']);
});

Route::post('upload-temp', [\App\Http\Controllers\Api\UploadController::class, 'temp']);

// Profile routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::post('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
});