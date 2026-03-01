<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class GestorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! User::where('email', 'gestor@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Gestor One',
                'email' => 'gestor@example.com',
                'role' => 'gestor',
                'is_active' => true,
            ]);
        }
    }
}
