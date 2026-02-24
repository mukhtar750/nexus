<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\LoginRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Handle File Uploads
        $cacCertificatePath = null;
        if ($request->hasFile('cac_certificate')) {
            $cacCertificatePath = $request->file('cac_certificate')->store('certificates', 'public');
        }

        $nepcCertificatePath = null;
        if ($request->hasFile('nepc_certificate')) {
            $nepcCertificatePath = $request->file('nepc_certificate')->store('certificates', 'public');
        }

        $avatarPath = null;
        if ($request->hasFile('profile_photo')) {
            $avatarPath = $request->file('profile_photo')->store('avatars', 'public');
        }

        $haccpPath = null;
        if ($request->hasFile('haccp_certificate')) {
            $haccpPath = $request->file('haccp_certificate')->store('certificates', 'public');
        }

        $fdaPath = null;
        if ($request->hasFile('fda_certificate')) {
            $fdaPath = $request->file('fda_certificate')->store('certificates', 'public');
        }

        $halalPath = null;
        if ($request->hasFile('halal_certificate')) {
            $halalPath = $request->file('halal_certificate')->store('certificates', 'public');
        }

        $sonPath = null;
        if ($request->hasFile('son_certificate')) {
            $sonPath = $request->file('son_certificate')->store('certificates', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'business_name' => $validated['business_name'],
            'business_address' => $validated['business_address'] ?? null,
            'year_established' => $validated['year_established'] ?? null,
            'business_structure' => $validated['business_structure'] ?? null,
            'cac_number' => $validated['cac_number'] ?? null,
            'cac_certificate_path' => $cacCertificatePath,
            'product_category' => $validated['product_category'] ?? null,
            'registered_with_cac' => $validated['registered_with_cac'] ?? false,
            'exported_before' => $validated['exported_before'] ?? false,
            'registered_with_nepc' => $validated['registered_with_nepc'] ?? false,
            'nepc_status' => $validated['nepc_status'] ?? null,
            'nepc_certificate_path' => $nepcCertificatePath,
            'recent_export_activity' => $validated['recent_export_activity'] ?? null,
            'commercial_scale' => $validated['commercial_scale'] ?? false,
            'packaged_for_retail' => $validated['packaged_for_retail'] ?? false,
            'regulatory_registration' => $validated['regulatory_registration'] ?? false,
            'engaged_logistics' => $validated['engaged_logistics'] ?? false,
            'received_inquiries' => $validated['received_inquiries'] ?? false,
            'production_location' => $validated['production_location'] ?? null,
            'production_compliant' => $validated['production_compliant'] ?? false,
            'production_capacity' => $validated['production_capacity'] ?? null,
            'active_channels' => $validated['active_channels'] ?? null,
            'sales_model' => $validated['sales_model'] ?? null,
            'export_objective' => $validated['export_objective'] ?? null,
            'user_type' => 'exporter',
            'status' => 'pending',
            'avatar_url' => $avatarPath,
            'haccp_certificate_path' => $haccpPath,
            'fda_certificate_path' => $fdaPath,
            'halal_certificate_path' => $halalPath,
            'son_certificate_path' => $sonPath,
        ]);

        // DO NOT issue token - user must be approved first
        return response()->json([
            'message' => 'Registration successful! Your account is pending admin approval. You will be notified once approved.',
            'user' => $user
        ], 201);
    }

    public function registerGuest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('profile_photo')) {
            $avatarPath = $request->file('profile_photo')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'phone' => $validated['phone'],
            'user_type' => 'guest',
            'status' => 'approved', // Auto-approve guests
            'avatar_url' => $avatarPath,
        ]);

        // Issue token immediately for guests
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check account status
        if ($user->status === 'pending') {
            return response()->json([
                'message' => 'Your account is pending admin approval. Please wait for approval to access the system.'
            ], 403);
        }

        if ($user->status === 'rejected') {
            return response()->json([
                'message' => 'Your registration has been rejected. Please contact support for more information.'
            ], 403);
        }

        // Only issue token if approved
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}
