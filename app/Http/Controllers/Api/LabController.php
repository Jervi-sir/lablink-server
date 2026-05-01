<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use Illuminate\Http\Request;

class LabController extends Controller
{
    /**
     * Display a listing of labs.
     */
    public function index(Request $request)
    {
        $labs = Lab::with(['user', 'wilaya', 'category'])
            ->when($request->type, function ($query) use ($request) {
                $query->whereHas('products', function ($q) use ($request) {
                    $q->where('type', $request->type)->where('is_active', true);
                });
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $labs,
        ]);
    }

    /**
     * Display the specified lab.
     */
    public function show(Lab $lab)
    {
        return response()->json([
            'status' => 'success',
            'data' => $lab->load(['user', 'wilaya', 'category']),
        ]);
    }

    /**
     * Get paginated products for a lab.
     */
    public function products(Lab $lab, Request $request)
    {
        $products = $lab->products()
            ->where('is_active', true)
            ->when($request->type, function ($query) use ($request) {
                return $query->where('type', $request->type);
            })
            ->with('media')
            ->latest()
            ->paginate(12);

        return response()->json([
            'status' => 'success',
            'data' => $products->items(),
            'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null,
        ]);
    }
}
