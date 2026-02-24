<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\Ticket;
use App\Http\Resources\InvitationResource;

class InvitationController extends Controller
{
    /**
     * Display a listing of the user's invitations.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $invitations = Invitation::with('event')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return InvitationResource::collection($invitations);
    }

    /**
     * Respond to an invitation (accept/decline).
     */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);

        $user = $request->user();
        $invitation = Invitation::where('user_id', $user->id)->findOrFail($id);

        if ($invitation->status !== 'pending') {
            return response()->json(['message' => 'Invitation already responded to'], 400);
        }

        $status = $request->status;
        $invitation->update(['status' => $status]);

        if ($status === 'accepted') {
            // Check if user already has a ticket for this event
            $existingTicket = Ticket::where('user_id', $user->id)
                ->where('event_id', $invitation->event_id)
                ->first();

            if (!$existingTicket) {
                // Create ticket automatically
                Ticket::create([
                    'user_id' => $user->id,
                    'event_id' => $invitation->event_id,
                    'qr_code_data' => 'TICKET-' . $user->id . '-' . $invitation->event_id . '-' . time(),
                    'status' => 'valid',
                ]);
            }
        }

        return response()->json([
            'message' => 'Invitation ' . $status,
            'invitation' => new InvitationResource($invitation->load('event')),
        ]);
    }
}
