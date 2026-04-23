<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wilaya;
use App\Models\ProductCategory;
use App\Models\LabCategory;

class TaxonomyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $requestedTypes = $request->query('types');
        $types = $requestedTypes ? explode(',', $requestedTypes) : ['wilayas', 'product_categories', 'lab_categories'];

        $data = [];

        if (in_array('wilayas', $types)) {
            $data['wilayas'] = Wilaya::orderBy('number', 'asc')->get();
        }

        if (in_array('product_categories', $types)) {
            $data['product_categories'] = ProductCategory::all();
        }

        if (in_array('lab_categories', $types)) {
            $data['lab_categories'] = LabCategory::all();
        }

        return response()->json($data);
    }
}
