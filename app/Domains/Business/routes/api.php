<?php

use App\Domains\Business\Controllers\BusinessController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  Route::get('businesses/me', [BusinessController::class, 'me']);
  Route::post('businesses', [BusinessController::class, 'store']);
  Route::put('businesses/{business}', [BusinessController::class, 'update']);
  Route::post('businesses/{business}/toggle-follow', [BusinessController::class, 'toggleFollow']);
  Route::post('businesses/{business}/toggle-save', [BusinessController::class, 'toggleSave']);
});

Route::get('businesses', [BusinessController::class, 'index']);
Route::get('businesses/featured-labs', [BusinessController::class, 'featuredLabs']);
Route::get('businesses/top-labs', [BusinessController::class, 'topLabs']);
Route::get('businesses/{business}', [BusinessController::class, 'show']);
Route::get('businesses/{business}/products', [BusinessController::class, 'products']);
