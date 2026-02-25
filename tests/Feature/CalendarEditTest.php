<?php

use App\Enums\ShiftStatus;
use App\Models\AmbulanceShift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can update shift status via model', function () {
    $nurse = User::factory()->create(['role' => 'nurse']);
    $shift = AmbulanceShift::factory()->create([
        'user_id' => $nurse->id,
        'status' => ShiftStatus::Accepted,
    ]);

    $result = $shift->update(['status' => ShiftStatus::Rejected]);

    expect($result)->toBeTrue();

    $shift->refresh();
    expect($shift->status)->toBe(ShiftStatus::Rejected);
});

it('can update shift status from en_reserva to accepted', function () {
    $nurse = User::factory()->create(['role' => 'nurse']);
    $shift = AmbulanceShift::factory()->create([
        'user_id' => $nurse->id,
        'status' => ShiftStatus::EnReserva,
    ]);

    $result = $shift->update(['status' => ShiftStatus::Accepted]);

    expect($result)->toBeTrue();

    $shift->refresh();
    expect($shift->status)->toBe(ShiftStatus::Accepted);
});
