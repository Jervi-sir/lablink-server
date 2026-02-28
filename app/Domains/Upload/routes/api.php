<?php

use App\Domains\Upload\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  // Product Images
  Route::post('/products/{product}/images', [UploadController::class, 'uploadProductImages']);
  Route::delete('/product-images/{productImage}', [UploadController::class, 'deleteProductImage']);
  Route::post('/product-images/{productImage}/set-main', [UploadController::class, 'setMainProductImage']);

  // Business Logo
  Route::post('/businesses/{business}/logo', [UploadController::class, 'uploadBusinessLogo']);

  // Business Certificate
  Route::post('/businesses/{business}/certificate', [UploadController::class, 'uploadBusinessCertificate']);
  Route::delete('/businesses/{business}/certificate', [UploadController::class, 'deleteBusinessCertificate']);
});

Route::post('/upload-temp', [UploadController::class, 'uploadTemp']);
