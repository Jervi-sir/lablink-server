<?php

namespace App\Domains\Search\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\BusinessProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
  /**
   * Search for products and laboratories.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function search(Request $request): JsonResponse
  {
    $query = $request->input('q');
    $type = $request->input('type', 'all'); // 'products', 'labs', or 'all'
    $perPage = $request->input('per_page', 20);

    if (!$query) {
      return response()->json([
        'products' => [],
        'labs' => []
      ]);
    }

    $results = [];

    if ($type === 'all' || $type === 'products') {
      $products = Product::where('name', 'ILIKE', "%{$query}%")
        ->orWhere('description', 'ILIKE', "%{$query}%")
        ->with(['business', 'category', 'images'])
        ->paginate($perPage);

      $results['products'] = [
        'data' => $products->getCollection()->map(fn($p) => $p->format()),
        'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null
      ];
    }

    if ($type === 'all' || $type === 'labs') {
      $labs = BusinessProfile::where('name', 'ILIKE', "%{$query}%")
        ->orWhere('description', 'ILIKE', "%{$query}%")
        ->paginate($perPage);

      $results['labs'] = [
        'data' => $labs->getCollection()->map(fn($l) => $l->format()),
        'next_page' => $labs->hasMorePages() ? $labs->currentPage() + 1 : null
      ];
    }

    return response()->json($results);
  }
}
