<?php

use App\Domains\Product\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/products/{product}/toggle-save', [ProductController::class, 'toggleSave']);
  Route::post('/products', [ProductController::class, 'store']);
  Route::put('/products/{product}', [ProductController::class, 'update']);
  Route::delete('/products/{product}', [ProductController::class, 'destroy']);
  Route::get('/business/inventory', [ProductController::class, 'inventory']);
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/trending-products', [ProductController::class, 'trending']);
Route::get('/products/recent', [ProductController::class, 'recent']);
Route::get('/products/{product}', [ProductController::class, 'show']);
