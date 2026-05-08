<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
                $query->whereIn('status', ['estimation_provided', 'student_negotiation', 'lab_negotiation', 'confirmed', 'completed', 'rejected']);
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
        $order = Order::with(['items.product', 'student.student', 'negotiations'])
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
            'status' => 'required|in:estimation_provided,rejected,completed',
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

    public function negotiate(Request $request, $id)
    {
        $request->validate([
            'action' => ['nullable', Rule::in(['accept', 'counter', 'reject'])],
            'suggested_price' => 'nullable|numeric|min:0',
        ]);

        $order = Order::with('negotiations')->where('lab_id', Auth::id())
            ->findOrFail($id);

        if ($order->status !== 'student_negotiation') {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يمكن الرد على هذا التفاوض حالياً',
            ], 422);
        }

        $latestNegotiation = $order->negotiations->sortByDesc('created_at')->first();
        if (!$latestNegotiation || $latestNegotiation->suggested_by !== 'student' || $latestNegotiation->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يوجد اقتراح طالب بانتظار رد المخبر',
            ], 422);
        }

        $action = $request->input('action', 'counter');

        if ($action === 'reject') {
            $latestNegotiation->update(['status' => 'rejected']);
            $order->update(['status' => 'rejected']);

            return response()->json([
                'status' => 'success',
                'message' => 'تم رفض اقتراح الطالب',
                'data' => $order->fresh(['items.product', 'student.student', 'negotiations']),
            ]);
        }

        if ($action === 'accept') {
            $latestNegotiation->update(['status' => 'accepted']);
            $order->update([
                'status' => 'lab_negotiation',
                'total_price' => $latestNegotiation->suggested_price,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم قبول سعر الطالب وبانتظار تأكيده النهائي',
                'data' => $order->fresh(['items.product', 'student.student', 'negotiations']),
            ]);
        }

        $request->validate([
            'suggested_price' => 'required|numeric|min:0',
        ]);

        if ($order->negotiations->where('suggested_by', 'lab')->count() >= 3) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يمكنك إضافة اقتراحات أخرى'
            ], 403);
        }

        $latestNegotiation->update(['status' => 'rejected']);

        $order->negotiations()->create([
            'suggested_by' => 'lab',
            'suggested_price' => $request->suggested_price,
            'status' => 'pending'
        ]);

        $order->update([
            'status' => 'lab_negotiation',
            'total_price' => $request->suggested_price
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم إرسال الاقتراح بنجاح',
            'data' => $order->load('negotiations'),
        ]);
    }

    public function markAsRead($id)
    {
        $order = Order::where('lab_id', Auth::id())->findOrFail($id);
        $order->update(['lab_last_viewed_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديد الطلب كمقروء'
        ]);
    }
}
