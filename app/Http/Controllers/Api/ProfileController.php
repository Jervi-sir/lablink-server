<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function show(Request $request)
    {
        $user = Auth::user()->load(['lab.wilaya', 'lab.category', 'student']);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'brand_name' => ['required_if:lab,!=,null', 'string'],
            'full_name' => ['required_if:student,!=,null', 'string'],
            'specialty' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        if ($user->lab) {
            $user->lab->update([
                'brand_name' => $request->brand_name,
            ]);
        }

        if ($user->student) {
            $user->student->update([
                'full_name' => $request->full_name,
                'specialty' => $request->specialty,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'data' => $user->load(['lab.wilaya', 'lab.category', 'student']),
        ]);
    }
}
