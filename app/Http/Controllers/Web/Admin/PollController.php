<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    public function index(Event $event)
    {
        $polls = $event->polls()->with('options')->latest()->paginate(10);
        return view('admin.events.polls.index', compact('event', 'polls'));
    }

    public function create(Event $event)
    {
        return view('admin.events.polls.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $event) {
            $poll = $event->polls()->create([
                'question' => $request->question,
                'is_active' => $request->has('is_active'),
            ]);

            foreach ($request->options as $optionText) {
                if (!empty($optionText)) {
                    $poll->options()->create(['option_text' => $optionText]);
                }
            }
        });

        return redirect()->route('admin.events.polls.index', $event->id)
            ->with('success', 'Poll created successfully.');
    }

    public function edit(Event $event, Poll $poll)
    {
        return view('admin.events.polls.edit', compact('event', 'poll'));
    }

    public function update(Request $request, Event $event, Poll $poll)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'is_active' => 'boolean', // Checkbox sends 1 or null usually, handled below
        ]);

        $poll->update([
            'question' => $request->question,
            'is_active' => $request->has('is_active'),
        ]);
        
        // Note: Editing options is complex if votes exist. 
        // For simplicity, we might only allow editing the question text and status
        // or require deleting/recreating for options if no votes.
        // Here we just update the question and status.

        return redirect()->route('admin.events.polls.index', $event->id)
            ->with('success', 'Poll updated successfully.');
    }

    public function destroy(Event $event, Poll $poll)
    {
        $poll->delete();
        return back()->with('success', 'Poll deleted successfully.');
    }
}
