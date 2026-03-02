<?php

use App\Filament\Resources\NurseShifts\Pages\ListNurseShifts;
use App\Models\AmbulanceShift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('nurse can view own shifts', function () {
    $nurse = User::factory()->create([
        'role' => 'nurse',
        'is_active' => true,
    ]);

    $ownShift = AmbulanceShift::factory()->create([
        'user_id' => $nurse->id,
        'date' => now()->addDay(),
    ]);

    $acceptedShift = AmbulanceShift::factory()->create([
        'user_id' => $nurse->id,
        'date' => now()->addDays(3),
        'status' => \App\Enums\ShiftStatus::Accepted,
    ]);

    $otherNurse = User::factory()->create([
        'role' => 'nurse',
        'is_active' => true,
    ]);

    $otherShift = AmbulanceShift::factory()->create([
        'user_id' => $otherNurse->id,
        'date' => now()->addDays(2),
    ]);

    $this->actingAs($nurse);

    Livewire::test(ListNurseShifts::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$ownShift, $acceptedShift])
        ->assertCanNotSeeTableRecords([$otherShift]);
});

test('admin cannot view nurse shifts page', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin);

    Livewire::test(ListNurseShifts::class)
        ->assertForbidden();
});
