<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
  public function index()
  {
    $totalUsers = User::count();
    $totalBusinesses = BusinessProfile::count();
    $totalProducts = Product::count();
    $totalOrders = Order::count();

    $recentUsers = User::with('role')
      ->latest()
      ->take(5)
      ->get()
      ->map(fn($user) => [
        'id' => $user->id,
        'email' => $user->email,
        'role' => $user->role?->code,
        'createdAt' => $user->created_at->diffForHumans(),
      ]);

    $recentOrders = Order::with(['user', 'status'])
      ->latest()
      ->take(5)
      ->get()
      ->map(fn($order) => [
        'id' => $order->id,
        'code' => $order->code,
        'user' => $order->user?->email,
        'total' => $order->total_price,
        'status' => $order->status?->code,
        'createdAt' => $order->created_at->diffForHumans(),
      ]);

    // Monthly user registrations for the chart (last 6 months)
    $monthlyRegistrations = collect(range(5, 0))->map(function ($monthsAgo) {
      $date = now()->subMonths($monthsAgo);
      return [
        'month' => $date->format('M'),
        'count' => User::whereYear('created_at', $date->year)
          ->whereMonth('created_at', $date->month)
          ->count(),
      ];
    });

    return Inertia::render('admin/dashboard', [
      'stats' => [
        'totalUsers' => $totalUsers,
        'totalBusinesses' => $totalBusinesses,
        'totalProducts' => $totalProducts,
        'totalOrders' => $totalOrders,
      ],
      'recentUsers' => $recentUsers,
      'recentOrders' => $recentOrders,
      'monthlyRegistrations' => $monthlyRegistrations,
    ]);
  }
}
