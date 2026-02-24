<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;

try {
    $user = User::first();
    $event = Event::first();

    if (!$user || !$event) {
        die("Missing user or event\n");
    }

    echo "Registering user {$user->id} for event {$event->id}...\n";

    $id = $event->id;

    // Check if already registered
    if ($user->tickets()->where('event_id', $id)->exists()) {
        echo "Already registered. Deleting existing ticket for test...\n";
        $user->tickets()->where('event_id', $id)->delete();
    }

    $ticket = Ticket::create([
        'user_id' => $user->id,
        'event_id' => $id,
        'qr_code_data' => 'TEST-TICKET-' . $user->id . '-' . $id . '-' . time(),
        'status' => 'valid',
    ]);

    echo "Registered successfully! Ticket ID: {$ticket->id}\n";
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
