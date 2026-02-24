<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SummitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Summit::truncate();

        // Get some event IDs for linking
        $lagosEvent = \App\Models\Event::where('title', 'Nigerian Export Summit 2026')->first();
        $abujaEvent = \App\Models\Event::where('title', 'Agriculture Export Masterclass')->first();

        \App\Models\Summit::create([
            'title' => 'Export Summit Port Harcourt',
            'city' => 'Port Harcourt',
            'zone' => 'South-South Zone',
            'date' => 'Oct 15 - 17, 2025',
            'venue' => 'Hotel Presidential',
            'is_active' => true,
            'event_id' => null,
        ]);

        \App\Models\Summit::create([
            'title' => 'Export Summit Kano',
            'city' => 'Kano',
            'zone' => 'North-West Zone',
            'date' => 'Nov 12 - 14, 2025',
            'venue' => 'Afficent Event Center',
            'is_active' => true,
            'event_id' => null,
        ]);

        \App\Models\Summit::create([
            'title' => 'Export Summit Abuja',
            'city' => 'Abuja',
            'zone' => 'North-Central Zone',
            'date' => 'Dec 05 - 07, 2025',
            'venue' => 'International Conference Centre',
            'is_active' => true,
            'event_id' => $abujaEvent ? $abujaEvent->id : null,
        ]);

        \App\Models\Summit::create([
            'title' => 'Export Summit Lagos',
            'city' => 'Lagos',
            'zone' => 'South-West Zone',
            'date' => 'Jan 20 - 22, 2026',
            'venue' => 'Eko Hotel & Suites',
            'is_active' => true,
            'event_id' => $lagosEvent ? $lagosEvent->id : null,
        ]);
    }
}
