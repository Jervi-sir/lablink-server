<?php

namespace App\Domains\Collection\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
  public function savedProducts(Request $request): JsonResponse
  {
    $user = $request->user();
    $products = $user->savedProducts()->with(['business', 'category', 'images', 'reviews'])->get();

    return response()->json([
      'data' => $products->map(fn($product) => $product->format($user)),
    ]);
  }

  public function savedBusinesses(Request $request): JsonResponse
  {
    $businesses = $request->user()->savedBusinesses()->with(['businessCategory', 'laboratoryCategory', 'wilaya'])->get();

    $formatted = $businesses->map(function ($business) {
      return $this->formatBusiness($business);
    });

    return response()->json(['data' => $formatted]);
  }

  public function followedBusinesses(Request $request): JsonResponse
  {
    $businesses = $request->user()->followedBusinesses()->with(['businessCategory', 'laboratoryCategory', 'wilaya'])->get();

    $formatted = $businesses->map(function ($business) {
      return $this->formatBusiness($business);
    });

    return response()->json(['data' => $formatted]);
  }

  private function formatBusiness($business)
  {
    return [
      'id' => $business->id,
      'name' => $business->name,
      'university' => $business->wilaya ? $business->wilaya->name : 'N/A', // Using wilaya as university placeholder or check if university relation exists
      'logo' => $business->logo ? $business->logo : '🔬',
      'followers' => '1.2k', // Needs real follower count logic later
      'isNew' => $business->created_at->gt(now()->subDays(7)),
      'type' => $business->laboratoryCategory ? $business->laboratoryCategory->name : 'Laboratory',
    ];
  }
}
