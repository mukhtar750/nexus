<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\SpeakerInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InvitationConfirmationController extends Controller
{
    // ─── Token Validation ────────────────────────────────────────────────────

    /**
     * Validate a token and return its type + basic details.
     * GET /api/invitations/validate-token?token=xxx
     */
    public function validateToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        $token = $request->token;

        // Check delegate (invitation)
        $invitation = Invitation::where('token', $token)
            ->where('invite_type', 'delegate')
            ->first();

        if ($invitation) {
            if ($invitation->status === 'confirmed' || $invitation->user_id !== null) {
                return response()->json(['message' => 'This invitation has already been used.'], 409);
            }
            return response()->json([
                'type' => 'delegate',
                'full_name' => $invitation->full_name,
                'email' => $invitation->email,
                'summit_id' => $invitation->summit_id,
            ]);
        }

        // Check speaker
        $speaker = SpeakerInvitation::where('token', $token)->first();

        if ($speaker) {
            if ($speaker->status === 'confirmed') {
                return response()->json(['message' => 'This invitation has already been used.'], 409);
            }
            return response()->json([
                'type' => 'speaker',
                'full_name' => $speaker->full_name,
                'email' => $speaker->email,
                'summit_id' => $speaker->summit_id,
            ]);
        }

        return response()->json(['message' => 'Invalid or expired invitation token.'], 404);
    }

    // ─── Delegate Confirmation ────────────────────────────────────────────────

    /**
     * Submit delegate confirmation form and create account.
     * POST /api/invitations/confirm/delegate
     */
    public function confirmDelegate(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255|unique:users,email',
            'organization' => 'required|string|max:255',
            'role_title' => 'required|string|max:255',
            'state' => 'required|string|max:100',
            'preferred_location' => 'required|in:port_harcourt,lagos,kano',
            'areas_of_interest' => 'nullable|string',
            'physical_attendance' => 'required|boolean',
            'how_received_invitation' => 'required|string|max:500',
            'password' => 'required|string|min:8',
        ]);

        $invitation = Invitation::where('token', $validated['token'])
            ->where('invite_type', 'delegate')
            ->whereNull('confirmed_at')
            ->first();

        if (!$invitation) {
            return response()->json(['message' => 'Invalid or already used invitation token.'], 404);
        }

        // Create user account
        $user = User::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'user_type' => 'delegate',
            'status' => 'approved', // Delegates are pre-approved
            'business_name' => $validated['organization'],
        ]);

        // Mark invitation as confirmed and link user
        $invitation->update([
            'user_id' => $user->id,
            'status' => 'accepted',
            'confirmed_at' => now(),
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'organization' => $validated['organization'],
            'role_title' => $validated['role_title'],
            'state' => $validated['state'],
            'preferred_location' => $validated['preferred_location'],
            'areas_of_interest' => $validated['areas_of_interest'],
            'physical_attendance' => $validated['physical_attendance'],
            'how_received_invitation' => $validated['how_received_invitation'],
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'message' => 'Delegate confirmation successful! Welcome to NESS 2026.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // ─── Speaker Confirmation ────────────────────────────────────────────────

    /**
     * Submit speaker confirmation form and create account.
     * POST /api/invitations/confirm/speaker
     */
    public function confirmSpeaker(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255|unique:users,email',
            'organization' => 'required|string|max:255',
            'role_title' => 'required|string|max:255',
            'state' => 'required|string|max:100',
            'preferred_location' => 'required|in:port_harcourt,lagos,kano',
            'session_type' => 'required|in:keynote,presentation,panel,chat',
            'speaking_topic' => 'required|string|max:500',
            'bio' => 'required|string',
            'physical_attendance' => 'required|boolean',
            'password' => 'required|string|min:8',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $speakerInvite = SpeakerInvitation::where('token', $validated['token'])
            ->where('status', 'pending')
            ->first();

        if (!$speakerInvite) {
            return response()->json(['message' => 'Invalid or already used invitation token.'], 404);
        }

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('speaker_photos', 'public');
        }

        // Create user account
        $user = User::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'user_type' => 'speaker',
            'status' => 'approved',
            'business_name' => $validated['organization'],
            'avatar_url' => $photoPath,
        ]);

        // Update speaker invitation
        $speakerInvite->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'organization' => $validated['organization'],
            'role_title' => $validated['role_title'],
            'state' => $validated['state'],
            'preferred_location' => $validated['preferred_location'],
            'session_type' => $validated['session_type'],
            'speaking_topic' => $validated['speaking_topic'],
            'bio' => $validated['bio'],
            'profile_photo_path' => $photoPath,
            'physical_attendance' => $validated['physical_attendance'],
            'registered_user_id' => $user->id,
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'message' => 'Speaker confirmation successful! Welcome to NESS 2026.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // ─── Admin: Create Delegate Invitation ───────────────────────────────────

    /**
     * Admin creates a delegate invite token.
     * POST /api/admin/invitations/create-delegate
     */
    public function createDelegateInvite(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'summit_id' => 'nullable|integer',
            'event_id' => 'nullable|integer|exists:events,id',
        ]);

        $invitation = Invitation::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'summit_id' => $validated['summit_id'] ?? null,
            'event_id' => $validated['event_id'] ?? null,
            'invite_type' => 'delegate',
            'status' => 'pending',
            'invited_by' => $request->user()->id,
            'token' => Str::random(64),
        ]);

        return response()->json([
            'message' => 'Delegate invitation created.',
            'invitation' => $invitation,
            'token' => $invitation->token,
        ], 201);
    }

    // ─── Admin: Create Speaker Invitation ─────────────────────────────────────

    /**
     * Admin creates a speaker invite token.
     * POST /api/admin/invitations/create-speaker
     */
    public function createSpeakerInvite(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'summit_id' => 'nullable|integer',
        ]);

        $invite = SpeakerInvitation::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'summit_id' => $validated['summit_id'] ?? null,
            'token' => Str::random(64),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Speaker invitation created.',
            'invite' => $invite,
            'token' => $invite->token,
        ], 201);
    }
}
