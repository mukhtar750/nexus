<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Summit;
use App\Models\SummitEoi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SummitEoiController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // PUBLIC: Submit Expression of Interest
    // POST /api/summits/{summit}/eoi
    // ─────────────────────────────────────────────────────────
    public function store(Request $request, Summit $summit)
    {
        $validated = $request->validate([
            // Section A
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'business_name' => 'required|string|max:255',
            'state' => 'required|string|max:100',
            'preferred_location' => 'required|in:port_harcourt,lagos,kano',
            'how_heard' => 'required|in:nepc,bank,industry_association,word_of_mouth,other',
            // Section B
            'sector' => 'required|in:agro_processing,solid_minerals,manufacturing,services,multiple,other',
            'primary_products' => 'required|string|max:500',
            'cac_registration' => 'required|in:yes,no,in_progress',
            'nepc_registration' => 'required|in:yes,no,in_progress',
            'export_status' => 'required|in:currently_exporting,exported_before,export_ready,exploring',
            'recent_export_value' => 'required|in:above_50m,10m_to_50m,below_10m,no_export_yet',
            // Section C
            'commercial_scale' => 'required|boolean',
            'regulatory_registration' => 'required|boolean',
            'regulatory_body' => 'nullable|string|max:255',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string',
            'seminar_goals' => 'nullable|array|max:2',
            'seminar_goals.*' => 'string',
        ]);

        // Prevent duplicate EOI for same email + summit
        $existing = SummitEoi::where('email', $validated['email'])
            ->where('summit_id', $summit->id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'An expression of interest has already been submitted with this email for this summit.',
                'status' => $existing->status,
            ], 409);
        }

        $eoi = SummitEoi::create(array_merge($validated, [
            'summit_id' => $summit->id,
            'status' => 'pending',
        ]));

        Log::info("EOI submitted: ID {$eoi->id} for Summit {$summit->id} by {$eoi->email}");

        return response()->json([
            'message' => 'Your expression of interest has been submitted successfully! We will be in touch if you are selected.',
            'eoi_id' => $eoi->id,
        ], 201);
    }

    // ─────────────────────────────────────────────────────────
    // PUBLIC: Check EOI status by email + summit
    // POST /api/summits/eoi/check-status
    // ─────────────────────────────────────────────────────────
    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'summit_id' => 'required|integer|exists:summits,id',
        ]);

        $eoi = SummitEoi::where('email', $request->email)
            ->where('summit_id', $request->summit_id)
            ->first();

        if (!$eoi) {
            return response()->json([
                'message' => 'No expression of interest found for this email and summit.',
            ], 404);
        }

        $response = [
            'status' => $eoi->status,
            'full_name' => $eoi->full_name,
            'summit_id' => $eoi->summit_id,
            'submitted_at' => $eoi->created_at,
        ];

        if ($eoi->status === 'selected') {
            // Include token so the app can surface the "Complete Registration" button
            $response['registration_token'] = $eoi->registration_token;
            $response['message'] = 'Congratulations! You have been selected. Please complete your registration.';
        } elseif ($eoi->status === 'rejected') {
            $response['message'] = 'Unfortunately, your application was not successful at this time.';
            $response['reason'] = $eoi->rejection_reason;
        } else {
            $response['message'] = 'Your application is currently under review. We will notify you of the outcome.';
        }

        return response()->json($response);
    }

    // ─────────────────────────────────────────────────────────
    // PUBLIC: Complete registration using selection token
    // POST /api/auth/register/from-eoi
    // ─────────────────────────────────────────────────────────
    public function completeRegistration(Request $request)
    {
        $request->validate([
            'registration_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            // Additional profile fields (pre-filled from EOI but user confirms)
            'phone' => 'nullable|string|max:20',
        ]);

        // Find the EOI by token
        $eoi = SummitEoi::where('registration_token', $request->registration_token)
            ->where('status', 'selected')
            ->first();

        if (!$eoi) {
            return response()->json([
                'message' => 'Invalid or expired registration token. Please check your status again.',
            ], 403);
        }

        // Prevent reuse — if already linked to a user
        if ($eoi->registered_user_id) {
            return response()->json([
                'message' => 'This registration link has already been used. Please log in.',
            ], 409);
        }

        // Check if an account with this email already exists
        if (User::where('email', $eoi->email)->exists()) {
            return response()->json([
                'message' => 'An account with this email already exists. Please log in.',
            ], 409);
        }

        // Create the user account (status = pending — admin still approves)
        $user = User::create([
            'name' => $eoi->full_name,
            'email' => $eoi->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone ?? $eoi->phone,
            'business_name' => $eoi->business_name,
            'product_category' => $eoi->primary_products,
            'user_type' => 'exporter',
            'status' => 'pending', // Admin still does final approval
        ]);

        // Link the EOI to the new user and burn the token (one-time use)
        $eoi->update([
            'registered_user_id' => $user->id,
            'registration_token' => null, // Token is now consumed
        ]);

        Log::info("Registration completed from EOI: EOI ID {$eoi->id} → User ID {$user->id}");

        return response()->json([
            'message' => 'Registration successful! Your account is pending final approval. You will be notified once approved.',
            'user' => $user,
        ], 201);
    }

    // ─────────────────────────────────────────────────────────
    // ADMIN: List all EOIs (filterable by summit & status)
    // GET /api/admin/eois
    // ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = SummitEoi::with('summit')
            ->orderBy('created_at', 'desc');

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

        $eois = $query->paginate(20);

        return response()->json($eois);
    }

    // ─────────────────────────────────────────────────────────
    // ADMIN: View single EOI
    // GET /api/admin/eois/{eoi}
    // ─────────────────────────────────────────────────────────
    public function show(SummitEoi $eoi)
    {
        return response()->json($eoi->load('summit', 'registeredUser'));
    }

    // ─────────────────────────────────────────────────────────
    // ADMIN: Select an EOI → generate registration token
    // POST /api/admin/eois/{eoi}/select
    // ─────────────────────────────────────────────────────────
    public function select(Request $request, SummitEoi $eoi)
    {
        if ($eoi->status === 'selected') {
            return response()->json(['message' => 'This applicant has already been selected.'], 400);
        }

        // Generate a secure, unique token
        $token = Str::random(64);

        $eoi->update([
            'status' => 'selected',
            'selected_at' => now(),
            'rejection_reason' => null,
            'registration_token' => $token,
        ]);

        Log::info("EOI selected: ID {$eoi->id} by Admin ID {$request->user()->id}");

        return response()->json([
            'message' => 'Applicant selected successfully. Registration token generated.',
            'eoi' => $eoi->fresh(),
            'registration_token' => $token,
            // Admin can share this link or send it to the participant
            'registration_link' => url("/register/from-eoi?token={$token}"),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // ADMIN: Reject an EOI
    // POST /api/admin/eois/{eoi}/reject
    // ─────────────────────────────────────────────────────────
    public function reject(Request $request, SummitEoi $eoi)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        if ($eoi->status === 'rejected') {
            return response()->json(['message' => 'This applicant has already been rejected.'], 400);
        }

        $eoi->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'registration_token' => null,
        ]);

        Log::info("EOI rejected: ID {$eoi->id} by Admin ID {$request->user()->id}");

        return response()->json([
            'message' => 'Applicant rejected.',
            'eoi' => $eoi->fresh(),
        ]);
    }
}
