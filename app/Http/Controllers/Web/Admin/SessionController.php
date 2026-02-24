<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Event $event)
    {
        $sessions = $event->sessions()->orderBy('start_time')->paginate(10);
        return view('admin.events.sessions.index', compact('event', 'sessions'));
    }

    public function create(Event $event)
    {
        return view('admin.events.sessions.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
        ]);

        $event->sessions()->create($validated);

        return redirect()->route('admin.events.sessions.index', $event->id)
            ->with('success', 'Session added successfully.');
    }

    public function edit(Event $event, EventSession $session)
    {
        return view('admin.events.sessions.edit', compact('event', 'session'));
    }

    public function update(Request $request, Event $event, EventSession $session)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
        ]);

        $session->update($validated);

        return redirect()->route('admin.events.sessions.index', $event->id)
            ->with('success', 'Session updated successfully.');
    }

    public function destroy(Event $event, EventSession $session)
    {
        $session->delete();
        return back()->with('success', 'Session deleted successfully.');
    }
}
