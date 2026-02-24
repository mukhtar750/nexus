<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class StaffController extends Controller
{
    public function verifyTicket(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $ticket = Ticket::where('qr_code_data', $request->ticket_code)
            ->with(['user', 'event'])
            ->first();

        if (!$ticket) {
            return response()->json(['message' => 'Invalid ticket'], 404);
        }

        // Check if ticket is valid
        if ($ticket->status !== 'valid') {
            return response()->json(['message' => 'Ticket is ' . $ticket->status], 400);
        }

        // For this demo, we won't invalidate the ticket immediately so it can be scanned again.
        // In a real app, you might want to mark it as 'used'.
        // $ticket->update(['status' => 'used']);

        return response()->json([
            'message' => 'Ticket Verified Successfully',
            'attendee_name' => $ticket->user->name,
            'attendee_avatar' => $ticket->user->avatar_url,
            'event_name' => $ticket->event->title,
            'ticket_type' => 'Standard',
            'status' => $ticket->status,
        ], 200);
    }
}
