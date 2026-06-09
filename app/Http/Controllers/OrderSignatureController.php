<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class OrderSignatureController extends Controller
{
    public function signature(Request $request, int $id)
    {
        $order = Order::with(['student.student', 'lab.lab', 'items.product'])
            ->where('student_id', Auth::id())
            ->whereIn('status', ['estimation_provided', 'lab_negotiation'])
            ->findOrFail($id);
        $request->validate([
            'signature_paths' => 'required|array',
        ]);

        $latestNegotiation = $order->negotiations()->latest()->first();

        if (
            $latestNegotiation &&
            $latestNegotiation->suggested_by === 'lab' &&
            $latestNegotiation->status === 'pending'
        ) {
            $latestNegotiation->update(['status' => 'accepted']);
        }

        $studentName = $order->student->student->full_name ?? 'طالب';
        $studentPhone = $order->student->phone_number ?? '';
        $labName = $order->lab->lab->brand_name ?? 'مخبر';
        $labNumber = $order->lab->phone_number ?? '';

        try {
            $html = view('pdf.lab-student-contract-template', [
                'labName' => $labName,
                'labNumber' => $labNumber,
                'studentName' => $studentName,
                'studentPhone' => $studentPhone,
                'orderItems' => $order->items,
                'totalPrice' => $order->total_price,
                'signaturePaths' => $request->input('signature_paths', []),
            ])->render();

            $fileName = "contracts/contract_{$order->id}.pdf";
            $fullPath = storage_path('app/public/' . $fileName);

            if (! file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            Browsershot::html($html)
                ->format('A4')
                ->margins(0, 0, 0, 0)
                ->noSandbox()
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->savePdf($fullPath);

            $contractUrl = asset('storage/' . $fileName);
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            $contractUrl = null;
        }

        $order->update([
            'status' => 'confirmed',
            'contract_pdf_url' => $contractUrl,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم توقيع العقد بنجاح',
            'data' => $order->fresh(),
        ]);
    }
}
