<?php

namespace App\Domains\Stat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatController extends Controller
{
  /**
   * Get student statistics.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    $user = $request->user();

    if ($user->isBusiness()) {
      $businessProfile = $user->businessProfile;
      $businessId = $businessProfile->id;

      $activeOrdersCount = \App\Models\Order::whereHas('products', function ($q) use ($businessId) {
        $q->where('business_id', $businessId);
      })->count();

      $earnings = \Illuminate\Support\Facades\DB::table('order_products')
        ->join('products', 'order_products.product_id', '=', 'products.id')
        ->where('products.business_id', $businessId)
        ->sum(\Illuminate\Support\Facades\DB::raw('order_products.price * order_products.quantity'));

      return response()->json([
        'active_orders' => $activeOrdersCount,
        'products' => $businessProfile->products()->count(),
        'followers' => $businessProfile->followers()->count(),
        'earnings' => $earnings,
      ]);
    }

    return response()->json([
      'orders' => \App\Models\Order::where('user_id', $user->id)->count(),
      'saved_products' => $user->savedProducts()->count(),
      'saved_laboratories' => $user->savedBusinesses()->count(),
      'followed_facilities' => $user->followedBusinesses()->count(),
    ]);
  }
}
