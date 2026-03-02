<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\SpeakerInvitation;
use App\Models\Summit;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMailable;

class InvitationController extends Controller
{
    public function index()
    {
        $delegateInvitations = Invitation::with('summit')->whereNotNull('token')->latest()->paginate(10, ['*'], 'delegates');
        $speakerInvitations = SpeakerInvitation::with('summit')->latest()->paginate(10, ['*'], 'speakers');

        return view('admin.invitations.index', compact('delegateInvitations', 'speakerInvitations'));
    }

    public function create()
    {
        $summits = Summit::where('is_active', true)->get();
        return view('admin.invitations.create', compact('summits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'summit_id' => 'required|exists:summits,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'type' => 'required|in:delegate,speaker',
        ]);

        $token = Str::random(32);

        if ($validated['type'] === 'delegate') {
            Invitation::create([
                'summit_id' => $validated['summit_id'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'token' => $token,
                'invite_type' => 'delegate',
                'status' => 'pending',
            ]);
        } else {
            SpeakerInvitation::create([
                'summit_id' => $validated['summit_id'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'token' => $token,
                'status' => 'pending',
            ]);
        }

        // Attempt to send the invitation email
        try {
            Mail::to($validated['email'])->send(new InvitationMailable(
                $validated['full_name'],
                $token,
                $validated['type']
            ));

            $message = 'Invitation generated and email sent successfully!';
        } catch (\Exception $e) {
            // Log the error if needed, but don't crash the application
            $message = 'Invitation generated (Token: ' . $token . '), but the email could not be sent. Please send the token manually.';
        }

        return redirect()->route('admin.invitations.index')
            ->with('success', $message);

    }

    public function destroy($type, $id)
    {
        if ($type === 'delegate') {
            Invitation::findOrFail($id)->delete();
        } else {
            SpeakerInvitation::findOrFail($id)->delete();
        }

        return back()->with('success', 'Invitation revoked successfully.');
    }
}
