<?php

use App\Domains\Conversation\Controllers\ConversationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/conversations', [ConversationController::class, 'index']);
  Route::post('/conversations', [ConversationController::class, 'store']);
  Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
  Route::post('/conversations/{conversation}/messages', [ConversationController::class, 'sendMessage']);
});
