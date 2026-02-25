<?php

use App\Enums\ShiftStatus;
use App\Models\AmbulanceShift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can update shift status from accepted to rejected via model', function () {
    $nurse = User::factory()->create(['role' => 'nurse']);
    $shift = AmbulanceShift::factory()->create([
        'user_id' => $nurse->id,
        'status' => ShiftStatus::Accepted,
    ]);

    $shift->update(['status' => ShiftStatus::Rejected]);

    $shift->refresh();

    expect($shift->status)->toBe(ShiftStatus::Rejected);
});

it('can update shift status from pending to accepted via model', function () {
    $nurse = User::factory()->create(['role' => 'nurse']);
    $shift = AmbulanceShift::factory()->create([
        'user_id' => $nurse->id,
        'status' => ShiftStatus::Pending,
    ]);

    $shift->update(['status' => ShiftStatus::Accepted]);

    $shift->refresh();
    expect($shift->status)->toBe(ShiftStatus::Accepted);
});

it('prevents duplicate accepted shift on same date', function () {
    $nurse1 = User::factory()->create(['role' => 'nurse']);
    $nurse2 = User::factory()->create(['role' => 'nurse']);
    $date = '2026-05-15';

    AmbulanceShift::create([
        'user_id' => $nurse1->id,
        'date' => $date,
        'status' => ShiftStatus::Accepted,
    ]);

    expect(function () use ($date, $nurse2) {
        AmbulanceShift::create([
            'user_id' => $nurse2->id,
            'date' => $date,
            'status' => ShiftStatus::Accepted,
        ]);
    })->toThrow(\Illuminate\Validation\ValidationException::class);
});

it('allows en_reserva shift when accepted shift exists on same date', function () {
    $nurse1 = User::factory()->create(['role' => 'nurse']);
    $nurse2 = User::factory()->create(['role' => 'nurse']);
    $date = '2026-05-16';

    AmbulanceShift::create([
        'user_id' => $nurse1->id,
        'date' => $date,
        'status' => ShiftStatus::Accepted,
    ]);

    $reserveShift = AmbulanceShift::create([
        'user_id' => $nurse2->id,
        'date' => $date,
        'status' => ShiftStatus::EnReserva,
    ]);

    expect($reserveShift)->toBeInstanceOf(AmbulanceShift::class);
    expect($reserveShift->status)->toBe(ShiftStatus::EnReserva);
});

it('allows accepted shift when en_reserva shift exists on same date', function () {
    $nurse1 = User::factory()->create(['role' => 'nurse']);
    $nurse2 = User::factory()->create(['role' => 'nurse']);
    $date = '2026-05-17';

    AmbulanceShift::create([
        'user_id' => $nurse1->id,
        'date' => $date,
        'status' => ShiftStatus::EnReserva,
    ]);

    $acceptedShift = AmbulanceShift::create([
        'user_id' => $nurse2->id,
        'date' => $date,
        'status' => ShiftStatus::Accepted,
    ]);

    expect($acceptedShift)->toBeInstanceOf(AmbulanceShift::class);
    expect($acceptedShift->status)->toBe(ShiftStatus::Accepted);
});

it('prevents duplicate en_reserva shift on same date', function () {
    $nurse1 = User::factory()->create(['role' => 'nurse']);
    $nurse2 = User::factory()->create(['role' => 'nurse']);
    $date = '2026-05-18';

    AmbulanceShift::create([
        'user_id' => $nurse1->id,
        'date' => $date,
        'status' => ShiftStatus::EnReserva,
    ]);

    expect(function () use ($date, $nurse2) {
        AmbulanceShift::create([
            'user_id' => $nurse2->id,
            'date' => $date,
            'status' => ShiftStatus::EnReserva,
        ]);
    })->toThrow(\Illuminate\Validation\ValidationException::class);
});

it('prevents duplicate pending shift on same date', function () {
    $nurse1 = User::factory()->create(['role' => 'nurse']);
    $nurse2 = User::factory()->create(['role' => 'nurse']);
    $date = '2026-05-19';

    AmbulanceShift::create([
        'user_id' => $nurse1->id,
        'date' => $date,
        'status' => ShiftStatus::Pending,
    ]);

    expect(function () use ($date, $nurse2) {
        AmbulanceShift::create([
            'user_id' => $nurse2->id,
            'date' => $date,
            'status' => ShiftStatus::Pending,
        ]);
    })->toThrow(\Illuminate\Validation\ValidationException::class);
});
