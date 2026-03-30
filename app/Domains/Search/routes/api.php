<?php

use App\Domains\Search\Controllers\SearchController;
use App\Domains\Search\Controllers\BusinessDiscoveryController;
use App\Domains\Search\Controllers\LaboratorySearchController;
use App\Domains\Search\Controllers\LaboratoryProductSearchController;
use App\Domains\Search\Controllers\ProductDiscoveryController;
use Illuminate\Support\Facades\Route;

Route::get('/search', [SearchController::class, 'search']);
Route::get('/search/products', [ProductDiscoveryController::class, 'index']);
Route::get('/search/businesses', [BusinessDiscoveryController::class, 'index']);
Route::get('/search/laboratory', [LaboratorySearchController::class, 'search']);
Route::get('/search/laboratory/products', [LaboratoryProductSearchController::class, 'index']);
Route::get('/search/laboratory/random', [LaboratorySearchController::class, 'randomSuppliers']);
