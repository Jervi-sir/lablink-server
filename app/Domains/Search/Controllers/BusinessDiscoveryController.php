<?php

namespace App\Domains\Search\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Models\BusinessProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessDiscoveryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 12);
        $query = trim((string) $request->input('q', ''));
        $businessType = $request->input('business_type', 'all');
        $wilayaId = $request->input('wilaya_id');
        $sortBy = $request->input('sort_by', 'featured');

        $businessesQuery = BusinessProfile::query()
            ->with(['businessCategory', 'laboratoryCategory', 'wilaya', 'contacts'])
            ->whereNotNull('name');

        if ($query !== '') {
            $businessesQuery->where(function (Builder $builder) use ($query) {
                $builder
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('address', 'like', "%{$query}%")
                    ->orWhere('specializations', 'like', "%{$query}%");
            });
        }

        if ($businessType === BusinessCategory::CODE_LAB || $businessType === BusinessCategory::CODE_WHOLESALE) {
            $businessesQuery->whereHas('businessCategory', function (Builder $categoryQuery) use ($businessType) {
                $categoryQuery->where('code', $businessType);
            });
        }

        if ($wilayaId !== null && $wilayaId !== '') {
            $businessesQuery->where('wilaya_id', (int) $wilayaId);
        }

        switch ($sortBy) {
            case 'name':
                $businessesQuery->orderBy('name');
                break;
            case 'recent':
                $businessesQuery->latest();
                break;
            default:
                $businessesQuery->orderByDesc('is_featured')->latest();
                break;
        }

        $businesses = $businessesQuery->paginate($perPage);
        $user = $request->user('sanctum');

        return response()->json([
            'data' => $businesses->getCollection()->map(fn (BusinessProfile $business) => $business->format($user)),
            'next_page' => $businesses->hasMorePages() ? $businesses->currentPage() + 1 : null,
        ]);
    }
}
