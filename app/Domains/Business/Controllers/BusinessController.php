<?php

namespace App\Domains\Business\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Product;

class BusinessController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    return response()->json([
      'data' => BusinessProfile::with(['user', 'businessCategory', 'laboratoryCategory', 'wilaya', 'contacts.platform'])->get()
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'nif' => ['nullable', 'string', 'max:50'],
      'logo' => ['nullable', 'string'],
      'description' => ['nullable', 'string'],
      'certificate_url' => ['nullable', 'string'],
      'address' => ['nullable', 'string'],
      'business_category_id' => ['nullable', 'exists:business_categories,id'],
      'laboratory_category_id' => ['nullable', 'exists:laboratory_categories,id'],
      'wilaya_id' => ['nullable', 'exists:wilayas,id'],
      'contacts' => ['nullable', 'array'],
      'contacts.*.platform_id' => ['required', 'exists:platforms,id'],
      'contacts.*.content' => ['required', 'string'],
      'contacts.*.label' => ['nullable', 'string'],
    ]);

    $validated['user_id'] = $request->user()->id;

    $business = BusinessProfile::create([
      'user_id' => $validated['user_id'],
      'name' => $validated['name'],
      'nif' => $validated['nif'] ?? null,
      'logo' => $validated['logo'] ?? null,
      'description' => $validated['description'] ?? null,
      'certificate_url' => $validated['certificate_url'] ?? null,
      'address' => $validated['address'] ?? null,
      'business_category_id' => $validated['business_category_id'] ?? null,
      'laboratory_category_id' => $validated['laboratory_category_id'] ?? null,
      'wilaya_id' => $validated['wilaya_id'] ?? null,
    ]);

    if (!empty($validated['contacts'])) {
      foreach ($validated['contacts'] as $contact) {
        $business->contacts()->create($contact);
      }
    }

    return response()->json([
      'message' => 'Business profile created successfully',
      'data' => $business->load('contacts.platform')
    ], 201);
  }

  /**
   * Display the specified resource.
   *
   * @param Request $request
   * @param BusinessProfile $business
   * @return JsonResponse
   */
  public function show(Request $request, BusinessProfile $business): JsonResponse
  {
    $business->load(['user', 'businessCategory', 'laboratoryCategory', 'wilaya', 'contacts.platform']);

    $user = $request->user('sanctum');

    // Get first page of products
    $products = $business->products()
      ->where('is_available', true)
      ->with(['business', 'category', 'images', 'reviews'])
      ->latest()
      ->paginate(10);

    return response()->json([
      'data' => $business->format($user),
      'products' => $products->getCollection()->map(fn($product) => $product->format($user)),
      'products_next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null,
    ]);
  }

  /**
   * Get paginated products for a business.
   *
   * @param Request $request
   * @param BusinessProfile $business
   * @return JsonResponse
   */
  public function products(Request $request, BusinessProfile $business): JsonResponse
  {
    $perPage = $request->input('per_page', 10);

    $user = $request->user('sanctum');

    $products = $business->products()
      ->where('is_available', true)
      ->with(['business', 'category', 'images', 'reviews'])
      ->latest()
      ->paginate($perPage);

    return response()->json([
      'data' => $products->getCollection()->map(fn($product) => $product->format($user)),
      'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null,
    ]);
  }

  /**
   * Toggle follow/unfollow a business for the authenticated user.
   *
   * @param Request $request
   * @param BusinessProfile $business
   * @return JsonResponse
   */
  public function toggleFollow(Request $request, BusinessProfile $business): JsonResponse
  {
    $user = $request->user();
    $isFollowed = $user->followedBusinesses()->where('business_id', $business->id)->exists();

    if ($isFollowed) {
      $user->followedBusinesses()->detach($business->id);
    } else {
      $user->followedBusinesses()->attach($business->id);
    }

    return response()->json([
      'isFollowed' => !$isFollowed,
      'followerCount' => $business->followers()->count(),
      'message' => $isFollowed ? 'Unfollowed successfully' : 'Followed successfully',
    ]);
  }

  /**
   * Toggle save/unsave a business for the authenticated user.
   *
   * @param Request $request
   * @param BusinessProfile $business
   * @return JsonResponse
   */
  public function toggleSave(Request $request, BusinessProfile $business): JsonResponse
  {
    $user = $request->user();
    $isSaved = $user->savedBusinesses()->where('business_id', $business->id)->exists();

    if ($isSaved) {
      $user->savedBusinesses()->detach($business->id);
    } else {
      $user->savedBusinesses()->attach($business->id);
    }

    return response()->json([
      'isSaved' => !$isSaved,
      'message' => $isSaved ? 'Business removed from saved' : 'Business saved successfully',
    ]);
  }

  /**
   * Display the authenticated user's business profile.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function me(Request $request): JsonResponse
  {
    $business = BusinessProfile::where('user_id', $request->user()->id)
      ->with(['user', 'businessCategory', 'laboratoryCategory', 'wilaya', 'products', 'contacts.platform'])
      ->firstOrFail();

    return response()->json([
      'data' => $business
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param BusinessProfile $business
   * @return JsonResponse
   */
  public function update(Request $request, BusinessProfile $business): JsonResponse
  {
    if ($business->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
      'name' => ['sometimes', 'string', 'max:255'],
      'nif' => ['nullable', 'string', 'max:50'],
      'logo' => ['nullable', 'string'],
      'description' => ['nullable', 'string'],
      'certificate_url' => ['nullable', 'string'],
      'address' => ['nullable', 'string'],
      'business_category_id' => ['nullable', 'exists:business_categories,id'],
      'laboratory_category_id' => ['nullable', 'exists:laboratory_categories,id'],
      'wilaya_id' => ['nullable', 'exists:wilayas,id'],
      'operating_hours' => ['nullable', 'array'],
      'contacts' => ['nullable', 'array'],
      'contacts.*.platform_id' => ['required', 'exists:platforms,id'],
      'contacts.*.content' => ['required', 'string'],
      'contacts.*.label' => ['nullable', 'string'],
    ]);

    $business->update($validated);

    if (isset($validated['contacts'])) {
      $business->contacts()->delete();
      foreach ($validated['contacts'] as $contact) {
        $business->contacts()->create($contact);
      }
    }

    return response()->json([
      'message' => 'Business profile updated successfully',
      'data' => $business->load('contacts.platform')
    ]);
  }

  /**
   * Display a listing of featured laboratories.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function featuredLabs(Request $request): JsonResponse
  {
    $perPage = $request->input('per_page', 10);
    $random = $request->boolean('random');

    $query = BusinessProfile::whereHas('businessCategory', function ($query) {
      $query->where('code', 'lab');
    });

    if ($random) {
      $query->inRandomOrder();
    } else {
      $query->where('is_featured', true);
    }

    $labs = $query->with(['user', 'businessCategory', 'laboratoryCategory', 'wilaya', 'contacts.platform'])
      ->paginate($perPage);

    $user = $request->user('sanctum');

    return response()->json([
      'data' => $labs->getCollection()->map(fn($lab) => $lab->format($user)),
      'next_page' => $labs->hasMorePages() ? $labs->currentPage() + 1 : null,
    ]);
  }
  /**
   * Display a listing of top laboratories (not featured).
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function topLabs(Request $request): JsonResponse
  {
    $perPage = $request->input('per_page', 10);

    $labs = BusinessProfile::where('is_featured', false)
      ->whereHas('businessCategory', function ($query) {
        $query->where('code', 'lab');
      })
      ->with(['user', 'businessCategory', 'laboratoryCategory', 'wilaya', 'contacts.platform'])
      ->latest()
      ->paginate($perPage);

    $user = $request->user('sanctum');

    return response()->json([
      'data' => $labs->getCollection()->map(fn($lab) => $lab->format($user)),
      'next_page' => $labs->hasMorePages() ? $labs->currentPage() + 1 : null,
    ]);
  }
}
