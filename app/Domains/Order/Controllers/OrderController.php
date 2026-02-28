<?php

namespace App\Domains\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
  /**
   * Display a listing of orders.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    $query = Order::with(['status', 'wilaya', 'products.business', 'products.images']);

    if (!$request->user()->isAdmin()) {
      $query->where('user_id', $request->user()->id);
    }

    if ($request->has('status')) {
      $query->whereHas('status', function ($q) use ($request) {
        $q->where('code', $request->status);
      });
    }

    if ($request->has('q')) {
      $q = $request->q;
      $query->where(function ($query) use ($q) {
        $query->where('code', 'like', "%{$q}%")
          ->orWhereHas('products', function ($query) use ($q) {
            $query->where('name', 'like', "%{$q}%");
          });
      });
    }

    $orders = $query->latest()->simplePaginate(10);

    return response()->json([
      'data' => $orders->items(),
      'next_page' => $orders->hasMorePages() ? $orders->currentPage() + 1 : null,
    ]);
  }

  /**
   * Store a newly created order.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'shipping_address' => ['required', 'string'],
      'department' => ['nullable', 'string'],
      'phone' => ['nullable', 'string'],
      'payment_method' => ['required', 'string', 'in:bank_transfer,card,cash_at_lab'],
      'is_hazmat' => ['nullable', 'boolean'],
      'notes' => ['nullable', 'string'],
      'wilaya_id' => ['nullable', 'exists:wilayas,id'],
      'products' => ['required', 'array', 'min:1'],
      'products.*.id' => ['required', 'exists:products,id'],
      'products.*.quantity' => ['required', 'integer', 'min:1'],
    ]);

    // Gather real prices to avoid spoofing
    $productIds = collect($validated['products'])->pluck('id');
    $realProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');

    $order = DB::transaction(function () use ($request, $validated, $realProducts) {
      $subtotal = collect($validated['products'])->sum(function ($item) use ($realProducts) {
        return $realProducts[$item['id']]->price * $item['quantity'];
      });

      $isHazmat = $validated['is_hazmat'] ?? false;
      $shippingFee = $isHazmat ? 2500 : 800;
      $tax = round($subtotal * 0.19, 2);
      $totalPrice = $subtotal + $shippingFee + $tax;

      $order = Order::create([
        'user_id' => $request->user()->id,
        'wilaya_id' => $validated['wilaya_id'] ?? null,
        'shipping_address' => $validated['shipping_address'],
        'department' => $validated['department'] ?? null,
        'phone' => $validated['phone'] ?? null,
        'payment_method' => $validated['payment_method'],
        'is_hazmat' => $isHazmat,
        'notes' => $validated['notes'] ?? null,
        'total_price' => $totalPrice,
        'shipping_fee' => $shippingFee,
        'tax' => $tax,
        'order_status_id' => 1, // default to 'pending'
      ]);

      $pivotData = collect($validated['products'])->mapWithKeys(function ($item) use ($realProducts) {
        return [
          $item['id'] => [
            'quantity' => $item['quantity'],
            'price' => $realProducts[$item['id']]->price,
          ]
        ];
      })->toArray();

      $order->products()->attach($pivotData);

      return $order;
    });

    return response()->json([
      'message' => 'Order placed successfully',
      'data' => $order->load(['products.business', 'products.images', 'status', 'wilaya'])
    ], 201);
  }

  /**
   * Display the specified order.
   *
   * @param Request $request
   * @param Order $order
   * @return JsonResponse
   */
  public function show(Request $request, Order $order): JsonResponse
  {
    $user = $request->user();

    // Authorization: Buyer, Admin, or Business selling a product in this order
    $isBuyer = $order->user_id === $user->id;
    $isAdmin = $user->isAdmin();
    $isSeller = false;

    if ($user->isBusiness()) {
      $business = $user->businessProfile;
      if ($business) {
        $isSeller = $order->products()->where('business_id', $business->id)->exists();
      }
    }

    if (!$isBuyer && !$isAdmin && !$isSeller) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $order->load(['status', 'wilaya', 'products.business', 'products.images', 'user.studentProfile']);

    return response()->json([
      'data' => $order
    ]);
  }

  /**
   * Update the specified order status.
   *
   * @param Request $request
   * @param Order $order
   * @return JsonResponse
   */
  public function update(Request $request, Order $order): JsonResponse
  {
    $user = $request->user();

    // Authorization: Admin or Business selling a product in this order
    $isAdmin = $user->isAdmin();
    $isSeller = false;

    if ($user->isBusiness()) {
      $business = $user->businessProfile;
      if ($business) {
        $isSeller = $order->products()->where('business_id', $business->id)->exists();
      }
    }

    if (!$isAdmin && !$isSeller) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
      'order_status_id' => ['required', 'exists:order_statuses,id'],
    ]);

    $order->update($validated);

    return response()->json([
      'message' => 'Order status updated successfully',
      'data' => $order->load(['status', 'wilaya', 'products.business', 'products.images', 'user.studentProfile'])
    ]);
  }

  /**
   * Display a listing of orders for the authenticated business.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function businessOrders(Request $request): JsonResponse
  {
    $user = $request->user();
    $business = $user->businessProfile;

    if (!$business) {
      return response()->json(['message' => 'Business profile not found'], 404);
    }

    $status = $request->input('status');
    $search = $request->input('search');
    $perPage = $request->input('per_page', 10);

    $query = Order::whereHas('products', function ($q) use ($business) {
      $q->where('business_id', $business->id);
    })->with(['status', 'user.studentProfile', 'products' => function ($q) use ($business) {
      $q->where('business_id', $business->id)->with('images');
    }]);

    if ($status && $status !== 'All') {
      $query->whereHas('status', function ($q) use ($status) {
        $q->where('code', strtolower($status));
      });
    }

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('code', 'like', "%{$search}%")
          ->orWhereHas('user.studentProfile', function ($sq) use ($search) {
            $sq->where('fullname', 'like', "%{$search}%");
          });
      });
    }

    $orders = $query->latest()->paginate($perPage);

    // Get counts for dashboard
    $counts = [
      'pending' => Order::whereHas('products', function ($q) use ($business) {
        $q->where('business_id', $business->id);
      })->whereHas('status', function ($q) {
        $q->where('code', 'pending');
      })->count(),
      'processing' => Order::whereHas('products', function ($q) use ($business) {
        $q->where('business_id', $business->id);
      })->whereHas('status', function ($q) {
        $q->where('code', 'processing');
      })->count(),
      'ready' => Order::whereHas('products', function ($q) use ($business) {
        $q->where('business_id', $business->id);
      })->whereHas('status', function ($q) {
        $q->where('code', 'ready');
      })->count(),
      'completed' => Order::whereHas('products', function ($q) use ($business) {
        $q->where('business_id', $business->id);
      })->whereHas('status', function ($q) {
        $q->where('code', 'done');
      })->count(),
    ];

    return response()->json([
      'data' => $orders->items(),
      'next_page' => $orders->hasMorePages() ? $orders->currentPage() + 1 : null,
      'counts' => $counts
    ]);
  }
}
