<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentAuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone_number',
            'password' => 'required|string|min:8',
            'state' => 'required|exists:wilayas,id',
            'university_id' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'phone_number' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'wilaya_id' => $request->state,
                'full_name' => $request->name,
                'university_registry_number' => $request->university_id,
            ]);

            $token = $user->createToken('student_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'تم التسجيل بنجاح',
                'data' => [
                    'user' => $user,
                    'student' => $student->load('wilaya'),
                    'token' => $token,
                ],
            ]);
        });
    }
}
