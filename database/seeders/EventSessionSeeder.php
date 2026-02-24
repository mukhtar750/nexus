<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventSession;

class EventSessionSeeder extends Seeder
{
    public function run(): void
    {
        // Get all events
        $events = Event::all();

        foreach ($events as $event) {
            $eventDuration = $event->start_time->diffInDays($event->end_time) + 1;

            // Create sessions for each day of the event
            for ($day = 0; $day < $eventDuration; $day++) {
                $currentDate = $event->start_time->copy()->addDays($day);

                // Create 4-6 sessions per day
                $sessions = $this->getSessionsForDay($day);

                foreach ($sessions as $index => $sessionData) {
                    // Calculate session times
                    $sessionStart = $currentDate->copy()->setTime(
                        $sessionData['start_hour'],
                        $sessionData['start_minute']
                    );
                    $sessionEnd = $currentDate->copy()->setTime(
                        $sessionData['end_hour'],
                        $sessionData['end_minute']
                    );

                    EventSession::create([
                        'event_id' => $event->id,
                        'title' => $sessionData['title'],
                        'speaker' => $sessionData['speaker'],
                        'start_time' => $sessionStart,
                        'end_time' => $sessionEnd,
                        'location' => $sessionData['location'],
                        'user_type_required' => $sessionData['user_type_required'] ?? null,
                    ]);
                }
            }
        }
    }

    private function getSessionsForDay(int $dayNumber): array
    {
        $allSessions = [
            // Day 0 sessions
            [
                [
                    'title' => 'Registration & Welcome Coffee',
                    'speaker' => 'NEPC Staff',
                    'start_hour' => 8,
                    'start_minute' => 0,
                    'end_hour' => 9,
                    'end_minute' => 0,
                    'location' => 'Main Lobby',
                ],
                [
                    'title' => 'Opening Ceremony & Keynote Address',
                    'speaker' => 'Hon. Minister of Trade',
                    'start_hour' => 9,
                    'start_minute' => 0,
                    'end_hour' => 10,
                    'end_minute' => 30,
                    'location' => 'Main Hall',
                ],
                [
                    'title' => 'Export Opportunities in Emerging Markets',
                    'speaker' => 'Dr. Amina Mohammed',
                    'start_hour' => 11,
                    'start_minute' => 0,
                    'end_hour' => 12,
                    'end_minute' => 30,
                    'location' => 'Conference Room A',
                ],
                [
                    'title' => 'Lunch Break & Networking',
                    'speaker' => '',
                    'start_hour' => 12,
                    'start_minute' => 30,
                    'end_hour' => 14,
                    'end_minute' => 0,
                    'location' => 'Dining Hall',
                ],
                [
                    'title' => 'Panel Discussion: Breaking into African Markets',
                    'speaker' => 'Multiple Speakers',
                    'start_hour' => 14,
                    'start_minute' => 0,
                    'end_hour' => 15,
                    'end_minute' => 30,
                    'location' => 'Main Hall',
                ],
                [
                    'title' => 'Workshop: Export Documentation Essentials',
                    'speaker' => 'Mr. John Okafor, NEPC',
                    'start_hour' => 16,
                    'start_minute' => 0,
                    'end_hour' => 17,
                    'end_minute' => 30,
                    'location' => 'Workshop Room 1',
                    'user_type_required' => 'exporter',
                ],
            ],
            // Day 1 sessions
            [
                [
                    'title' => 'Morning Tea & Networking',
                    'speaker' => '',
                    'start_hour' => 8,
                    'start_minute' => 30,
                    'end_hour' => 9,
                    'end_minute' => 0,
                    'location' => 'Main Lobby',
                ],
                [
                    'title' => 'Good Manufacturing Practices (GMP) for Exporters',
                    'speaker' => 'Dr. Sarah Chen',
                    'start_hour' => 9,
                    'start_minute' => 0,
                    'end_hour' => 10,
                    'end_minute' => 30,
                    'location' => 'Conference Room A',
                ],
                [
                    'title' => 'Digital Marketing for International Markets',
                    'speaker' => 'Mr. Emmanuel Adebayo',
                    'start_hour' => 11,
                    'start_minute' => 0,
                    'end_hour' => 12,
                    'end_minute' => 30,
                    'location' => 'Conference Room B',
                ],
                [
                    'title' => 'Lunch Break',
                    'speaker' => '',
                    'start_hour' => 12,
                    'start_minute' => 30,
                    'end_hour' => 14,
                    'end_minute' => 0,
                    'location' => 'Dining Hall',
                ],
                [
                    'title' => 'Export Financing & Government Incentives',
                    'speaker' => 'Mrs. Fatima Abdullahi, BOI',
                    'start_hour' => 14,
                    'start_minute' => 0,
                    'end_hour' => 15,
                    'end_minute' => 30,
                    'location' => 'Main Hall',
                ],
                [
                    'title' => 'Product Certification & Standards',
                    'speaker' => 'Eng. Patrick Nwosu, SON',
                    'start_hour' => 16,
                    'start_minute' => 0,
                    'end_hour' => 17,
                    'end_minute' => 30,
                    'location' => 'Workshop Room 2',
                    'user_type_required' => 'exporter',
                ],
            ],
            // Day 2 sessions
            [
                [
                    'title' => 'Branding for Export Success',
                    'speaker' => 'Ms. Jennifer Okonkwo',
                    'start_hour' => 9,
                    'start_minute' => 0,
                    'end_hour' => 10,
                    'end_minute' => 30,
                    'location' => 'Conference Room A',
                ],
                [
                    'title' => 'Logistics & Shipping Best Practices',
                    'speaker' => 'Capt. James Brown',
                    'start_hour' => 11,
                    'start_minute' => 0,
                    'end_hour' => 12,
                    'end_minute' => 0,
                    'location' => 'Conference Room B',
                ],
                [
                    'title' => 'Lunch & B2B Matchmaking',
                    'speaker' => '',
                    'start_hour' => 12,
                    'start_minute' => 0,
                    'end_hour' => 14,
                    'end_minute' => 0,
                    'location' => 'Networking Zone',
                ],
                [
                    'title' => 'Success Stories: Nigerian Export Champions',
                    'speaker' => 'Panel of Successful Exporters',
                    'start_hour' => 14,
                    'start_minute' => 0,
                    'end_hour' => 15,
                    'end_minute' => 30,
                    'location' => 'Main Hall',
                ],
                [
                    'title' => 'Closing Ceremony & Certificate Presentation',
                    'speaker' => 'Director General, NEPC',
                    'start_hour' => 16,
                    'start_minute' => 0,
                    'end_hour' => 17,
                    'end_minute' => 0,
                    'location' => 'Main Hall',
                ],
            ],
        ];

        return $allSessions[$dayNumber % count($allSessions)];
    }
}
