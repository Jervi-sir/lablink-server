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
            'images' => 'nullable|array',
        ]);

        $product = Product::create([
            ...collect($validated)->except(['media_ids', 'images'])->toArray(),
            'images' => $request->images,
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

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with(['media', 'category', 'user.lab'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

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
            'images' => 'nullable|array',
        ]);

        $product->update([
            ...collect($validated)->except(['media_ids', 'images'])->toArray(),
            'images' => $request->images,
            'price' => $request->price ?? $product->price,
        ]);

        if (!empty($validated['media_ids'])) {
            \App\Models\Media::whereIn('id', $validated['media_ids'])->update([
                'mediable_id' => $product->id,
                'mediable_type' => Product::class,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم التحديث بنجاح',
            'data' => $product,
        ]);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف المنتج بنجاح',
        ]);
    }
}
