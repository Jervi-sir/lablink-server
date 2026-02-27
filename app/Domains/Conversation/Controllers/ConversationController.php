<?php

namespace App\Domains\Conversation\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
  /**
   * Display a listing of the user's conversations.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    $userId = $request->user()->id;

    $query = Conversation::where('user1_id', $userId)
      ->orWhere('user2_id', $userId)
      ->with(['user1.studentProfile', 'user2.studentProfile', 'user1.businessProfile', 'user2.businessProfile', 'messages' => function ($q) {
        $q->latest()->limit(1);
      }]);

    if ($request->has('q')) {
      $q = $request->q;
      $query->where(function ($query) use ($userId, $q) {
        $query->whereHas('user1', function ($query) use ($userId, $q) {
          $query->where('id', '!=', $userId)
            ->where(function ($query) use ($q) {
              $query->whereHas('studentProfile', function ($query) use ($q) {
                $query->where('fullname', 'like', "%{$q}%");
              })
                ->orWhereHas('businessProfile', function ($query) use ($q) {
                  $query->where('name', 'like', "%{$q}%");
                });
            });
        })->orWhereHas('user2', function ($query) use ($userId, $q) {
          $query->where('id', '!=', $userId)
            ->where(function ($query) use ($q) {
              $query->whereHas('studentProfile', function ($query) use ($q) {
                $query->where('fullname', 'like', "%{$q}%");
              })
                ->orWhereHas('businessProfile', function ($query) use ($q) {
                  $query->where('name', 'like', "%{$q}%");
                });
            });
        });
      });
    }

    $paginated = $query->latest('updated_at')
      ->simplePaginate(15);

    return response()->json([
      'data' => $paginated->items(),
      'next_page' => $paginated->hasMorePages() ? $paginated->currentPage() + 1 : null,
    ]);
  }

  /**
   * Store a newly created conversation or get existing.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'target_user_id' => ['required', 'exists:users,id', 'different:' . $request->user()->id],
    ]);

    $user1Id = $request->user()->id;
    $user2Id = $validated['target_user_id'];

    $conversation = Conversation::where(function ($query) use ($user1Id, $user2Id) {
      $query->where('user1_id', $user1Id)->where('user2_id', $user2Id);
    })->orWhere(function ($query) use ($user1Id, $user2Id) {
      $query->where('user1_id', $user2Id)->where('user2_id', $user1Id);
    })->first();

    if (!$conversation) {
      $conversation = Conversation::create([
        'user1_id' => $user1Id,
        'user2_id' => $user2Id,
      ]);
    }

    return response()->json([
      'message' => 'Conversation retrieved or created',
      'data' => $conversation
    ]);
  }

  /**
   * Display the specified conversation and its messages.
   *
   * @param Request $request
   * @param Conversation $conversation
   * @return JsonResponse
   */
  public function show(Request $request, Conversation $conversation): JsonResponse
  {
    $userId = $request->user()->id;

    if ($conversation->user1_id !== $userId && $conversation->user2_id !== $userId) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $conversation->load(['messages.sender']);

    return response()->json([
      'data' => $conversation
    ]);
  }

  /**
   * Send a new message in the conversation.
   *
   * @param Request $request
   * @param Conversation $conversation
   * @return JsonResponse
   */
  public function sendMessage(Request $request, Conversation $conversation): JsonResponse
  {
    $userId = $request->user()->id;

    if ($conversation->user1_id !== $userId && $conversation->user2_id !== $userId) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
      'content' => ['required', 'string'],
    ]);

    $message = Message::create([
      'conversation_id' => $conversation->id,
      'sender_id' => $userId,
      'content' => $validated['content'],
    ]);

    return response()->json([
      'message' => 'Message sent successfully',
      'data' => $message
    ], 201);
  }
}
