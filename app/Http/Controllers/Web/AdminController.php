<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Auth Methods
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (!Auth::user()->hasRole('admin')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have admin access.',
                ]);
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // Dashboard
    public function dashboard()
    {
        $pendingCount = User::where('status', 'pending')->count();
        return view('admin.dashboard', compact('pendingCount'));
    }

    // User Management
    public function users(Request $request)
    {
        $query = User::with('roles');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user->roles()->sync($request->roles ?? []);

        return back()->with('success', 'User roles updated successfully.');
    }

    public function approveUser(User $user)
    {
        $user->update(['status' => 'approved']);
        return back()->with('success', "User {$user->name} has been approved.");
    }

    public function rejectUser(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', "User {$user->name} has been rejected.");
    }

    // Event Management
    public function events(Request $request)
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function createEvent()
    {
        return view('admin.events.create');
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string',
            'cover_image' => 'nullable|image|max:2048',
            'requires_invitation' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('events', 'public');
            $validated['cover_image_url'] = $path;
        }

        // Remove cover_image from validated array as it's not in fillable
        unset($validated['cover_image']);

        $validated['requires_invitation'] = $request->has('requires_invitation');

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function editEvent(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string',
            'cover_image' => 'nullable|image|max:2048',
            'requires_invitation' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('events', 'public');
            $validated['cover_image_url'] = $path;
        }

        // Remove cover_image from validated array as it's not in fillable
        unset($validated['cover_image']);

        $validated['requires_invitation'] = $request->has('requires_invitation');

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function deleteEvent(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted successfully.');
    }

    // Invitation Management
    public function invitations(Event $event)
    {
        $invitations = $event->invitations()->with('user')->latest()->paginate(20);
        $users = User::orderBy('name')->get(); // For the dropdown/search
        return view('admin.events.invitations', compact('event', 'invitations', 'users'));
    }

    public function storeInvitation(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Prevent duplicates
        $existing = \App\Models\Invitation::where('event_id', $event->id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'User is already invited or registered for this event.');
        }

        \App\Models\Invitation::create([
            'event_id' => $event->id,
            'user_id' => $request->user_id,
            'status' => 'pending',
            'invited_by' => Auth::id(),
        ]);

        return back()->with('success', 'User invited successfully.');
    }

    public function deleteInvitation(\App\Models\Invitation $invitation)
    {
        $invitation->delete();
        return back()->with('success', 'Invitation removed successfully.');
    }
}
