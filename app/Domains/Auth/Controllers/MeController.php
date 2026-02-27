<?php

namespace App\Domains\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
  /**
   * Get the authenticated user.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function __invoke(Request $request): JsonResponse
  {
    $user = $request->user();
    $authType = 'student';
    $relations = ['role'];

    if ($user->isStudent()) {
      $authType = 'student';
      $relations[] = 'studentProfile.department.university.wilaya';
    } elseif ($user->isBusiness()) {
      $authType = 'business';
      $relations[] = 'businessProfile.businessCategory';
      $relations[] = 'businessProfile.laboratoryCategory';
      $relations[] = 'businessProfile.wilaya';
    }

    return response()->json([
      'authType' => $authType,
      'user' => $user->load($relations)->format(),
    ]);
  }
}
