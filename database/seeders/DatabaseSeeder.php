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
        $adminRole = Role::create(['name' => 'admin', 'description' => 'Administrator with full access']);
        $staffRole = Role::create(['name' => 'staff', 'description' => 'Staff member for event management']);
        $attendeeRole = Role::create(['name' => 'attendee', 'description' => 'Regular event attendee']);

        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@exporthub.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($adminRole);

        // Create Staff User
        $staff = User::factory()->create([
            'name' => 'Staff Member',
            'email' => 'staff@exporthub.com',
            'role' => 'staff',
            'password' => bcrypt('password'),
        ]);
        $staff->roles()->attach($staffRole);

        // Create Test User (Attendee)
        $attendee = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'attendee',
        ]);
        $attendee->roles()->attach($attendeeRole);

        // Create sample events
        $event1 = Event::create([
            'title' => 'Nigerian Export Summit 2026',
            'description' => 'Join industry leaders and exporters for the biggest export summit of the year. Network, learn, and grow your export business.',
            'start_time' => now()->addDays(15),
            'end_time' => now()->addDays(17),
            'location' => 'Eko Convention Centre, Lagos',
            'cover_image_url' => null,
        ]);

        $event2 = Event::create([
            'title' => 'Agriculture Export Masterclass',
            'description' => 'Learn the best practices for exporting agricultural products from Nigeria to international markets.',
            'start_time' => now()->addDays(30),
            'end_time' => now()->addDays(30)->addHours(6),
            'location' => 'Abuja International Conference Centre',
            'cover_image_url' => null,
        ]);

        $event3 = Event::create([
            'title' => 'SME Export Financing Workshop',
            'description' => 'Discover funding opportunities and financing options for small and medium enterprises in the export sector.',
            'start_time' => now()->addDays(45),
            'end_time' => now()->addDays(45)->addHours(4),
            'location' => 'Transcorp Hilton, Abuja',
            'cover_image_url' => null,
        ]);

        // Create speakers for event 1
        Speaker::create([
            'event_id' => $event1->id,
            'name' => 'Dr. Amina Mohammed',
            'title' => 'Director General, WTO',
            'bio' => 'Dr. Amina Mohammed is a renowned economist and the current Director General of the World Trade Organization.',
            'photo_url' => 'https://ui-avatars.com/api/?name=Amina+Mohammed&background=random',
        ]);

        Speaker::create([
            'event_id' => $event1->id,
            'name' => 'John Doe',
            'title' => 'CEO, ExportPro',
            'bio' => 'John Doe has over 20 years of experience in international trade logistics.',
            'photo_url' => 'https://ui-avatars.com/api/?name=John+Doe&background=random',
        ]);

        // Create sessions for event 1
        EventSession::create([
            'event_id' => $event1->id,
            'title' => 'Opening Keynote: The Future of Nigerian Exports',
            'speaker' => 'Dr. Amina Mohammed',
            'start_time' => $event1->start_time,
            'end_time' => $event1->start_time->addHours(2),
            'location' => 'Main Hall',
        ]);

        EventSession::create([
            'event_id' => $event1->id,
            'title' => 'Panel: Breaking into African Markets',
            'speaker' => 'Multiple Speakers',
            'start_time' => $event1->start_time->addHours(3),
            'end_time' => $event1->start_time->addHours(5),
            'location' => 'Conference Room A',
        ]);

        // Seed Polls and Questions
        $this->call([
            PollSeeder::class,
            QuestionSeeder::class,
        ]);
    }
}
