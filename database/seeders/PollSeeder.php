<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Poll;
use App\Models\PollOption;

class PollSeeder extends Seeder
{
    public function run(): void
    {
        // Get all events
        $events = Event::all();

        foreach ($events as $event) {
            // Create 2-3 polls per event
            $pollCount = rand(2, 3);

            for ($i = 0; $i < $pollCount; $i++) {
                $poll = Poll::create([
                    'event_id' => $event->id,
                    'question' => $this->getPollQuestions()[$i % count($this->getPollQuestions())],
                    'is_active' => true,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                ]);

                // Create 3-4 options per poll
                $options = $this->getPollOptions($i % count($this->getPollQuestions()));
                foreach ($options as $optionText) {
                    PollOption::create([
                        'poll_id' => $poll->id,
                        'option_text' => $optionText,
                    ]);
                }
            }
        }
    }

    private function getPollQuestions(): array
    {
        return [
            'Which export market are you most interested in?',
            'What is your biggest export challenge?',
            'Which capacity building topic would you like to learn more about?',
            'How did you hear about this event?',
            'What product are you currently exporting or planning to export?',
        ];
    }

    private function getPollOptions(int $questionIndex): array
    {
        $optionSets = [
            // Export markets
            ['Europe', 'Asia', 'North America', 'Middle East', 'Africa'],

            // Export challenges
            ['Documentation', 'Finding Buyers', 'Compliance & Regulations', 'Logistics & Shipping', 'Financing'],

            // Capacity building topics
            ['Good Manufacturing Practices', 'Branding & Marketing', 'Export Process', 'Regulations & Certifications', 'Business Development'],

            // How did you hear
            ['Social Media', 'NEPC Website', 'Email Invitation', 'Friend or Colleague', 'Other'],

            // Products
            ['Agricultural Products', 'Handicrafts', 'Textiles', 'Processed Foods', 'Other'],
        ];

        return $optionSets[$questionIndex] ?? $optionSets[0];
    }
}
