<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminProductController extends Controller
{
  public function index(Request $request)
  {
    $query = Product::with(['business', 'category', 'images']);

    // Search
    if ($search = $request->input('search')) {
      $query->where(function ($q) use ($search) {
        $q->where('name', 'ilike', "%{$search}%")
          ->orWhere('sku', 'ilike', "%{$search}%")
          ->orWhere('description', 'ilike', "%{$search}%");
      });
    }

    // Filter by availability
    if ($request->has('available')) {
      $query->where('is_available', $request->boolean('available'));
    }

    $products = $query->latest()
      ->paginate(15)
      ->through(fn($product) => [
        'id' => $product->id,
        'name' => $product->name,
        'sku' => $product->sku,
        'price' => (float) $product->price,
        'stock' => (int) $product->stock,
        'isAvailable' => (bool) $product->is_available,
        'isTrending' => (bool) $product->is_trending,
        'category' => $product->category?->code,
        'business' => $product->business?->name,
        'image' => $product->images->first()?->url,
        'createdAt' => $product->created_at->format('M d, Y'),
      ]);

    return Inertia::render('admin/products/index', [
      'products' => $products,
      'filters' => [
        'search' => $request->input('search', ''),
        'available' => $request->input('available', ''),
      ],
    ]);
  }

  public function toggleTrending(Product $product)
  {
    $product->update(['is_trending' => ! $product->is_trending]);

    return back()->with('success', 'Product trending status updated.');
  }

  public function toggleAvailability(Product $product)
  {
    $product->update(['is_available' => ! $product->is_available]);

    return back()->with('success', 'Product availability status updated.');
  }
}
