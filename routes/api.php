<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\SummitEoiController;

// Guest registration (open)
Route::post('/auth/register/guest', [AuthController::class, 'registerGuest']);
Route::post('/auth/login', [AuthController::class, 'login']);

// ─── EOI (Exporter) ────────────────────────────────────────────────────────
// Step 1: Submit expression of interest (no account required)
Route::post('/summits/{summit}/eoi', [SummitEoiController::class, 'store']);
// Step 2: Check EOI status by email
Route::post('/summits/eoi/check-status', [SummitEoiController::class, 'checkStatus']);
// Step 3: Complete registration using admin-issued selection token
Route::post('/auth/register/from-eoi', [SummitEoiController::class, 'completeRegistration']);

// Public events list (guests can view)
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);

// Public summits list
Route::get('/summits', [\App\Http\Controllers\Api\SummitController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        // Return user with roles loaded
        return $request->user()->load('roles');
    });
    Route::post('/user/profile', [ProfileController::class, 'update']);

    // Invitations
    Route::get('/invitations', [InvitationController::class, 'index']);
    Route::post('/invitations/{id}/respond', [InvitationController::class, 'respond']);

    // Event Registration
    Route::post('/events/{id}/register', [EventController::class, 'register']);
    Route::get('/user/events', [EventController::class, 'myEvents']);

    // Engagement Routes
    Route::get('/events/{event}/polls', [\App\Http\Controllers\Api\PollController::class, 'index']);
    Route::post('/polls/{poll}/vote', [\App\Http\Controllers\Api\PollController::class, 'vote']);

    Route::get('/events/{event}/questions', [\App\Http\Controllers\Api\QuestionController::class, 'index']);
    Route::post('/events/{event}/questions', [\App\Http\Controllers\Api\QuestionController::class, 'store']);
    Route::post('/questions/{question}/upvote', [\App\Http\Controllers\Api\QuestionController::class, 'upvote']);

    // Staff Routes (Protected by role:staff)
    Route::middleware(['role:staff'])->group(function () {
        Route::post('/staff/verify-ticket', [\App\Http\Controllers\Api\StaffController::class, 'verifyTicket']);
    });

    // Admin Routes (Protected by role:admin)
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/stats', [\App\Http\Controllers\Api\AdminController::class, 'stats']);
        Route::get('/users', [\App\Http\Controllers\Api\AdminController::class, 'index']);
        Route::get('/users/pending', [\App\Http\Controllers\Api\AdminController::class, 'pendingUsers']);
        Route::post('/users/{user}/assign-role', [\App\Http\Controllers\Api\AdminController::class, 'assignRole']);
        Route::post('/users/{user}/remove-role', [\App\Http\Controllers\Api\AdminController::class, 'removeRole']);
        Route::post('/users/{user}/approve', [\App\Http\Controllers\Api\AdminController::class, 'approveUser']);
        Route::post('/users/{user}/reject', [\App\Http\Controllers\Api\AdminController::class, 'rejectUser']);
        Route::post('/events/invite', [\App\Http\Controllers\Api\AdminController::class, 'inviteUser']);

        // ─── EOI Management ─────────────────────────────────────────────
        Route::get('/eois', [SummitEoiController::class, 'index']);
        Route::get('/eois/{eoi}', [SummitEoiController::class, 'show']);
        Route::post('/eois/{eoi}/select', [SummitEoiController::class, 'select']);
        Route::post('/eois/{eoi}/reject', [SummitEoiController::class, 'reject']);
    });

    // Events Routes
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
});
