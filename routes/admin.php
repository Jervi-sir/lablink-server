<?php

use App\Http\Controllers\Admin\AdminBusinessController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
  ->middleware(['auth', 'admin'])
  ->name('admin.')
  ->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-verification', [AdminUserController::class, 'toggleVerification'])->name('users.toggle-verification');

    // Businesses
    Route::get('/businesses', [AdminBusinessController::class, 'index'])->name('businesses.index');
    Route::patch('/businesses/{business}/toggle-featured', [AdminBusinessController::class, 'toggleFeatured'])->name('businesses.toggle-featured');

    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::patch('/products/{product}/toggle-trending', [AdminProductController::class, 'toggleTrending'])->name('products.toggle-trending');
    Route::patch('/products/{product}/toggle-availability', [AdminProductController::class, 'toggleAvailability'])->name('products.toggle-availability');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
  });
