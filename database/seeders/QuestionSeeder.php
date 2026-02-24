<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Question;
use App\Models\User;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Get all events
        $events = Event::all();

        // Get a few users to post questions
        $users = User::take(5)->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Skipping question seeding.');
            return;
        }

        foreach ($events as $event) {
            // Create 3-5 questions per event
            $questionCount = rand(3, 5);

            for ($i = 0; $i < $questionCount; $i++) {
                $question = Question::create([
                    'event_id' => $event->id,
                    'user_id' => $users->random()->id,
                    'content' => $this->getQuestions()[$i % count($this->getQuestions())],
                    'is_approved' => true,
                ]);

                // Add some upvotes randomly
                $upvoteCount = rand(0, min($users->count(), 3));
                $upvoters = $users->random($upvoteCount);

                foreach ($upvoters as $upvoter) {
                    $question->upvotes()->create([
                        'user_id' => $upvoter->id,
                    ]);
                }
            }
        }
    }

    private function getQuestions(): array
    {
        return [
            'How do I obtain an export license in Nigeria?',
            'What are the documentation requirements for exporting agricultural products to Europe?',
            'Which certification is needed for food exports to the US?',
            'How can I find reliable international buyers for my products?',
            'What are the current export incentives available from the government?',
            'What is the process for getting NAFDAC certification?',
            'How do I handle customs procedures for first-time exporters?',
            'Are there any grants or loans available for export businesses?',
            'What packaging standards are required for export products?',
            'How can I participate in international trade fairs?',
        ];
    }
}
