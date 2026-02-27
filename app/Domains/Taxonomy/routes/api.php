<?php

use App\Domains\Taxonomy\Controllers\TaxonomyController;
use Illuminate\Support\Facades\Route;

// This single route handles all taxonomy lookups. 
// Use ?types=wilayas,product_categories,etc. to get multiple at once.
Route::get('taxonomies', [TaxonomyController::class, 'index']);
