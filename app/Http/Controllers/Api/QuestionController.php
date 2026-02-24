<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index(Request $request, Event $event)
    {
        $user = Auth::user();
        if (!$user->tickets()->where('event_id', $event->id)->exists()) {
             return response()->json(['message' => 'Not registered for this event'], 403);
        }

        $query = $event->questions()
            ->with('user')
            ->where('is_approved', true);

        if ($request->sort === 'popular') {
            $query->withCount('upvotes')->orderBy('upvotes_count', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return response()->json($query->get());
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        if (!$user->tickets()->where('event_id', $event->id)->exists()) {
             return response()->json(['message' => 'Not registered for this event'], 403);
        }

        $question = $event->questions()->create([
            'user_id' => $user->id,
            'content' => $request->content,
            'is_approved' => true, // Auto-approve for now
        ]);

        return response()->json($question->load('user'), 201);
    }

    public function upvote(Question $question)
    {
        $user = Auth::user();
        // Check if user is registered for the event associated with the question
        if (!$user->tickets()->where('event_id', $question->event_id)->exists()) {
             return response()->json(['message' => 'Not registered for this event'], 403);
        }

        $existingUpvote = $question->upvotes()->where('user_id', $user->id)->first();

        if ($existingUpvote) {
            $existingUpvote->delete();
            return response()->json(['message' => 'Upvote removed']);
        } else {
            $question->upvotes()->create(['user_id' => $user->id]);
            return response()->json(['message' => 'Upvoted']);
        }
    }
}
