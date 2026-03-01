<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super Admin - solo crear si no existe
        if (! User::where('email', 'info@alfredopineda.es')->exists()) {
            User::factory()->create([
                'name' => 'Alfredo Pineda',
                'email' => 'info@alfredopineda.es',
                'password' => 'keeper#01',
                'role' => 'admin',
                'is_active' => true,
            ]);
        }

        $this->call([
            GestorSeeder::class,
        ]);

        // Sample Nurses - solo crear si no existen
        $nurse1 = User::firstOrCreate(
            ['email' => 'nurse1@example.com'],
            [
                'name' => 'Nurse One',
                'role' => 'nurse',
                'is_active' => true,
                'monthly_shift_limit' => 10,
                'password' => 'password',
            ]
        );

        $nurse2 = User::firstOrCreate(
            ['email' => 'nurse2@example.com'],
            [
                'name' => 'Nurse Two',
                'role' => 'nurse',
                'is_active' => true,
                'monthly_shift_limit' => 12,
                'password' => 'password',
            ]
        );

        // Create some random nurses (solo si no existen)
        $randomNurses = User::factory(5)->create([
            'role' => 'nurse',
            'is_active' => true,
            'monthly_shift_limit' => 8,
        ])->each(function ($nurse) {
            $existingDates = \App\Models\AmbulanceShift::where('user_id', $nurse->id)
                ->pluck('date')
                ->map(fn ($date) => $date->format('Y-m-d'))
                ->toArray();

            $shiftCount = rand(1, 3);
            for ($i = 0; $i < $shiftCount; $i++) {
                $date = fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d');
                if (! in_array($date, $existingDates)) {
                    \App\Models\AmbulanceShift::firstOrCreate(
                        ['user_id' => $nurse->id, 'date' => $date],
                        ['status' => fake()->randomElement(\App\Enums\ShiftStatus::cases())]
                    );
                    $existingDates[] = $date;
                }
            }
        });

        // Shifts for Nurse One (solo crear si no existen turnos)
        if (\App\Models\AmbulanceShift::where('user_id', $nurse1->id)->count() === 0) {
            $dates = ['2026-03-10', '2026-03-12', '2026-03-15', '2026-03-18', '2026-03-20'];
            foreach (array_slice($dates, 0, 3) as $index => $date) {
                \App\Models\AmbulanceShift::firstOrCreate(
                    ['user_id' => $nurse1->id, 'date' => $date],
                    ['status' => \App\Enums\ShiftStatus::Pending]
                );
            }
            foreach (array_slice($dates, 3) as $date) {
                \App\Models\AmbulanceShift::firstOrCreate(
                    ['user_id' => $nurse1->id, 'date' => $date],
                    ['status' => \App\Enums\ShiftStatus::Accepted]
                );
            }
        }

        // Shifts for Nurse Two
        if (\App\Models\AmbulanceShift::where('user_id', $nurse2->id)->count() === 0) {
            $dates = ['2026-03-11', '2026-03-13', '2026-03-17', '2026-03-19', '2026-03-21'];
            foreach (array_slice($dates, 0, 2) as $date) {
                \App\Models\AmbulanceShift::firstOrCreate(
                    ['user_id' => $nurse2->id, 'date' => $date],
                    ['status' => \App\Enums\ShiftStatus::Rejected]
                );
            }
            foreach (array_slice($dates, 2) as $date) {
                \App\Models\AmbulanceShift::firstOrCreate(
                    ['user_id' => $nurse2->id, 'date' => $date],
                    ['status' => \App\Enums\ShiftStatus::EnReserva]
                );
            }
        }

        // Random shifts for other nurses (ya verificado en el each de arriba)
    }
}
