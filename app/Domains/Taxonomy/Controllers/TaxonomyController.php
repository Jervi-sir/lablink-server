<?php

namespace App\Domains\Taxonomy\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wilaya;
use App\Models\BusinessCategory;
use App\Models\LaboratoryCategory;
use App\Models\ProductCategory;
use App\Models\University;
use App\Models\Department;
use App\Models\OrderStatus;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaxonomyController extends Controller
{
  /**
   * Get taxonomies based on requested types.
   */
  public function index(Request $request): JsonResponse
  {
    $types = $request->input('types', '');
    $typesArray = array_filter(explode(',', $types));

    $response = [];

    // If no types specified, return nothing or maybe default set
    if (empty($typesArray)) {
      return response()->json(['message' => 'Please specify types (e.g., wilayas,product_categories)'], 400);
    }

    foreach ($typesArray as $type) {
      switch (trim($type)) {
        case 'wilayas':
          $query = Wilaya::query();
          if ($request->boolean('has_businesses')) {
            $query->whereHas('businessProfiles');
          }
          $response['wilayas'] = $query->get();
          break;

        case 'business_categories':
          $response['business_categories'] = BusinessCategory::all();
          break;

        case 'laboratory_categories':
          $response['laboratory_categories'] = LaboratoryCategory::all();
          break;

        case 'product_categories':
          $response['product_categories'] = ProductCategory::all();
          break;

        case 'universities':
          $query = University::query();
          $wilayaId = $request->input('wilaya_id');
          if ($wilayaId) {
            $query->where('wilaya_id', $wilayaId);
          }
          $response['universities'] = $query->with('wilaya')->get();
          break;

        case 'departments':
          $query = Department::query();
          $universityId = $request->input('university_id');
          if ($universityId) {
            $query->where('university_id', $universityId);
          }
          $response['departments'] = $query->with('university')->get();
          break;

        case 'order_statuses':
          $response['order_statuses'] = OrderStatus::all();
          break;

        case 'roles':
          $response['roles'] = Role::all();
          break;
      }
    }

    return response()->json($response);
  }

  /**
   * Single resource helpers if needed specifically
   */
  public function wilayas(Request $request): JsonResponse
  {
    $query = Wilaya::query();
    if ($request->boolean('has_businesses')) {
      $query->whereHas('businessProfiles');
    }
    return response()->json($query->get());
  }

  public function productCategories(): JsonResponse
  {
    return response()->json(ProductCategory::all());
  }

  public function universities(Request $request): JsonResponse
  {
    $query = University::query();
    if ($request->has('wilaya_id')) {
      $query->where('wilaya_id', $request->wilaya_id);
    }
    return response()->json($query->with('wilaya')->get());
  }
}
