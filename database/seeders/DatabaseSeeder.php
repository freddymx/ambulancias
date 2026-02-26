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
        // Super Admin
        User::factory()->create([
            'name' => 'Alfredo Pineda',
            'email' => 'info@alfredopineda.es',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->call([
            GestorSeeder::class,
        ]);

        // Sample Nurses
        $nurse1 = User::factory()->create([
            'name' => 'Nurse One',
            'email' => 'nurse1@example.com',
            'role' => 'nurse',
            'is_active' => true,
            'monthly_shift_limit' => 10,
        ]);

        $nurse2 = User::factory()->create([
            'name' => 'Nurse Two',
            'email' => 'nurse2@example.com',
            'role' => 'nurse',
            'is_active' => true,
            'monthly_shift_limit' => 12,
        ]);

        // Create some random nurses
        $randomNurses = User::factory(5)->create([
            'role' => 'nurse',
            'is_active' => true,
            'monthly_shift_limit' => 8,
        ]);

        // Shifts for Nurse One
        \App\Models\AmbulanceShift::factory()->count(3)->create([
            'user_id' => $nurse1->id,
            'status' => \App\Enums\ShiftStatus::Pending,
        ]);
        \App\Models\AmbulanceShift::factory()->count(2)->create([
            'user_id' => $nurse1->id,
            'status' => \App\Enums\ShiftStatus::Accepted,
        ]);

        // Shifts for Nurse Two
        \App\Models\AmbulanceShift::factory()->count(2)->create([
            'user_id' => $nurse2->id,
            'status' => \App\Enums\ShiftStatus::Rejected,
        ]);
        \App\Models\AmbulanceShift::factory()->count(2)->create([
            'user_id' => $nurse2->id,
            'status' => \App\Enums\ShiftStatus::EnReserva,
        ]);

        // Random shifts for other nurses
        foreach ($randomNurses as $nurse) {
            \App\Models\AmbulanceShift::factory()->count(rand(1, 3))->create([
                'user_id' => $nurse->id,
                'status' => fake()->randomElement(\App\Enums\ShiftStatus::cases()),
            ]);
        }
    }
}
