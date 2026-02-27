<?php

use App\Domains\Order\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/orders', [OrderController::class, 'index']);
  Route::post('/orders', [OrderController::class, 'store']);
  Route::get('/orders/{order}', [OrderController::class, 'show']);
  Route::patch('/orders/{order}/status', [OrderController::class, 'update']);
  Route::get('/business/orders', [OrderController::class, 'businessOrders']);
});
