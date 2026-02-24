<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Invitation;

class InvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users (exporters and guests)
        $users = User::take(5)->get();
        // Get some events
        $events = Event::take(3)->get();

        if ($users->isEmpty() || $events->isEmpty()) {
            return;
        }

        foreach ($users as $index => $user) {
            // Invite user to the first event
            Invitation::updateOrCreate(
                ['user_id' => $user->id, 'event_id' => $events[0]->id],
                ['status' => 'pending']
            );

            // Invite user to the second event if it exists (some as accepted)
            if (isset($events[1]) && $index % 2 == 0) {
                Invitation::updateOrCreate(
                    ['user_id' => $user->id, 'event_id' => $events[1]->id],
                    ['status' => 'accepted']
                );
            }
        }
    }
}
