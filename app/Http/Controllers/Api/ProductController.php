<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * List current user's products/equipment.
     */
    public function index()
    {
        $products = Product::where('user_id', Auth::id())
            ->with('media')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    /**
     * Store a newly created product/equipment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'type' => 'required|string',
            'image_url' => 'nullable|string',
            'specifications' => 'nullable|array',
            
            // Equipment specific
            'location' => 'nullable|string',
            'supervisor' => 'nullable|string',
            'working_hours' => 'nullable|string',
            'min_booking_time' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            
            // Product specific
            'price' => 'nullable|numeric',
            'stock_quantity' => 'nullable|integer',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'media_ids' => 'nullable|array',
            'media_ids.*' => 'exists:media,id',
        ]);

        $product = Product::create([
            ...collect($validated)->except('media_ids')->toArray(),
            'user_id' => Auth::id(),
            'price' => $request->price ?? 0,
        ]);

        if (!empty($validated['media_ids'])) {
            \App\Models\Media::whereIn('id', $validated['media_ids'])->update([
                'mediable_id' => $product->id,
                'mediable_type' => Product::class,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تمت الإضافة بنجاح',
            'data' => $product,
        ]);
    }
}
