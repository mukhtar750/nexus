<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'company' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('profile_photo')) {
            $avatarPath = $request->file('profile_photo')->store('avatars', 'public');
            $user->avatar_url = $avatarPath;
        }

        // Update other fields
        $user->update($request->only(['name', 'phone', 'company', 'bio']));
        $user->save();

        return response()->json([
            'user' => $user->load('roles'),
            'message' => 'Profile updated successfully'
        ]);
    }
}
