<?php

use App\Domains\Stat\Controllers\StatController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/stats', [StatController::class, 'index']);
});
