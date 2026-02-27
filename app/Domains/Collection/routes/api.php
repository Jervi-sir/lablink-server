<?php

use App\Domains\Collection\Controllers\CollectionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/collections/saved-products', [CollectionController::class, 'savedProducts']);
  Route::get('/collections/saved-businesses', [CollectionController::class, 'savedBusinesses']);
  Route::get('/collections/followed-businesses', [CollectionController::class, 'followedBusinesses']);
});
