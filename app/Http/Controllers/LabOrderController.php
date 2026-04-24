<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'student.student'])
            ->where('lab_id', Auth::id());

        if ($request->has('tab')) {
            if ($request->tab === 'requests') {
                $query->whereIn('status', ['request_estimation']);
            } elseif ($request->tab === 'confirmed') {
                $query->whereIn('status', ['estimation_provided', 'confirmed', 'completed', 'rejected']);
            }
        }

        $orders = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'student.student'])
            ->where('lab_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:estimation_provided,confirmed,rejected,completed',
            'total_price' => 'nullable|numeric',
            'items' => 'nullable|array',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.price' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            $order = Order::where('lab_id', Auth::id())->findOrFail($id);
            
            $updateData = ['status' => $request->status];
            if ($request->has('total_price')) {
                $updateData['total_price'] = $request->total_price;
            }
            
            $order->update($updateData);

            if ($request->has('items')) {
                foreach ($request->items as $itemData) {
                    $item = OrderItem::where('order_id', $order->id)->findOrFail($itemData['id']);
                    $item->update(['price' => $itemData['price']]);
                }
            }

            DB::commit();

            // Send notification to Student
            try {
                $notificationService = app(\App\Services\NotificationService::class);
                
                $statusAr = [
                    'estimation_provided' => 'تم تقديم عرض سعر',
                    'confirmed' => 'تم تأكيد طلبك',
                    'rejected' => 'تم رفض طلبك',
                    'completed' => 'اكتمل طلبك',
                ];

                $body = $statusAr[$request->status] ?? "تم تحديث حالة طلبك إلى {$request->status}";

                $studentUser = $order->student;
                if ($studentUser) {
                    $notificationService->sendPushNotification(
                        $studentUser,
                        "تحديث في حالة الطلب 📦",
                        $body,
                        ['order_id' => (string)$order->id, 'type' => 'order_status_change', 'status' => $request->status]
                    );
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send order status update notification: " . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث حالة الطلب بنجاح',
                'data' => $order->load('items.product')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث الطلب: ' . $e->getMessage()
            ], 500);
        }
    }
}
