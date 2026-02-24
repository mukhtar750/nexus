<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function index(Event $event)
    {
        $user = Auth::user();
        if (!$user->tickets()->where('event_id', $event->id)->exists()) {
            return response()->json(['message' => 'Not registered for this event'], 403);
        }

        $polls = $event->polls()
            ->where('is_active', true)
            ->with([
                'options' => function ($query) {
                    $query->withCount('votes');
                }
            ])
            ->withCount('votes')
            ->get();

        return response()->json($polls);
    }

    public function vote(Request $request, Poll $poll)
    {
        $user = Auth::user();

        if (!$user->tickets()->where('event_id', $poll->event_id)->exists()) {
            return response()->json(['message' => 'Not registered for this event'], 403);
        }

        if (!$poll->is_active) {
            return response()->json(['message' => 'Poll is closed'], 400);
        }

        if ($poll->votes()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already voted'], 400);
        }

        $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        if (!$poll->options()->where('id', $request->poll_option_id)->exists()) {
            return response()->json(['message' => 'Invalid option'], 400);
        }

        PollVote::create([
            'poll_id' => $poll->id,
            'poll_option_id' => $request->poll_option_id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Vote recorded',
            'poll' => $poll->fresh() // Return fresh poll with updated counts
        ]);
    }
}
