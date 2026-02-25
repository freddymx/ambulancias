<?php

namespace Database\Factories;

use App\Enums\ShiftStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AmbulanceShiftFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => ShiftStatus::Pending,
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ShiftStatus::Accepted,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ShiftStatus::Rejected,
        ]);
    }

    public function enReserva(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ShiftStatus::EnReserva,
        ]);
    }
}
