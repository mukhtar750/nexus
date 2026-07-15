<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Speaker;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['description' => 'Administrator with full access']);
        $staffRole = Role::firstOrCreate(['name' => 'staff'], ['description' => 'Staff member for event management']);
        $attendeeRole = Role::firstOrCreate(['name' => 'attendee'], ['description' => 'Regular event attendee']);

        // Create Admin User
        $admin = User::firstWhere('email', 'admin@exporthub.com');
        if (!$admin) {
            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@exporthub.com',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]);
        }
        if (!$admin->roles()->where('name', 'admin')->exists()) {
            $admin->roles()->attach($adminRole);
        }

        // Create Staff User
        $staff = User::firstWhere('email', 'staff@exporthub.com');
        if (!$staff) {
            $staff = User::factory()->create([
                'name' => 'Staff Member',
                'email' => 'staff@exporthub.com',
                'role' => 'staff',
                'password' => bcrypt('password'),
            ]);
        }
        if (!$staff->roles()->where('name', 'staff')->exists()) {
            $staff->roles()->attach($staffRole);
        }

        // Create Test User (Attendee)
        $attendee = User::firstWhere('email', 'test@example.com');
        if (!$attendee) {
            $attendee = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'attendee',
            ]);
        }
        if (!$attendee->roles()->where('name', 'attendee')->exists()) {
            $attendee->roles()->attach($attendeeRole);
        }

        // Create sample events
        $event1 = Event::updateOrCreate(
            ['title' => 'Nigerian Export Summit 2026'],
            [
                'description' => 'Join industry leaders and exporters for the biggest export summit of the year. Network, learn, and grow your export business.',
                'start_time' => now()->addDays(15),
                'end_time' => now()->addDays(17),
                'location' => 'Eko Convention Centre, Lagos',
                'cover_image_url' => null,
                'category' => 'summit',
            ]
        );

        $event2 = Event::updateOrCreate(
            ['title' => 'Agriculture Export Masterclass'],
            [
                'description' => 'Learn the best practices for exporting agricultural products from Nigeria to international markets.',
                'start_time' => now()->addDays(30),
                'end_time' => now()->addDays(30)->addHours(6),
                'location' => 'Abuja International Conference Centre',
                'cover_image_url' => null,
                'category' => 'training',
            ]
        );

        $event3 = Event::updateOrCreate(
            ['title' => 'SME Export Financing Workshop'],
            [
                'description' => 'Discover funding opportunities and financing options for small and medium enterprises in the export sector.',
                'start_time' => now()->addDays(45),
                'end_time' => now()->addDays(45)->addHours(4),
                'location' => 'Transcorp Hilton, Abuja',
                'cover_image_url' => null,
                'category' => 'general',
            ]
        );

        // Create Summits for EOI flow
        \App\Models\Summit::updateOrCreate(
            ['title' => 'Lagos Sensitisation Seminar'],
            [
                'event_id' => $event1->id,
                'city' => 'Lagos',
                'zone' => 'South West',
                'date' => 'May 2026',
                'venue' => 'Eko Hotel',
                'is_active' => true,
                'is_eoi_open' => true, // Unlocked
                'hasHighlights' => false,
            ]
        );

        \App\Models\Summit::updateOrCreate(
            ['title' => 'Kano Sensitisation Seminar'],
            [
                'event_id' => $event1->id,
                'city' => 'Kano',
                'zone' => 'North West',
                'date' => 'June 2026',
                'venue' => 'Kano State Library',
                'is_active' => true,
                'is_eoi_open' => false, // Locked
                'hasHighlights' => false,
            ]
        );

        // Create speakers for event 1
        Speaker::updateOrCreate(
            ['name' => 'Dr. Amina Mohammed', 'event_id' => $event1->id],
            [
                'title' => 'Director General, WTO',
                'bio' => 'Dr. Amina Mohammed is a renowned economist and the current Director General of the World Trade Organization.',
                'avatar_url' => 'https://ui-avatars.com/api/?name=Amina+Mohammed&background=random',
            ]
        );

        Speaker::updateOrCreate(
            ['name' => 'John Doe', 'event_id' => $event1->id],
            [
                'title' => 'CEO, ExportPro',
                'bio' => 'John Doe has over 20 years of experience in international trade logistics.',
                'avatar_url' => 'https://ui-avatars.com/api/?name=John+Doe&background=random',
            ]
        );

        // Create sessions for event 1
        EventSession::updateOrCreate(
            ['title' => 'Opening Keynote: The Future of Nigerian Exports', 'event_id' => $event1->id],
            [
                'speaker' => 'Dr. Amina Mohammed',
                'start_time' => $event1->start_time,
                'end_time' => $event1->start_time->addHours(2),
                'location' => 'Main Hall',
            ]
        );

        EventSession::updateOrCreate(
            ['title' => 'Panel: Breaking into African Markets', 'event_id' => $event1->id],
            [
                'speaker' => 'Multiple Speakers',
                'start_time' => $event1->start_time->addHours(3),
                'end_time' => $event1->start_time->addHours(5),
                'location' => 'Conference Room A',
            ]
        );

        // Seed Polls and Questions
        $this->call([
            PollSeeder::class,
            QuestionSeeder::class,
        ]);

        // Seed Community Data
        $admin = User::firstWhere('email', 'admin@exporthub.com');
        $staff = User::firstWhere('email', 'staff@exporthub.com');
        $attendee = User::firstWhere('email', 'test@example.com');

        if ($admin && $staff && $attendee) {
            // 1. Pinned Announcement Post
            $announcement = \App\Models\CommunityPost::updateOrCreate(
                ['title' => 'Welcome to the Exporter Community! 🎉'],
                [
                    'user_id' => $admin->id,
                    'content' => "This is the official discussion forum for non-oil exporters. Connect with peers, share trade insights, ask regulatory questions, and collaborate for global growth. Please keep discussions professional and constructive.",
                    'type' => 'text',
                    'is_pinned' => true,
                ]
            );

            // 2. Normal Discussion Post
            $post1 = \App\Models\CommunityPost::updateOrCreate(
                ['title' => 'FDA Certification for Shea Butter to US markets'],
                [
                    'user_id' => $attendee->id,
                    'content' => "Hello everyone! I am preparing to ship our first major batch of raw shea butter to the US. I wanted to ask about the FDA registration process. Did you register directly or hire a third-party US agent? What was the typical timeline?",
                    'type' => 'text',
                    'is_pinned' => false,
                ]
            );

            // Seed comment and reply
            $comment = \App\Models\CommunityComment::updateOrCreate(
                ['content' => 'In my experience, registering is simple but you MUST have a US Agent. I used a local consultant who handled the agent representation. Timeline was about 3 weeks.', 'community_post_id' => $post1->id],
                [
                    'user_id' => $staff->id,
                ]
            );

            \App\Models\CommunityComment::updateOrCreate(
                ['content' => 'Thanks! Do you mind sharing the contact details of the consultant?', 'parent_id' => $comment->id],
                [
                    'community_post_id' => $post1->id,
                    'user_id' => $attendee->id,
                ]
            );

            // 3. Poll Post
            $pollPost = \App\Models\CommunityPost::updateOrCreate(
                ['title' => 'Preferred Shipping Mode to Europe? 🚢✈️'],
                [
                    'user_id' => $staff->id,
                    'content' => "For those exporting agro-commodities (cashew, ginger) to European buyers, what is your most reliable shipping method?",
                    'type' => 'poll',
                    'is_pinned' => false,
                ]
            );

            if ($pollPost->pollOptions()->count() === 0) {
                $opt1 = \App\Models\CommunityPollOption::create([
                    'community_post_id' => $pollPost->id,
                    'option_text' => 'Air Freight (Fast but expensive)',
                ]);
                $opt2 = \App\Models\CommunityPollOption::create([
                    'community_post_id' => $pollPost->id,
                    'option_text' => 'Ocean Freight LCL (Shared Container)',
                ]);
                $opt3 = \App\Models\CommunityPollOption::create([
                    'community_post_id' => $pollPost->id,
                    'option_text' => 'Ocean Freight FCL (Full Container Load)',
                ]);

                // Seed some votes
                \App\Models\CommunityPollVote::create([
                    'community_post_id' => $pollPost->id,
                    'community_poll_option_id' => $opt3->id,
                    'user_id' => $attendee->id,
                ]);
                \App\Models\CommunityPollVote::create([
                    'community_post_id' => $pollPost->id,
                    'community_poll_option_id' => $opt3->id,
                    'user_id' => $admin->id,
                ]);
            }

            // Seed likes
            \App\Models\CommunityLike::firstOrCreate([
                'user_id' => $staff->id,
                'community_post_id' => $post1->id,
            ]);
            \App\Models\CommunityLike::firstOrCreate([
                'user_id' => $admin->id,
                'community_post_id' => $post1->id,
            ]);

            // Seed some notifications for test user
            \App\Models\UserNotification::updateOrCreate(
                ['title' => 'New Reply on Shea Butter Post'],
                [
                    'user_id' => $attendee->id,
                    'message' => "Staff Member replied to your comment on 'FDA Certification for Shea Butter to US markets'",
                    'type' => 'reply',
                    'reference_id' => $post1->id,
                ]
            );
            \App\Models\UserNotification::updateOrCreate(
                ['title' => 'New Comment on Pinned Post'],
                [
                    'user_id' => $attendee->id,
                    'message' => "Admin User commented on 'Welcome to the Exporter Community!'",
                    'type' => 'comment',
                    'reference_id' => $announcement->id,
                ]
            );
        }
    }
}
