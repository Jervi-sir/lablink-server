<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/taxonomies', \App\Http\Controllers\Api\TaxonomyController::class);

Route::get('/labs', [\App\Http\Controllers\Api\LabController::class, 'index']);
Route::get('/labs/{id}', [\App\Http\Controllers\Api\LabController::class, 'show']);
Route::get('/labs/{id}/products', [\App\Http\Controllers\Api\LabController::class, 'products']);

Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index'])->middleware('auth:sanctum');
Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
Route::delete('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/products', [\App\Http\Controllers\Api\ProductController::class, 'store'])->middleware('auth:sanctum');

Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->middleware('auth:sanctum');
Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->middleware('auth:sanctum');

Route::post('auth/login', [\App\Http\Controllers\Api\AuthenticationController::class, 'login']);
Route::get('auth/me', [\App\Http\Controllers\Api\AuthenticationController::class, 'me'])->middleware('auth:sanctum');
Route::post('auth/logout', [\App\Http\Controllers\Api\AuthenticationController::class, 'logout'])->middleware('auth:sanctum');

Route::post('auth/student/register', [\App\Http\Controllers\Api\StudentAuthenticationController::class, 'register']);
Route::post('auth/business/register', [\App\Http\Controllers\Api\LabAuthenticationController::class, 'register']);

Route::post('upload-temp', [\App\Http\Controllers\Api\UploadController::class, 'temp']);
Route::post('auth/business/register', [\App\Http\Controllers\Api\LabAuthenticationController::class, 'register']);

Route::post('upload-temp', [\App\Http\Controllers\Api\UploadController::class, 'temp']);

// Profile routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::post('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
});

// Lab routes
Route::middleware('auth:sanctum')->prefix('lab')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Api\Lab\ProductController::class, 'index']);
    
    // Lab Orders
    Route::get('/orders', [\App\Http\Controllers\LabOrderController::class, 'index']);
    Route::get('/orders/{id}', [\App\Http\Controllers\LabOrderController::class, 'show']);
    Route::post('/orders/{id}/status', [\App\Http\Controllers\LabOrderController::class, 'updateStatus']);
});

// Push token routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/student/push-token', [\App\Http\Controllers\Api\PushTokenController::class, 'store']);
    Route::post('auth/business/push-token', [\App\Http\Controllers\Api\PushTokenController::class, 'store']);
});

// Manual notification trigger
