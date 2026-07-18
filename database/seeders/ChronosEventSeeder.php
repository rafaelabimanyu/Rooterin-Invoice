<?php

namespace Database\Seeders;

use App\Models\ChronosEvent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ChronosEventSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::firstWhere('role', User::ROLE_OWNER);
        $admin = User::firstWhere('role', User::ROLE_ADMIN);

        ChronosEvent::create([
            'title' => 'Pengerjaan Fitur A & B (Internal Dev)',
            'description' => 'Selesaikan layout mobile & API untuk client management.',
            'start_date' => Carbon::create(2026, 5, 25),
            'end_date' => Carbon::create(2026, 5, 27),
            'color' => 'indigo',
            'status_type' => 'internal',
            'responsible_staff_id' => $owner?->id,
        ]);

        ChronosEvent::create([
            'title' => 'Meeting Bersama Tim Finansial Klien',
            'description' => 'Diskusi outstanding receivables dan workflow kwitansi.',
            'start_date' => Carbon::create(2026, 5, 30),
            'end_date' => Carbon::create(2026, 5, 30),
            'color' => 'emerald',
            'status_type' => 'meeting',
            'responsible_staff_id' => $admin?->id,
        ]);

        ChronosEvent::create([
            'title' => 'Implementasi Fitur AI Analytics Upgrade',
            'description' => 'Integrasikan parser Markdown & dynamic charts.',
            'start_date' => Carbon::create(2026, 6, 10),
            'end_date' => Carbon::create(2026, 6, 12),
            'color' => 'amber',
            'status_type' => 'draft',
            'responsible_staff_id' => $owner?->id,
        ]);
    }
}
