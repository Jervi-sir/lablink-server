<?php

namespace App\Http\Controllers\Api\Lab;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    /**
     * Get statistics for the authenticated lab.
     */
    public function index()
    {
        $userId = Auth::id();

        // Order statistics
        $totalOrders = Order::where('lab_id', $userId)->count();
        
        $pendingOrders = Order::where('lab_id', $userId)
            ->whereIn('status', ['request_estimation'])
            ->count();
            
        $activeOrders = Order::where('lab_id', $userId)
            ->whereIn('status', ['estimation_provided', 'confirmed'])
            ->count();

        $completedOrders = Order::where('lab_id', $userId)
            ->where('status', 'completed')
            ->count();

        $rejectedOrders = Order::where('lab_id', $userId)
            ->where('status', 'rejected')
            ->count();

        $totalRevenue = Order::where('lab_id', $userId)
            ->where('status', 'completed')
            ->sum('total_price');

        // Product/Service statistics
        $totalProducts = Product::where('user_id', $userId)
            ->where('type', 'equipment')
            ->count();

        $totalServices = Product::where('user_id', $userId)
            ->where('type', 'service')
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'orders' => [
                    'total' => $totalOrders,
                    'pending' => $pendingOrders,
                    'active' => $activeOrders,
                    'completed' => $completedOrders,
                    'rejected' => $rejectedOrders,
                ],
                'revenue' => [
                    'total' => (float) $totalRevenue,
                ],
                'inventory' => [
                    'products' => $totalProducts,
                    'services' => $totalServices,
                    'total' => $totalProducts + $totalServices,
                ]
            ]
        ]);
    }
}
