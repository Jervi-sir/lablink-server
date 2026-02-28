<?php

namespace App\Domains\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  /**
   * Display a listing of the products.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    return response()->json([
      'data' => Product::with(['business', 'category', 'images'])->get()
    ]);
  }

  /**
   * Store a newly created product in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse
  {
    $user = $request->user();
    $business = $user->businessProfile;

    if (!$business) {
      return response()->json(['message' => 'Business profile not found'], 404);
    }

    $validated = $request->validate([
      'product_category_id' => ['nullable', 'exists:product_categories,id'],
      'name' => ['required', 'string', 'max:255'],
      'product_type' => ['nullable', 'string', 'in:product,service'],
      'offer_type' => ['nullable', 'string', 'max:255'],
      'unit' => ['nullable', 'string', 'max:50'],
      'price' => ['nullable', 'numeric', 'min:0'],
      'safety_level' => ['nullable', 'string', 'max:50'],
      'msds_path' => ['nullable', 'string'],
      'documentations' => ['nullable', 'string'],
      'stock' => ['nullable', 'integer', 'min:0'],
      'is_available' => ['nullable', 'boolean'],
      'description' => ['nullable', 'string'],
      'summary' => ['nullable', 'string'],
      'specifications' => ['nullable', 'array'],
      'sku' => ['nullable', 'string', 'max:100'],
    ]);

    $product = Product::create([
      'business_id' => $business->id,
      'product_category_id' => $validated['product_category_id'] ?? null,
      'name' => $validated['name'],
      'product_type' => $validated['product_type'] ?? 'product',
      'sku' => $validated['sku'] ?? null,
      'offer_type' => $validated['offer_type'] ?? null,
      'unit' => $validated['unit'] ?? null,
      'price' => $validated['price'] ?? 0,
      'safety_level' => $validated['safety_level'] ?? null,
      'msds_path' => $validated['msds_path'] ?? null,
      'documentations' => $validated['documentations'] ?? null,
      'stock' => $validated['stock'] ?? 0,
      'is_available' => $validated['is_available'] ?? true,
      'description' => $validated['description'] ?? null,
      'summary' => $validated['summary'] ?? null,
      'specifications' => $validated['specifications'] ?? null,
    ]);

    return response()->json([
      'message' => 'Product created successfully',
      'data' => $product
    ], 201);
  }

  /**
   * Display the specified product.
   *
   * @param Request $request
   * @param Product $product
   * @return JsonResponse
   */
  public function show(Request $request, Product $product): JsonResponse
  {
    $product->load(['business', 'category', 'images', 'reviews.user']);

    $user = $request->user('sanctum');

    return response()->json([
      'data' => $product->format($user)
    ]);
  }

  /**
   * Toggle save/unsave a product for the authenticated user.
   *
   * @param Request $request
   * @param Product $product
   * @return JsonResponse
   */
  public function toggleSave(Request $request, Product $product): JsonResponse
  {
    $user = $request->user();
    $isSaved = $user->savedProducts()->where('product_id', $product->id)->exists();

    if ($isSaved) {
      $user->savedProducts()->detach($product->id);
    } else {
      $user->savedProducts()->attach($product->id);
    }

    return response()->json([
      'isSaved' => !$isSaved,
      'message' => $isSaved ? 'Product removed from saved' : 'Product saved successfully',
    ]);
  }

  /**
   * Update the specified product in storage.
   *
   * @param Request $request
   * @param Product $product
   * @return JsonResponse
   */
  public function update(Request $request, Product $product): JsonResponse
  {
    // Example basic auth check. Normally you'd use policies.
    $user = $request->user();
    if ($product->business->user_id !== $user->id && !$user->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
      'product_category_id' => ['nullable', 'exists:product_categories,id'],
      'name' => ['sometimes', 'string', 'max:255'],
      'product_type' => ['nullable', 'string', 'in:product,service'],
      'sku' => ['nullable', 'string', 'max:100'],
      'offer_type' => ['nullable', 'string', 'max:255'],
      'unit' => ['nullable', 'string', 'max:50'],
      'price' => ['nullable', 'numeric', 'min:0'],
      'safety_level' => ['nullable', 'string', 'max:50'],
      'msds_path' => ['nullable', 'string'],
      'documentations' => ['nullable', 'string'],
      'stock' => ['nullable', 'integer', 'min:0'],
      'is_available' => ['nullable', 'boolean'],
      'description' => ['nullable', 'string'],
      'summary' => ['nullable', 'string'],
      'specifications' => ['nullable', 'array'],
    ]);

    $product->update($validated);

    return response()->json([
      'message' => 'Product updated successfully',
      'data' => $product
    ]);
  }

  /**
   * Remove the specified product from storage.
   *
   * @param Request $request
   * @param Product $product
   * @return JsonResponse
   */
  public function destroy(Request $request, Product $product): JsonResponse
  {
    $user = $request->user();
    if ($product->business->user_id !== $user->id && !$user->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $product->delete();

    return response()->json([
      'message' => 'Product deleted successfully'
    ]);
  }

  /**
   * Display a listing of trending products.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function trending(Request $request): JsonResponse
  {
    $perPage = $request->input('per_page', 10);
    $categoryId = $request->input('product_category_id');

    $query = Product::where('is_trending', true);

    if ($categoryId) {
      $query->where('product_category_id', $categoryId);
    }

    $products = $query->with(['business', 'category', 'images'])
      ->paginate($perPage);

    $user = $request->user('sanctum');

    return response()->json([
      'data' => $products->getCollection()->map(fn($product) => $product->format($user)),
      'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null,
    ]);
  }
  /**
   * Display a listing of recent products.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function recent(Request $request): JsonResponse
  {
    $perPage = $request->input('per_page', 10);

    $products = Product::where('is_available', true)
      ->with(['business', 'category', 'images'])
      ->latest()
      ->paginate($perPage);

    $user = $request->user('sanctum');

    return response()->json([
      'data' => $products->getCollection()->map(fn($product) => $product->format($user)),
      'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null,
    ]);
  }

  /**
   * Display a listing of the authenticated business's products (Inventory).
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function inventory(Request $request): JsonResponse
  {
    $user = $request->user();
    $business = $user->businessProfile;

    if (!$business) {
      return response()->json(['message' => 'Business profile not found'], 404);
    }

    $perPage = $request->input('per_page', 10);
    $search = $request->input('search');
    $status = $request->input('status'); // All, Active, Out of Stock, Draft

    $query = $business->products()->with(['category', 'images']);

    if ($search) {
      $query->where('name', 'like', "%{$search}%");
    }

    if ($status && $status !== 'All') {
      if ($status === 'Active') {
        $query->where('is_available', true)->where('stock', '>', 0);
      } elseif ($status === 'Out of Stock') {
        $query->where('stock', 0);
      } elseif ($status === 'Draft') {
        $query->where('is_available', false);
      }
    }

    $products = $query->latest()->paginate($perPage);

    return response()->json([
      'data' => $products->getCollection()->map(fn($product) => $product->format($user)),
      'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null,
    ]);
  }
}
