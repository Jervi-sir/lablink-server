<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\LabCategory;
use App\Models\User;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LabAuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'labName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone_number',
            'password' => 'required|string|min:8',
            'state' => 'required|exists:wilayas,id',
            'specialty' => 'required|exists:lab_categories,id',
            'commercialRegistry' => 'nullable|string',
            'accreditationFile' => 'nullable|string',
            'equipmentListFile' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'phone_number' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'password_plainText' => $request->password,
            ]);


            $lab = Lab::create([
                'user_id' => $user->id,
                'wilaya_id' => $request->state,
                'lab_category_id' => $request->specialty,
                'brand_name' => $request->labName,
                'nif' => $request->commercialRegistry,
                'permission_path_url' => $request->accreditationFile,
                'equipments_path_url' => $request->equipmentListFile,
            ]);

            $token = $user->createToken('lab_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'تم تسجيل المخبر بنجاح',
                'data' => [
                    'user' => $user,
                    'lab' => $lab->load(['wilaya', 'category']),
                    'token' => $token,
                ],
            ]);
        });
    }
}
