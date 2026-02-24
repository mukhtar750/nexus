<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    public function index(Event $event)
    {
        $speakers = $event->speakers()->latest()->paginate(10);
        return view('admin.events.speakers.index', compact('event', 'speakers'));
    }

    public function create(Event $event)
    {
        return view('admin.events.speakers.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
            'schedule_time' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('speakers', 'public');
            $validated['avatar_url'] = Storage::url($path);
        }

        unset($validated['avatar']);

        $event->speakers()->create($validated);

        return redirect()->route('admin.events.speakers.index', $event->id)
            ->with('success', 'Speaker added successfully.');
    }

    public function edit(Event $event, Speaker $speaker)
    {
        return view('admin.events.speakers.edit', compact('event', 'speaker'));
    }

    public function update(Request $request, Event $event, Speaker $speaker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
            'schedule_time' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('speakers', 'public');
            $validated['avatar_url'] = Storage::url($path);
        }

        unset($validated['avatar']);

        $speaker->update($validated);

        return redirect()->route('admin.events.speakers.index', $event->id)
            ->with('success', 'Speaker updated successfully.');
    }

    public function destroy(Event $event, Speaker $speaker)
    {
        $speaker->delete();
        return back()->with('success', 'Speaker deleted successfully.');
    }
}
