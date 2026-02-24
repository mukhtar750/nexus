<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\Admin\PollController;
use App\Http\Controllers\Web\Admin\QuestionController;
use App\Http\Controllers\Web\Admin\AttendeeController;
use App\Http\Controllers\Web\Admin\SpeakerController;
use App\Http\Controllers\Web\Admin\SessionController;



Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Admin Web Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::post('/users/{user}/roles', [AdminController::class, 'updateUserRole'])->name('users.updateRole');
        Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');

        // Events
        Route::get('/events', [AdminController::class, 'events'])->name('events.index');
        Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
        Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
        Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
        Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
        Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('events.delete');
        Route::get('/events/{event}/invitations', [AdminController::class, 'invitations'])->name('events.invitations');
        Route::post('/events/{event}/invitations', [AdminController::class, 'storeInvitation'])->name('events.invitations.store');
        Route::delete('/invitations/{invitation}', [AdminController::class, 'deleteInvitation'])->name('invitations.delete');

        // Summits
        Route::get('/summits', [\App\Http\Controllers\Web\Admin\SummitController::class, 'index'])->name('summits.index');
        Route::get('/summits/create', [\App\Http\Controllers\Web\Admin\SummitController::class, 'create'])->name('summits.create');
        Route::post('/summits', [\App\Http\Controllers\Web\Admin\SummitController::class, 'store'])->name('summits.store');
        Route::get('/summits/{summit}/edit', [\App\Http\Controllers\Web\Admin\SummitController::class, 'edit'])->name('summits.edit');
        Route::put('/summits/{summit}', [\App\Http\Controllers\Web\Admin\SummitController::class, 'update'])->name('summits.update');
        Route::delete('/summits/{summit}', [\App\Http\Controllers\Web\Admin\SummitController::class, 'destroy'])->name('summits.delete');

        // Event Engagement
        Route::prefix('events/{event}')->name('events.')->group(function () {
            // Polls
            Route::get('/polls', [PollController::class, 'index'])->name('polls.index');
            Route::get('/polls/create', [PollController::class, 'create'])->name('polls.create');
            Route::post('/polls', [PollController::class, 'store'])->name('polls.store');
            Route::get('/polls/{poll}/edit', [PollController::class, 'edit'])->name('polls.edit');
            Route::put('/polls/{poll}', [PollController::class, 'update'])->name('polls.update');
            Route::delete('/polls/{poll}', [PollController::class, 'destroy'])->name('polls.delete');

            // Questions
            Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
            Route::post('/questions/{question}/toggle', [QuestionController::class, 'toggleApproval'])->name('questions.toggle');
            Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.delete');

            // Attendees
            Route::get('/attendees', [AttendeeController::class, 'index'])->name('attendees.index');

            // Speakers
            Route::get('/speakers', [SpeakerController::class, 'index'])->name('speakers.index');
            Route::get('/speakers/create', [SpeakerController::class, 'create'])->name('speakers.create');
            Route::post('/speakers', [SpeakerController::class, 'store'])->name('speakers.store');
            Route::get('/speakers/{speaker}/edit', [SpeakerController::class, 'edit'])->name('speakers.edit');
            Route::put('/speakers/{speaker}', [SpeakerController::class, 'update'])->name('speakers.update');
            Route::delete('/speakers/{speaker}', [SpeakerController::class, 'destroy'])->name('speakers.delete');

            // Sessions
            Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
            Route::get('/sessions/create', [SessionController::class, 'create'])->name('sessions.create');
            Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
            Route::get('/sessions/{session}/edit', [SessionController::class, 'edit'])->name('sessions.edit');
            Route::put('/sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');
            Route::delete('/sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.delete');
        });
    });
});
