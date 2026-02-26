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
        User::factory()->create([
            'name' => 'Gestor One',
            'email' => 'gestor@example.com',
            'role' => 'gestor',
            'is_active' => true,
        ]);
    }
}
