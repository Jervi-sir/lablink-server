<?php

use App\Domains\Student\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/students/me', [StudentController::class, 'me']);
  Route::put('/students/me', [StudentController::class, 'updateMe']);
  Route::post('/students', [StudentController::class, 'store']);
  Route::put('/students/{student}', [StudentController::class, 'update']);
});

Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{student}', [StudentController::class, 'show']);
