<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClaimedCertificate;
use App\Http\Resources\ClaimedCertificateResource;

class CertificateController extends Controller
{
    /**
     * Get all certificates claimed by the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $certificates = ClaimedCertificate::where('user_id', $user->id)
            ->orderBy('claimed_at', 'desc')
            ->get();

        return ClaimedCertificateResource::collection($certificates);
    }

    /**
     * Claim a new certificate.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'module_id' => 'required|string',
            'module_title' => 'required|string',
        ]);

        // Check if already claimed
        $existing = ClaimedCertificate::where('user_id', $user->id)
            ->where('module_id', $validated['module_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Certificate already claimed',
                'data' => new ClaimedCertificateResource($existing)
            ], 200);
        }

        $certificate = ClaimedCertificate::create([
            'user_id' => $user->id,
            'module_id' => $validated['module_id'],
            'module_title' => $validated['module_title'],
            'claimed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Certificate claimed successfully',
            'data' => new ClaimedCertificateResource($certificate)
        ], 201);
    }
}
