<?php

namespace App\Http\Controllers\Api\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * List the lab's own products.
     */
    public function index(Request $request)
    {
        $query = Product::where('user_id', Auth::id())
            ->with('media', 'category')
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $products = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }
}
