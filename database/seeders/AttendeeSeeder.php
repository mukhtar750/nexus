<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filepath = database_path('data/attendees.txt');
        
        if (!File::exists($filepath)) {
            $this->command->error("File not found at: {$filepath}");
            return;
        }

        $content = File::get($filepath);
        $lines = explode("\n", $content);

        $defaultPassword = Hash::make('password');
        $attendeeRole = Role::firstOrCreate(['name' => 'attendee']);
        
        $count = 0;
        $skipped = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip header or empty lines
            if (empty($line) || str_contains(strtolower($line), 'full name')) {
                continue;
            }

            $parts = preg_split('/\t+/', $line);
            
            if (count($parts) < 2) {
                $parts = preg_split('/\s{2,}/', $line);
            }

            if (count($parts) < 2) {
                $this->command->warn("Skipping invalid line: {$line}");
                $skipped++;
                continue;
            }

            $name = trim($parts[0]);
            $email = trim(end($parts));
            
            // Clean up email
            $email = strtolower(trim($email));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->command->warn("Invalid email format for line: {$line}");
                $skipped++;
                continue;
            }

            $user = User::firstWhere('email', $email);

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $defaultPassword,
                    'role' => 'attendee',
                ]);
                $count++;
            } else {
                $this->command->info("User already exists: {$email}");
                $skipped++;
            }

            // Ensure the user has the attendee role attached
            if (!$user->roles()->where('name', 'attendee')->exists()) {
                $user->roles()->attach($attendeeRole);
            }
        }

        $this->command->info("Successfully seeded {$count} new attendees. Skipped {$skipped} lines.");
    }
}
