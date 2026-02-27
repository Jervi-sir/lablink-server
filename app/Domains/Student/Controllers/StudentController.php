<?php

namespace App\Domains\Student\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
  /**
   * Display a listing of students.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    return response()->json([
      'data' => StudentProfile::with(['user', 'department'])->get()
    ]);
  }

  /**
   * Get the current authenticated student profile.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function me(Request $request): JsonResponse
  {
    $student = StudentProfile::with(['user.role', 'department'])->where('user_id', $request->user()->id)->first();

    if (!$student) {
      return response()->json(['message' => 'Student profile not found'], 404);
    }

    return response()->json([
      'data' => $student
    ]);
  }

  /**
   * Store a newly created student profile.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'fullname' => ['required', 'string', 'max:255'],
      'student_card_id' => ['required', 'string', 'max:100', 'unique:student_profiles'],
      'department_id' => ['required', 'exists:departments,id'],
    ]);

    $student = StudentProfile::create([
      'user_id' => $request->user()->id,
      'fullname' => $validated['fullname'],
      'student_card_id' => $validated['student_card_id'],
      'department_id' => $validated['department_id'],
    ]);

    return response()->json([
      'message' => 'Student profile created successfully',
      'data' => $student
    ], 201);
  }

  /**
   * Display the specified student.
   *
   * @param StudentProfile $student
   * @return JsonResponse
   */
  public function show(StudentProfile $student): JsonResponse
  {
    $student->load(['user', 'department']);

    return response()->json([
      'data' => $student
    ]);
  }

  /**
   * Update the specified student.
   *
   * @param Request $request
   * @param StudentProfile $student
   * @return JsonResponse
   */
  public function update(Request $request, StudentProfile $student): JsonResponse
  {
    if ($student->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
      'fullname' => ['sometimes', 'string', 'max:255'],
      'student_card_id' => ['sometimes', 'string', 'max:100', 'unique:student_profiles,student_card_id,' . $student->id],
      'department_id' => ['sometimes', 'exists:departments,id'],
    ]);

    $student->update($validated);

    return response()->json([
      'message' => 'Student profile updated successfully',
      'data' => $student
    ]);
  }
  /**
   * Update the current authenticated student profile.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function updateMe(Request $request): JsonResponse
  {
    $user = $request->user();
    // Validate incoming data first
    $validated = $request->validate([
      'fullName' => ['sometimes', 'string', 'max:255'],
      'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
    ]);

    $student = StudentProfile::where('user_id', $user->id)->first();
    if (!$student) {
      // Auto-create a student profile with minimal required fields
      $student = StudentProfile::create([
        'user_id' => $user->id,
        'fullname' => $validated['fullName'] ?? '',
        'student_card_id' => null,
        'department_id' => null,
      ]);
    }

    // Validation already performed above

    if (isset($validated['fullName'])) {
      $student->update(['fullname' => $validated['fullName']]);
    }

    if (isset($validated['email'])) {
      $user->update(['email' => $validated['email']]);
    }

    return response()->json([
      'message' => 'Student profile updated successfully',
      'data' => $student->load(['user', 'department'])
    ]);
  }
}
