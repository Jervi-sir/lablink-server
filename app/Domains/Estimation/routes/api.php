<?php

use App\Domains\Estimation\Controllers\EstimationRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/estimation-requests', [EstimationRequestController::class, 'index']);
    Route::post('/estimation-requests', [EstimationRequestController::class, 'store']);
    Route::get('/estimation-requests/{estimationRequest}', [EstimationRequestController::class, 'show']);
    Route::get('/business/estimation-requests', [EstimationRequestController::class, 'businessIndex']);
    Route::post('/estimation-requests/{estimationRequest}/quote', [EstimationRequestController::class, 'quote']);
    Route::post('/estimation-requests/{estimationRequest}/confirm', [EstimationRequestController::class, 'confirm']);
});
