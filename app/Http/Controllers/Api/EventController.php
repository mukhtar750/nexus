<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Http\Resources\EventResource;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // If user is guest/null, only show non-private events
        if (!$user) {
            $events = Event::where('requires_invitation', false)->get();
        } else {
            // Show public events OR private events user is invited to OR all if admin
            if ($user->hasRole('admin')) {
                $events = Event::all();
            } else {
                $events = Event::where('requires_invitation', false)
                    ->orWhereHas('invitations', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })->get();
            }
        }

        return EventResource::collection($events);
    }

    public function show($id)
    {
        return new EventResource(Event::with(['sessions', 'speakers'])->findOrFail($id));
    }

    public function register(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            \Log::error("Event registration failed: No authenticated user found.");
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        try {
            $event = Event::findOrFail($id);

            // Block direct registration for invite-only events
            if ($event->requires_invitation && !$user->hasRole('admin')) {
                // Check if user has an accepted invitation
                $invitation = \App\Models\Invitation::where('user_id', $user->id)
                    ->where('event_id', $id)
                    ->where('status', 'accepted')
                    ->first();

                if (!$invitation) {
                    return response()->json([
                        'message' => 'This event requires an invitation. Please check your invitations.'
                    ], 403);
                }
            }

            \Log::info("User {$user->id} attempting to register for event {$id}");

            // Check if already registered
            if ($user->tickets()->where('event_id', $id)->exists()) {
                \Log::warning("User {$user->id} is already registered for event {$id}");
                return response()->json(['message' => 'Already registered'], 200); // Return 200 to satisfy app if already done
            }

            // Create ticket
            $ticket = \App\Models\Ticket::create([
                'user_id' => $user->id,
                'event_id' => $id,
                'qr_code_data' => 'TICKET-' . $user->id . '-' . $id . '-' . time(),
                'status' => 'valid',
            ]);

            \Log::info("Ticket created successfully for user {$user->id} for event {$id}. Ticket ID: {$ticket->id}");

            return response()->json(['message' => 'Registered successfully', 'ticket' => $ticket], 201);
        } catch (\Exception $e) {
            \Log::error("Registration failed for user {$user->id} for event {$id}: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['message' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    public function myEvents(Request $request)
    {
        $user = $request->user();
        // Get events where user has a ticket
        $events = Event::whereHas('tickets', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return EventResource::collection($events);
    }
}
