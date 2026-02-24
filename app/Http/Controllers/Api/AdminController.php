<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function stats()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_events' => \App\Models\Event::count(),
            'total_tickets' => \App\Models\Ticket::count(),
            'recent_users' => User::latest()->take(5)->get(),
        ]);
    }

    /**
     * Get all users with their roles.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        return response()->json($users);
    }

    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $roleName = $request->role;
        $role = Role::where('name', $roleName)->first();

        // Check if user already has the role
        if ($user->roles()->where('name', $roleName)->exists()) {
            return response()->json(['message' => "User already has the role {$roleName}"], 409);
        }

        $user->roles()->attach($role->id);

        Log::info("Role assigned: {$roleName} to User ID {$user->id} by Admin ID {$request->user()->id}");

        return response()->json([
            'message' => "Role {$roleName} assigned successfully.",
            'user' => $user->load('roles')
        ]);
    }

    /**
     * Remove a role from a user.
     */
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $roleName = $request->role;
        $role = Role::where('name', $roleName)->first();

        if (!$user->roles()->where('name', $roleName)->exists()) {
            return response()->json(['message' => "User does not have the role {$roleName}"], 404);
        }

        $user->roles()->detach($role->id);

        Log::info("Role removed: {$roleName} from User ID {$user->id} by Admin ID {$request->user()->id}");

        return response()->json([
            'message' => "Role {$roleName} removed successfully.",
            'user' => $user->load('roles')
        ]);
    }

    /**
     * Approve a pending user registration.
     */
    public function approveUser(Request $request, User $user)
    {
        if ($user->status === 'approved') {
            return response()->json(['message' => 'User is already approved'], 400);
        }

        $user->update(['status' => 'approved']);

        Log::info("User approved: User ID {$user->id} by Admin ID {$request->user()->id}");

        return response()->json([
            'message' => 'User approved successfully',
            'user' => $user
        ]);
    }

    /**
     * Reject a pending user registration.
     */
    public function rejectUser(Request $request, User $user)
    {
        if ($user->status === 'rejected') {
            return response()->json(['message' => 'User is already rejected'], 400);
        }

        $user->update(['status' => 'rejected']);

        Log::info("User rejected: User ID {$user->id} by Admin ID {$request->user()->id}");

        return response()->json([
            'message' => 'User rejected successfully',
            'user' => $user
        ]);
    }

    /**
     * Get pending users for approval.
     */
    public function pendingUsers()
    {
        $users = User::where('status', 'pending')
            ->with('roles')
            ->latest()
            ->paginate(15);

        return response()->json($users);
    }

    /**
     * Invite a user to a specific event.
     */
    public function inviteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
        ]);

        $admin = $request->user();

        // Prevent duplicate invitations
        $existing = \App\Models\Invitation::where('user_id', $request->user_id)
            ->where('event_id', $request->event_id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'User already invited to this event'], 409);
        }

        $invitation = \App\Models\Invitation::create([
            'user_id' => $request->user_id,
            'event_id' => $request->event_id,
            'status' => 'pending',
            'invited_by' => $admin->id,
        ]);

        return response()->json([
            'message' => 'Invitation sent successfully',
            'invitation' => $invitation
        ], 201);
    }
}
