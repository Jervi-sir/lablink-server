<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\LabContractController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

Route::get('notifications/send-to-user', [NotificationController::class, 'sendToUser']);

Route::get('/fake-contract', [LabContractController::class, 'show']);

require __DIR__.'/settings.php';
