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
            'title' => 'NESS 2026 Sensitization Seminar - Port Harcourt',
            'city' => 'Port Harcourt',
            'zone' => 'South-South Zone',
            'date' => 'March 26, 2026',
            'venue' => 'PTDF Centre for Skills Development and Training (CSD), Omagwa-Igwuruta Road, Port Harcourt, Rivers State',
            'is_active' => true,
            'event_id' => null,
        ]);

        \App\Models\Summit::create([
            'title' => 'NESS 2026 Sensitization Seminar - Kano',
            'city' => 'Kano',
            'zone' => 'North-West Zone',
            'date' => 'April 2, 2026',
            'venue' => 'Bristol Palace Hotel, 54 Guda Abdullahi Street, Kano',
            'is_active' => true,
            'event_id' => null,
        ]);

        \App\Models\Summit::create([
            'title' => 'NESS 2026 Sensitization Seminar - Lagos',
            'city' => 'Lagos',
            'zone' => 'South-West Zone',
            'date' => 'March 30, 2026',
            'venue' => 'Lagos Marriot Hotel Ikeja, 122 Joel Ogunnaike Street',
            'is_active' => true,
            'event_id' => $lagosEvent ? $lagosEvent->id : null,
        ]);
    }
}
