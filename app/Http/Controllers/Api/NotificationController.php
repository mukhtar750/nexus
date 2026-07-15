<?php

namespace App\Http\Controllers\Api;

// Correct class name capitalization
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use App\Http\Resources\UserNotificationResource;

class NotificationController extends Controller
{
    /**
     * List all notifications for the current user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notifications = UserNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return UserNotificationResource::collection($notifications);
    }

    /**
     * Mark a notification as read.
     */
    public function read(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notification = UserNotification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->read_at = now();
        $notification->save();

        return response()->json([
            'message' => 'Notification marked as read',
            'data' => new UserNotificationResource($notification)
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function readAll(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        UserNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }
}
