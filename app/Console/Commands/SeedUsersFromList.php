<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class SeedUsersFromList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed-from-list {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed users from a given text file containing Name and Email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filepath = $this->argument('filepath');
        
        if (!File::exists($filepath)) {
            $this->error("File not found at: {$filepath}");
            return;
        }

        $content = File::get($filepath);
        $lines = explode("\n", $content);

        $defaultPassword = Hash::make('password');
        
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
                // Fallback to checking for multiple spaces if no tab exists
                $parts = preg_split('/\s{2,}/', $line);
            }

            if (count($parts) < 2) {
                $this->warn("Skipping invalid line: {$line}");
                $skipped++;
                continue;
            }

            $name = trim($parts[0]);
            $email = trim(end($parts)); // Email is usually the last part
            
            // Clean up email
            $email = strtolower(trim($email));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->warn("Invalid email format for line: {$line}");
                $skipped++;
                continue;
            }

            // Check if user exists
            if (User::where('email', $email)->exists()) {
                $this->info("User already exists: {$email}");
                $skipped++;
                continue;
            }

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => $defaultPassword,
            ]);

            $count++;
        }

        $this->info("Successfully seeded {$count} users. Skipped {$skipped} lines.");
    }
}
