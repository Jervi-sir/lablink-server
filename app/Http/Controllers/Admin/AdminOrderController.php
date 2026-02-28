<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminOrderController extends Controller
{
  public function index(Request $request)
  {
    $query = Order::with(['user', 'status', 'wilaya', 'products']);

    // Search
    if ($search = $request->input('search')) {
      $query->where(function ($q) use ($search) {
        $q->where('code', 'ilike', "%{$search}%")
          ->orWhereHas('user', fn($uq) => $uq->where('email', 'ilike', "%{$search}%"));
      });
    }

    // Filter by status
    if ($status = $request->input('status')) {
      $query->whereHas('status', fn($q) => $q->where('code', $status));
    }

    $orders = $query->latest()
      ->paginate(15)
      ->through(fn($order) => [
        'id' => $order->id,
        'code' => $order->code,
        'userEmail' => $order->user?->email,
        'totalPrice' => (float) $order->total_price,
        'shippingFee' => (float) $order->shipping_fee,
        'tax' => (float) $order->tax,
        'status' => $order->status?->code,
        'paymentMethod' => $order->payment_method,
        'wilaya' => $order->wilaya?->name,
        'productCount' => $order->products->count(),
        'createdAt' => $order->created_at->format('M d, Y'),
      ]);

    $statuses = OrderStatus::pluck('code')->toArray();

    return Inertia::render('admin/orders/index', [
      'orders' => $orders,
      'statuses' => $statuses,
      'filters' => [
        'search' => $request->input('search', ''),
        'status' => $request->input('status', ''),
      ],
    ]);
  }

  public function updateStatus(Request $request, Order $order)
  {
    $request->validate([
      'status' => 'required|string|exists:order_statuses,code',
    ]);

    $statusId = OrderStatus::where('code', $request->status)->first()->id;
    $order->update(['order_status_id' => $statusId]);

    return back()->with('success', 'Order status updated successfully.');
  }
}
