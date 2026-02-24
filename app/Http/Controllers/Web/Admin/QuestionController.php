<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Event $event)
    {
        $questions = $event->questions()->with('user')->latest()->paginate(20);
        return view('admin.events.questions.index', compact('event', 'questions'));
    }

    public function toggleApproval(Request $request, Event $event, Question $question)
    {
        $question->update(['is_approved' => !$question->is_approved]);
        $status = $question->is_approved ? 'approved' : 'hidden';
        return back()->with('success', "Question $status.");
    }

    public function destroy(Event $event, Question $question)
    {
        $question->delete();
        return back()->with('success', 'Question deleted successfully.');
    }
}
