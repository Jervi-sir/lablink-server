<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminBusinessController extends Controller
{
  public function index(Request $request)
  {
    $query = BusinessProfile::with(['user', 'businessCategory', 'laboratoryCategory', 'wilaya']);

    // Search
    if ($search = $request->input('search')) {
      $query->where(function ($q) use ($search) {
        $q->where('name', 'ilike', "%{$search}%")
          ->orWhere('nif', 'ilike', "%{$search}%")
          ->orWhere('address', 'ilike', "%{$search}%");
      });
    }

    $businesses = $query->latest()
      ->paginate(15)
      ->through(fn($biz) => [
        'id' => $biz->id,
        'name' => $biz->name,
        'nif' => $biz->nif,
        'logo' => $biz->logo,
        'address' => $biz->address,
        'category' => $biz->businessCategory?->code,
        'labCategory' => $biz->laboratoryCategory?->code,
        'wilaya' => $biz->wilaya?->name,
        'isFeatured' => (bool) $biz->is_featured,
        'productCount' => $biz->products()->count(),
        'followerCount' => $biz->followers()->count(),
        'ownerEmail' => $biz->user?->email,
        'createdAt' => $biz->created_at->format('M d, Y'),
      ]);

    return Inertia::render('admin/businesses/index', [
      'businesses' => $businesses,
      'filters' => [
        'search' => $request->input('search', ''),
      ],
    ]);
  }

  public function toggleFeatured(BusinessProfile $business)
  {
    $business->update(['is_featured' => ! $business->is_featured]);

    return back()->with('success', 'Business featured status updated.');
  }
}
