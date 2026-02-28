<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\SummitEoi;
use App\Models\Summit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class EoiController extends Controller
{
    public function index(Request $request)
    {
        $query = SummitEoi::with('summit')->latest();

        if ($request->filled('summit_id')) {
            $query->where('summit_id', $request->summit_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('business_name', 'like', '%' . $request->search . '%');
            });
        }

        $eois = $query->paginate(20)->withQueryString();
        $summits = Summit::where('is_active', true)->get();

        $counts = [
            'total' => SummitEoi::count(),
            'pending' => SummitEoi::where('status', 'pending')->count(),
            'selected' => SummitEoi::where('status', 'selected')->count(),
            'rejected' => SummitEoi::where('status', 'rejected')->count(),
        ];

        return view('admin.eois.index', compact('eois', 'summits', 'counts'));
    }

    public function show(SummitEoi $eoi)
    {
        $eoi->load('summit', 'registeredUser');
        return view('admin.eois.show', compact('eoi'));
    }

    public function select(Request $request, SummitEoi $eoi)
    {
        if ($eoi->status === 'selected') {
            return back()->with('error', 'This applicant has already been selected.');
        }

        $token = Str::random(64);
        $eoi->update([
            'status' => 'selected',
            'selected_at' => now(),
            'rejection_reason' => null,
            'registration_token' => $token,
        ]);

        Log::info("EOI selected (web): ID {$eoi->id} by Admin ID " . auth()->id());

        return back()->with('success', "✅ {$eoi->full_name} has been selected. Registration token generated.");
    }

    public function reject(Request $request, SummitEoi $eoi)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        if ($eoi->status === 'rejected') {
            return back()->with('error', 'This applicant has already been rejected.');
        }

        $eoi->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'registration_token' => null,
        ]);

        Log::info("EOI rejected (web): ID {$eoi->id} by Admin ID " . auth()->id());

        return back()->with('success', "❌ {$eoi->full_name}'s application has been rejected.");
    }
}
