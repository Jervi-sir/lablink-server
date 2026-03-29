<?php

namespace App\Domains\Search\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductDiscoveryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 12);
        $query = trim((string) $request->input('q', ''));
        $productType = $request->input('product_type');
        $wilayaId = $request->input('wilaya_id');
        $categoryIds = $request->input('product_category_ids', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $safetyLevels = $request->input('safety_levels', []);
        $sortBy = $request->input('sort_by', 'recent');
        $businessCategoryCode = $request->input('business_category_code');

        if (is_string($categoryIds)) {
            $categoryIds = array_filter(explode(',', $categoryIds));
        }

        if (is_string($safetyLevels)) {
            $safetyLevels = array_filter(explode(',', $safetyLevels));
        }

        $productsQuery = Product::query()
            ->where('is_available', true)
            ->with(['business.businessCategory', 'business.wilaya', 'category', 'images', 'reviews']);

        if ($query !== '') {
            $productsQuery->where(function (Builder $builder) use ($query) {
                $builder
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('summary', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhereHas('business', function (Builder $businessQuery) use ($query) {
                        $businessQuery->where('name', 'like', "%{$query}%");
                    });
            });
        }

        if (in_array($productType, ['product', 'service'], true)) {
            $productsQuery->where('product_type', $productType);
        }

        if ($wilayaId !== null && $wilayaId !== '') {
            $productsQuery->whereHas('business', function (Builder $businessQuery) use ($wilayaId) {
                $businessQuery->where('wilaya_id', (int) $wilayaId);
            });
        }

        if (!empty($categoryIds)) {
            $productsQuery->whereIn('product_category_id', array_map('intval', $categoryIds));
        }

        if ($minPrice !== null && $minPrice !== '') {
            $productsQuery->where('price', '>=', (float) $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $productsQuery->where('price', '<=', (float) $maxPrice);
        }

        if (!empty($safetyLevels)) {
            $productsQuery->whereIn('safety_level', array_map('intval', $safetyLevels));
        }
 
        if ($businessCategoryCode) {
            $productsQuery->whereHas('business.businessCategory', function (Builder $q) use ($businessCategoryCode) {
                $q->where('code', $businessCategoryCode);
            });
        }

        switch ($sortBy) {
            case 'price_asc':
                $productsQuery->orderBy('price');
                break;
            case 'price_desc':
                $productsQuery->orderByDesc('price');
                break;
            default:
                $productsQuery->latest();
                break;
        }

        $products = $productsQuery->paginate($perPage);
        $user = $request->user('sanctum');

        return response()->json([
            'data' => $products->getCollection()->map(fn (Product $product) => $product->format($user)),
            'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null,
        ]);
    }
}
