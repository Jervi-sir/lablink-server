<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminUserController extends Controller
{
  public function index(Request $request)
  {
    $query = User::with('role');

    // Search
    if ($search = $request->input('search')) {
      $query->where(function ($q) use ($search) {
        $q->where('email', 'ilike', "%{$search}%")
          ->orWhere('phone_number', 'ilike', "%{$search}%");
      });
    }

    // Filter by role
    if ($role = $request->input('role')) {
      $query->whereHas('role', fn($q) => $q->where('code', $role));
    }

    $users = $query->latest()
      ->paginate(15)
      ->through(fn($user) => [
        'id' => $user->id,
        'email' => $user->email,
        'phoneNumber' => $user->phone_number,
        'avatar' => $user->avatar,
        'role' => $user->role?->code,
        'isVerified' => (bool) $user->is_verified,
        'createdAt' => $user->created_at->format('M d, Y'),
      ]);

    return Inertia::render('admin/users/index', [
      'users' => $users,
      'filters' => [
        'search' => $request->input('search', ''),
        'role' => $request->input('role', ''),
      ],
    ]);
  }

  public function show(User $user)
  {
    $user->load(['role', 'studentProfile', 'businessProfile', 'orders.status']);

    return Inertia::render('admin/users/show', [
      'user' => [
        'id' => $user->id,
        'email' => $user->email,
        'phoneNumber' => $user->phone_number,
        'avatar' => $user->avatar,
        'role' => $user->role?->code,
        'isVerified' => (bool) $user->is_verified,
        'createdAt' => $user->created_at->format('M d, Y H:i'),
        'updatedAt' => $user->updated_at->format('M d, Y H:i'),
        'studentProfile' => $user->studentProfile ? [
          'firstName' => $user->studentProfile->first_name,
          'lastName' => $user->studentProfile->last_name,
        ] : null,
        'businessProfile' => $user->businessProfile ? [
          'name' => $user->businessProfile->name,
          'nif' => $user->businessProfile->nif,
        ] : null,
        'orderCount' => $user->orders->count(),
      ],
    ]);
  }

  public function destroy(User $user)
  {
    $user->delete();

    return redirect()->route('admin.users.index')
      ->with('success', 'User deleted successfully.');
  }

  public function toggleVerification(User $user)
  {
    $user->update(['is_verified' => ! $user->is_verified]);

    return back()->with('success', 'User verification status updated.');
  }
}
