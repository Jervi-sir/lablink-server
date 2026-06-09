<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'lab.lab'])
            ->where('student_id', Auth::id());

        if ($request->has('tab')) {
            if ($request->tab === 'requests') {
                $query->whereIn('status', ['request_estimation', 'estimation_provided', 'student_negotiation', 'lab_negotiation', 'rejected']);
            } elseif ($request->tab === 'confirmed') {
                $query->whereIn('status', ['confirmed', 'completed']);
            }
        }

        $orders = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $lab = Lab::find($request->lab_id);

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
                $notificationService = app(NotificationService::class);
                $labUser = $order->lab;
                if ($labUser) {
                    $studentName = Auth::user()->student->full_name ?? 'طالب جديد';
                    $notificationService->sendPushNotification(
                        $labUser,
                        'طلب جديد! 🔬',
                        "لقد تلقيت طلباً جديداً من {$studentName}.",
                        ['order_id' => (string) $order->id, 'type' => 'new_order']
                    );
                }
            } catch (\Exception $e) {
                Log::error('Failed to send order creation notification: '.$e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'data' => $order->load('items.product'),
                'message' => 'تم إرسال طلب عرض السعر بنجاح',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إرسال الطلب: '.$e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'lab.lab', 'negotiations'])
            ->where('student_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    public function negotiate(Request $request, $id)
    {
        $request->validate([
            'action' => ['nullable', Rule::in(['counter', 'reject'])],
            'suggested_price' => 'nullable|numeric|min:0',
        ]);

        $order = Order::with('negotiations')->where('student_id', Auth::id())
            ->findOrFail($id);

        if (! in_array($order->status, ['estimation_provided', 'lab_negotiation'], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يمكن التفاوض على هذا الطلب حالياً',
            ], 422);
        }

        $action = $request->input('action', 'counter');

        if ($action === 'reject') {
            $latestNegotiation = $order->negotiations->sortByDesc('created_at')->first();
            if ($latestNegotiation && $latestNegotiation->suggested_by === 'lab' && $latestNegotiation->status === 'pending') {
                $latestNegotiation->update(['status' => 'rejected']);
            }

            $order->update(['status' => 'rejected']);

            return response()->json([
                'status' => 'success',
                'message' => 'تم رفض التسعير',
                'data' => $order->fresh(['items.product', 'lab.lab', 'negotiations']),
            ]);
        }

        $request->validate([
            'suggested_price' => 'required|numeric|min:0',
        ]);

        // Check if max 3 negotiations reached
        if ($order->negotiations->where('suggested_by', 'student')->count() >= 3) {
            return response()->json([
                'status' => 'error',
                'message' => 'لقد وصلت إلى الحد الأقصى من الاقتراحات المسموح بها',
            ], 403);
        }

        $latestNegotiation = $order->negotiations->sortByDesc('created_at')->first();
        if ($latestNegotiation && $latestNegotiation->suggested_by === 'lab' && $latestNegotiation->status === 'pending') {
            $latestNegotiation->update(['status' => 'rejected']);
        }

        $order->negotiations()->create([
            'suggested_by' => 'student',
            'suggested_price' => $request->suggested_price,
            'status' => 'pending',
        ]);

        $order->update(['status' => 'student_negotiation']);

        return response()->json([
            'status' => 'success',
            'message' => 'تم إرسال اقتراح السعر بنجاح',
            'data' => $order->load('negotiations'),
        ]);
    }

    public function signature(Request $request, $id)
    {
        $order = Order::with(['student.student', 'lab.lab', 'items.product'])
            ->where('student_id', Auth::id())
            ->whereIn('status', ['estimation_provided', 'lab_negotiation'])
            ->findOrFail($id);

        $request->validate([
            'signature_paths' => 'required|array',
        ]);

        $latestNegotiation = $order->negotiations()->latest()->first();
        if ($latestNegotiation && $latestNegotiation->suggested_by === 'lab' && $latestNegotiation->status === 'pending') {
            $latestNegotiation->update(['status' => 'accepted']);
        }

        // Get details for the PDF
        $studentName = $order->student->student->full_name ?? 'طالب';
        $studentPhone = $order->student->phone_number ?? '';
        $labName = $order->lab->lab->brand_name ?? 'مخبر';
        $labNumber = $order->lab->phone_number ?? '';
        $orderItems = $order->items;

        try {
            // Generate PDF using dompdf
            $pdf = Pdf::loadView('pdf.lab-student-contract-template', [
                'labName' => $labName,
                'labNumber' => $labNumber,
                'studentName' => $studentName,
                'studentPhone' => $studentPhone,
                'orderItems' => $orderItems,
                'totalPrice' => $order->total_price,
                'signaturePaths' => $request->input('signature_paths', []),
            ])->setOption('isRemoteEnabled', true);

            $pdfContent = $pdf->output();

            // Save PDF to public storage
            $fileName = "contracts/contract_{$order->id}.pdf";
            Storage::disk('public')->put($fileName, $pdfContent);

            $contractUrl = asset('storage/'.$fileName);
        } catch (\Exception $e) {
            Log::error('PDF generation failed: '.$e->getMessage());
            $contractUrl = null;
        }

        $order->update([
            'status' => 'confirmed',
            'contract_pdf_url' => $contractUrl,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم توقيع العقد بنجاح',
            'data' => $order,
        ]);
    }

    public function markAsRead($id)
    {
        $order = Order::where('student_id', Auth::id())->findOrFail($id);
        $order->update(['student_last_viewed_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديد الطلب كمقروء',
        ]);
    }

    public function destroy($id)
    {
        $order = Order::where('student_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'rejected') {
            return response()->json([
                'status' => 'error',
                'message' => 'يمكن حذف الطلبات المرفوضة فقط',
            ], 422);
        }

        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف الطلب بنجاح',
        ]);
    }
}
