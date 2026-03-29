<?php

namespace App\Domains\Estimation\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Models\BusinessProfile;
use App\Models\EstimationRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstimationRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $requests = EstimationRequest::with(['user.studentProfile.department.university.wilaya', 'business.businessCategory', 'business.laboratoryCategory', 'business.wilaya', 'items.product.images'])
            ->where('user_id', $request->user()->id)
            ->where('status', '!=', 'confirmed')
            ->latest()
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'data' => $requests->getCollection()->map(fn($item) => $item->format()),
            'next_page' => $requests->hasMorePages() ? $requests->currentPage() + 1 : null,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isStudent()) {
            return response()->json(['message' => 'Only students can submit estimation requests.'], 403);
        }

        $validated = $request->validate([
            'business_id' => ['required', 'exists:businesses,id'],
            'address' => ['nullable', 'string'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $business = BusinessProfile::with('businessCategory')->findOrFail($validated['business_id']);

        if (!$business->businessCategory || $business->businessCategory->code !== BusinessCategory::CODE_LAB) {
            return response()->json(['message' => 'Estimation requests can only be sent to laboratories.'], 422);
        }

        $productIds = collect($validated['items'])->pluck('product_id')->unique()->values();
        $products = Product::with(['business', 'images'])
            ->where('business_id', $business->id)
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        if ($products->count() !== $productIds->count()) {
            return response()->json(['message' => 'All selected products or services must belong to the same laboratory.'], 422);
        }

        $estimationRequest = DB::transaction(function () use ($business, $products, $user, $validated) {
            $estimationRequest = EstimationRequest::create([
                'user_id' => $user->id,
                'business_id' => $business->id,
                'address' => $validated['address'] ?? null,
                'department' => $validated['department'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                $product = $products[$item['product_id']];

                $estimationRequest->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'product_name' => $product->name,
                    'product_type' => $product->product_type,
                    'unit' => $product->unit,
                ]);
            }

            return $estimationRequest;
        });

        $estimationRequest->load([
            'user.studentProfile.department.university.wilaya',
            'business.businessCategory',
            'business.laboratoryCategory',
            'business.wilaya',
            'items.product.images',
        ]);

        return response()->json([
            'message' => 'Estimation request sent successfully',
            'data' => $estimationRequest->format(),
        ], 201);
    }

    public function show(Request $request, EstimationRequest $estimationRequest): JsonResponse
    {
        $user = $request->user();
        $isStudentOwner = $estimationRequest->user_id === $user->id;
        $isAdmin = $user->isAdmin();
        $isBusinessOwner = $user->isBusiness() && $user->businessProfile && $user->businessProfile->id === $estimationRequest->business_id;

        if (!$isStudentOwner && !$isAdmin && !$isBusinessOwner) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $estimationRequest->load([
            'user.studentProfile.department.university.wilaya',
            'business.businessCategory',
            'business.laboratoryCategory',
            'business.wilaya',
            'items.product.images',
        ]);

        return response()->json([
            'data' => $estimationRequest->format(),
        ]);
    }

    public function businessIndex(Request $request): JsonResponse
    {
        $business = $request->user()->businessProfile;

        if (!$business) {
            return response()->json(['message' => 'Business profile not found'], 404);
        }

        $requests = EstimationRequest::with(['user.studentProfile.department.university.wilaya', 'items.product.images'])
            ->where('business_id', $business->id)
            ->where('status', '!=', 'confirmed');
            
        if ($request->has('status')) {
            $status = $request->input('status');
            if ($status === 'Quoted') {
                 // "Done" in frontend sends Quoted
                $requests->whereIn('status', ['quoted', 'reviewed']);
            } else {
                $status = strtolower($status);
                // "Pending" is used for "Not Done"
                $requests->where('status', $status);
            }
        }

        $requests = $requests->latest()
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'data' => $requests->getCollection()->map(fn($item) => $item->format()),
            'next_page' => $requests->hasMorePages() ? $requests->currentPage() + 1 : null,
            'counts' => [
                'pending' => EstimationRequest::where('business_id', $business->id)->where('status', 'pending')->count(),
                'quoted' => EstimationRequest::where('business_id', $business->id)->where('status', 'quoted')->count(),
                'reviewed' => EstimationRequest::where('business_id', $business->id)->where('status', 'reviewed')->count(),
                'cancelled' => EstimationRequest::where('business_id', $business->id)->where('status', 'cancelled')->count(),
            ]
        ]);
    }

    public function quote(Request $request, EstimationRequest $estimationRequest): JsonResponse
    {
        $user = $request->user();
        $business = $user->businessProfile;

        if (!$business || $estimationRequest->business_id !== $business->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'exists:estimation_request_items,id'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'extra_fee' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($estimationRequest, $validated) {
            foreach ($validated['items'] as $itemData) {
                $estimationRequest->items()
                    ->where('id', $itemData['id'])
                    ->update(['price' => $itemData['unit_price']]);
            }

            $estimationRequest->update([
                'status' => 'quoted',
                'extra_fee' => $validated['extra_fee'] ?? 0,
                'quoting_notes' => $validated['notes'] ?? null,
            ]);
        });

        $estimationRequest->load(['items.product.images', 'user.studentProfile']);

        return response()->json([
            'message' => 'Quote submitted successfully',
            'data' => $estimationRequest->format(),
        ]);
    }

    public function confirm(Request $request, EstimationRequest $estimationRequest): JsonResponse
    {
        $user = $request->user();

        if ($estimationRequest->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($estimationRequest->status !== 'quoted') {
            return response()->json(['message' => 'Only quoted estimations can be confirmed.'], 422);
        }

        $order = DB::transaction(function () use ($estimationRequest, $user) {
            $subtotal = (float) $estimationRequest->items->sum(fn($item) => ((float) $item->price) * $item->quantity);
            $extraFee = (float) $estimationRequest->extra_fee;
            
            // For now, same logic as OrderController
            $shippingFee = 0; // Maybe included in extra fee? or default
            $tax = round($subtotal * 0.19, 2);
            $totalPrice = $subtotal + $extraFee + $shippingFee + $tax;

            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'shipping_address' => $estimationRequest->address ?? 'N/A',
                'department' => $estimationRequest->department,
                'phone' => $estimationRequest->phone,
                'payment_method' => 'bank_transfer', // Default for lab orders
                'total_price' => $totalPrice,
                'shipping_fee' => $shippingFee,
                'tax' => $tax,
                'order_status_id' => 1, // pending
                'notes' => $estimationRequest->notes . ($estimationRequest->quoting_notes ? "\n\nLab Notes: " . $estimationRequest->quoting_notes : ""),
            ]);

            foreach ($estimationRequest->items as $item) {
                $order->products()->attach($item->product_id, [
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            $estimationRequest->update(['status' => 'confirmed']);

            return $order;
        });

        return response()->json([
            'message' => 'Order placed successfully from estimation',
            'data' => $order->load(['products.business', 'products.images', 'status']),
        ]);
    }
}
