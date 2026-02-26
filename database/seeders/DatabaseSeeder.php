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
        User::factory()->create([
            'name' => 'Nurse One',
            'email' => 'nurse1@example.com',
            'role' => 'nurse',
            'is_active' => true,
            'monthly_shift_limit' => 10,
        ]);

        User::factory()->create([
            'name' => 'Nurse Two',
            'email' => 'nurse2@example.com',
            'role' => 'nurse',
            'is_active' => true,
            'monthly_shift_limit' => 12,
        ]);

        // Create some random nurses
        User::factory(5)->create([
            'role' => 'nurse',
            'is_active' => true,
            'monthly_shift_limit' => 8,
        ]);
    }
}
