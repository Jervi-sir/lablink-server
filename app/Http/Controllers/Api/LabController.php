<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Product;
use Illuminate\Http\Request;

class LabController extends Controller
{
    /**
     * Display a listing of labs.
     */
    public function index(Request $request)
    {
        $labs = Lab::with(['user', 'wilaya', 'category'])
            ->whereHas('user', function ($query) {
                // Ensure the lab is active if needed
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
    public function show($id)
    {
        $lab = Lab::with(['user', 'wilaya', 'category'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $lab,
        ]);
    }

    /**
     * Get paginated products for a lab.
     */
    public function products($id, Request $request)
    {
        $lab = Lab::findOrFail($id);
        
        $products = Product::where('user_id', $lab->user_id)
            ->where('is_active', true)
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
