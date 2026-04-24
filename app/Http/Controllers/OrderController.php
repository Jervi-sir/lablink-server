<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'lab.lab'])
            ->where('student_id', Auth::id());

        if ($request->has('tab')) {
            if ($request->tab === 'requests') {
                $query->whereIn('status', ['request_estimation', 'estimation_provided', 'rejected']);
            } elseif ($request->tab === 'confirmed') {
                $query->whereIn('status', ['confirmed', 'completed']);
            }
        }

        $orders = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $lab = \App\Models\Lab::findOrFail($request->lab_id);

            $order = Order::create([
                'student_id' => Auth::id(),
                'lab_id' => $lab->user_id,
                'status' => 'request_estimation', // Starts as a request for estimation
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    // Price is null initially because it's an estimation request
                ]);
            }

            DB::commit();

            // Send notification to Lab
            try {
                $notificationService = app(\App\Services\NotificationService::class);
                $labUser = $order->lab;
                if ($labUser) {
                    $studentName = Auth::user()->student->full_name ?? 'طالب جديد';
                    $notificationService->sendPushNotification(
                        $labUser,
                        "طلب جديد! 🔬",
                        "لقد تلقيت طلباً جديداً من {$studentName}.",
                        ['order_id' => (string)$order->id, 'type' => 'new_order']
                    );
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send order creation notification: " . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'data' => $order->load('items.product'),
                'message' => 'تم إرسال طلب عرض السعر بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إرسال الطلب: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'lab.lab'])
            ->where('student_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    public function signature(Request $request, $id)
    {
        $order = Order::where('student_id', Auth::id())
            ->where('status', 'estimation_provided')
            ->findOrFail($id);

        $order->update([
            'status' => 'confirmed',
            'signed_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم توقيع العقد بنجاح',
            'data' => $order
        ]);
    }
}
