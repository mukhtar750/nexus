<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\SummitEoiController;

use App\Http\Controllers\Api\InvitationConfirmationController;

// Guest registration (open)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/register/guest', [AuthController::class, 'registerGuest']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// ─── Invitation Confirmations (token-gated, no auth required) ─────────────
Route::get('/invitations/validate-token', [InvitationConfirmationController::class, 'validateToken']);
Route::post('/invitations/confirm/delegate', [InvitationConfirmationController::class, 'confirmDelegate']);
Route::post('/invitations/confirm/speaker', [InvitationConfirmationController::class, 'confirmSpeaker']);
Route::post('/invitations/public-confirm', [InvitationConfirmationController::class, 'publicConfirmation']);



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
Route::get('/summits/{summit}', [\App\Http\Controllers\Api\SummitController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        // Return user with roles and invitations loaded
        return $request->user()->load([
            'roles', 
            'invitations.summit', 
            'speakerInvitations.summit'
        ]);
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

        // ─── Invitation Management ──────────────────────────────────────────
        Route::post('/invitations/create-delegate', [InvitationConfirmationController::class, 'createDelegateInvite']);
        Route::post('/invitations/create-speaker', [InvitationConfirmationController::class, 'createSpeakerInvite']);



        // ─── EOI Management ─────────────────────────────────────────────
        Route::get('/eois', [SummitEoiController::class, 'index']);
        Route::get('/eois/{eoi}', [SummitEoiController::class, 'show']);
        Route::post('/eois/{eoi}/select', [SummitEoiController::class, 'select']);
        Route::post('/eois/{eoi}/reject', [SummitEoiController::class, 'reject']);
    });

    // Community Routes
    Route::get('/community/posts', [\App\Http\Controllers\Api\CommunityController::class, 'index']);
    Route::post('/community/posts', [\App\Http\Controllers\Api\CommunityController::class, 'store']);
    Route::get('/community/posts/{post}', [\App\Http\Controllers\Api\CommunityController::class, 'show']);
    Route::delete('/community/posts/{post}', [\App\Http\Controllers\Api\CommunityController::class, 'destroy']);
    Route::post('/community/posts/{post}/like', [\App\Http\Controllers\Api\CommunityController::class, 'like']);
    Route::post('/community/posts/{post}/vote', [\App\Http\Controllers\Api\CommunityController::class, 'vote']);
    Route::post('/community/posts/{post}/pin', [\App\Http\Controllers\Api\CommunityController::class, 'pin']);
    Route::post('/community/report/{type}/{id}', [\App\Http\Controllers\Api\CommunityController::class, 'report']);
    
    Route::get('/community/posts/{post}/comments', [\App\Http\Controllers\Api\CommunityController::class, 'comments']);
    Route::post('/community/posts/{post}/comments', [\App\Http\Controllers\Api\CommunityController::class, 'storeComment']);
    Route::delete('/community/comments/{comment}', [\App\Http\Controllers\Api\CommunityController::class, 'destroyComment']);

    // Certificates Routes
    Route::get('/certificates', [\App\Http\Controllers\Api\CertificateController::class, 'index']);
    Route::post('/certificates', [\App\Http\Controllers\Api\CertificateController::class, 'store']);

    // Notifications Routes
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'read']);
    Route::post('/notifications/read-all', [\App\Http\Controllers\Api\NotificationController::class, 'readAll']);

    // Events Routes
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
});
