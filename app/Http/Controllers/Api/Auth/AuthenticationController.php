<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'بيانات الاعتماد غير صحيحة',
            ], 401);
        }

        $user->load(['student.wilaya', 'lab.wilaya', 'lab.category']);

        $profile = null;
        $type = null;

        if ($user->lab) {
            $profile = $user->lab;
            $type = 'lab';
        } elseif ($user->student) {
            $profile = $user->student;
            $type = 'student';
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'user' => $user,
                'profile' => $profile,
                'type' => $type,
                'token' => $token,
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->load(['student.wilaya', 'lab.wilaya', 'lab.category']);

        $profile = null;
        $type = null;

        if ($user->lab) {
            $profile = $user->lab;
            $type = 'lab';
        } elseif ($user->student) {
            $profile = $user->student;
            $type = 'student';
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'profile' => $profile,
                'type' => $type,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }
}
