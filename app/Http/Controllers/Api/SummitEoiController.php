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
use Illuminate\Support\Facades\Mail;
use App\Mail\EoiSelected;

class SummitEoiController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // PUBLIC: Submit Expression of Interest
    // POST /api/summits/{summit}/eoi
    // ─────────────────────────────────────────────────────────
    public function store(Request $request, Summit $summit)
    {
        $user = $request->user('sanctum');

        $validated = $request->validate([
            // Section A
            'full_name' => $user ? 'nullable|string|max:255' : 'required|string|max:255',
            'phone' => $user ? 'nullable|string|max:20' : 'required|string|max:20',
            'email' => $user ? 'nullable|email|max:255' : 'required|email|max:255',
            'business_name' => $user ? 'nullable|string|max:255' : 'required|string|max:255',
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
            // Section D (Rigorous / Business Profile)
            'business_address' => 'nullable|string|max:500',
            'year_established' => 'nullable|string|max:4',
            'business_structure' => 'nullable|string|max:255',
            'cac_number' => 'nullable|string|max:50',
            'cac_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nepc_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'haccp_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'fda_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'halal_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'son_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'production_location' => 'nullable|string|max:255',
            'production_compliant' => 'nullable|boolean',
            'production_capacity' => 'nullable|string|max:255',
            'active_channels' => 'nullable|string',
            'sales_model' => 'nullable|string|max:255',
            'export_objective' => 'nullable|string|max:500',
        ]);

        $email = $user ? $user->email : $validated['email'];

        // Prevent duplicate EOI for same email + summit
        $existing = SummitEoi::where('email', $email)
            ->where('summit_id', $summit->id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'An expression of interest has already been submitted with this email for this summit.',
                'status' => $existing->status,
            ], 409);
        }

        // Handle File Uploads
        $fileFields = [
            'cac_certificate', 'nepc_certificate', 'haccp_certificate',
            'fda_certificate', 'halal_certificate', 'son_certificate'
        ];
        $filePaths = [];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $filePaths[$field] = $request->file($field)->store('certificates', 'public');
            }
        }

        $eoi = SummitEoi::create(array_merge($validated, $filePaths, [
            'summit_id' => $summit->id,
            'email' => $email,
            'full_name' => $user ? ($validated['full_name'] ?? $user->name) : $validated['full_name'],
            'phone' => $user ? ($validated['phone'] ?? $user->phone) : $validated['phone'],
            'business_name' => $user ? ($validated['business_name'] ?? $user->business_name ?? $user->company) : $validated['business_name'],
            'registered_user_id' => $user ? $user->id : null,
            'status' => 'pending',
        ]));

        Log::info("EOI submitted: ID {$eoi->id} for Summit {$summit->id} by {$eoi->email}");

        // ── AUTO-SELECTION LOGIC ─────────────────────────────────────────────
        if ($eoi->shouldAutoSelect()) {
            $eoi->update([
                'status' => 'selected',
                'selected_at' => now(),
                'registration_token' => Str::random(40),
            ]);

            try {
                Mail::to($eoi->email)->send(new EoiSelected($eoi));
                Log::info("EOI ID {$eoi->id} auto-selected and notification sent.");
            } catch (\Exception $e) {
                Log::error("Failed to send auto-selection email for EOI {$eoi->id}: " . $e->getMessage());
            }

            return response()->json([
                'message' => 'Congratulations! Your profile meets our high-quality criteria and you have been automatically selected. Check your email to complete registration.',
                'eoi_id' => $eoi->id,
                'status' => 'selected',
            ], 201);
        }

        return response()->json([
            'message' => 'Your expression of interest has been submitted successfully! We will be in touch if you are selected.',
            'eoi_id' => $eoi->id,
            'status' => 'pending',
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
            'summit_id' => 'nullable|integer|exists:summits,id',
        ]);

        $query = SummitEoi::where('email', $request->email);

        if ($request->filled('summit_id')) {
            $query->where('summit_id', $request->summit_id);
        } else {
            // Global check: prioritize selected, then pending, then rejected
            $query->orderByRaw("FIELD(status, 'selected', 'pending', 'rejected') ASC")
                  ->orderBy('created_at', 'desc');
        }

        $eoi = $query->first();

        if (!$eoi) {
            return response()->json([
                'message' => 'No expression of interest found for this email.',
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
            'company' => $eoi->business_name,
            'business_name' => $eoi->business_name,
            'business_address' => $eoi->business_address,
            'year_established' => $eoi->year_established,
            'business_structure' => $eoi->business_structure,
            'cac_number' => $eoi->cac_number,
            'cac_certificate_path' => $eoi->cac_certificate,
            'nepc_certificate_path' => $eoi->nepc_certificate,
            'haccp_certificate_path' => $eoi->haccp_certificate,
            'fda_certificate_path' => $eoi->fda_certificate,
            'halal_certificate_path' => $eoi->halal_certificate,
            'son_certificate_path' => $eoi->son_certificate,
            'product_category' => $eoi->primary_products,
            'registered_with_cac' => $eoi->cac_registration === 'yes',
            'exported_before' => $eoi->export_status !== 'exploring',
            'registered_with_nepc' => $eoi->nepc_registration === 'yes',
            'nepc_status' => $eoi->nepc_registration === 'yes' ? 'Registered' : 'Not Registered',
            'recent_export_activity' => $eoi->export_status,
            'commercial_scale' => $eoi->commercial_scale,
            'packaged_for_retail' => false, // Default or add to EOI
            'regulatory_registration' => $eoi->regulatory_registration,
            'engaged_logistics' => false, // Add to EOI if needed
            'received_inquiries' => false, // Add to EOI if needed
            'production_location' => $eoi->production_location,
            'production_compliant' => $eoi->production_compliant,
            'production_capacity' => $eoi->production_capacity,
            'active_channels' => $eoi->active_channels,
            'sales_model' => $eoi->sales_model,
            'export_objective' => $eoi->export_objective,
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
