<?php

namespace App\Domains\Search\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\BusinessProfile;
use App\Models\BusinessCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaboratorySearchController extends Controller
{
  /**
   * Search for products and suppliers specifically for laboratories.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function search(Request $request): JsonResponse
  {
    $query = $request->input('q');
    $perPage = $request->input('per_page', 20);

    // If no query, return empty for now
    if (!$query) {
      return response()->json([
        'products' => [],
        'suppliers' => []
      ]);
    }

    $results = [];

    // Search for products from suppliers (production of suppliers)
    $products = Product::whereHas('business', function ($q) {
      $q->whereHas('businessCategory', function ($q2) {
        $q2->where('code', BusinessCategory::CODE_WHOLESALE);
      });
    })
      ->where(function ($q) use ($query) {
        $q->where('name', 'ILIKE', "%{$query}%")
          ->orWhere('description', 'ILIKE', "%{$query}%");
      })
      ->with(['business', 'category', 'images'])
      ->paginate($perPage);

    $user = $request->user('sanctum');

    $results['products'] = [
      'data' => $products->getCollection()->map(fn($p) => $p->format($user)),
      'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null
    ];

    // Search for suppliers (businesses with category 'supplier')
    $suppliers = BusinessProfile::whereHas('businessCategory', function ($q) {
      $q->where('code', BusinessCategory::CODE_WHOLESALE);
    })
      ->where(function ($q) use ($query) {
        $q->where('name', 'ILIKE', "%{$query}%")
          ->orWhere('description', 'ILIKE', "%{$query}%");
      })
      ->paginate($perPage);

    $results['suppliers'] = [
      'data' => $suppliers->getCollection()->map(fn($l) => $l->format($user)),
      'next_page' => $suppliers->hasMorePages() ? $suppliers->currentPage() + 1 : null
    ];

    return response()->json($results);
  }

  /**
   * Get a random selection of suppliers.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function randomSuppliers(Request $request): JsonResponse
  {
    $count = $request->input('count', 10);

    $suppliers = BusinessProfile::whereHas('businessCategory', function ($q) {
      $q->where('code', BusinessCategory::CODE_WHOLESALE);
    })
      ->inRandomOrder()
      ->limit($count)
      ->get();

    return response()->json([
      'data' => $suppliers->map(fn($l) => $l->format($request->user('sanctum')))
    ]);
  }
}
