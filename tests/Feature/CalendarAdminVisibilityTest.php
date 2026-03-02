<?php

namespace Tests\Feature;

use App\Enums\ShiftStatus;
use App\Filament\Widgets\ShiftCalendarWidget;
use App\Models\AmbulanceShift;
use App\Models\User;
use Carbon\Carbon;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    Notification::fake();
});

it('admin can see all shifts from other users with names', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $nurse = User::factory()->create(['role' => 'nurse']);
    $date1 = Carbon::parse('2026-12-01');
    $date2 = Carbon::parse('2026-12-02');
    $date3 = Carbon::parse('2026-12-03');

    AmbulanceShift::create([
        'user_id' => $nurse->id,
        'date' => $date1,
        'status' => ShiftStatus::Accepted,
    ]);

    AmbulanceShift::create([
        'user_id' => $nurse->id,
        'date' => $date2,
        'status' => ShiftStatus::Pending,
    ]);

    AmbulanceShift::create([
        'user_id' => $nurse->id,
        'date' => $date3,
        'status' => ShiftStatus::EnReserva,
    ]);

    $fetchInfo = [
        'start' => $date1->startOfMonth()->toIso8601String(),
        'startStr' => $date1->startOfMonth()->toIso8601String(),
        'end' => $date1->endOfMonth()->toIso8601String(),
        'endStr' => $date1->endOfMonth()->toIso8601String(),
        'tzOffset' => 0,
    ];

    Auth::login($admin);

    $fetchInfoObj = new FetchInfo($fetchInfo);
    $widget = new ShiftCalendarWidget;
    $events = $widget->getEvents($fetchInfoObj);

    expect($events)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($events)->toHaveCount(3);

    // Verify names are visible
    foreach ($events as $event) {
        expect($event->getTitle())->toContain($nurse->name);
    }
});

it('gestor can see all shifts from other users with names', function () {
    $gestor = User::factory()->create(['role' => 'gestor']);
    $nurse = User::factory()->create(['role' => 'nurse']);
    $date1 = Carbon::parse('2026-12-01');

    AmbulanceShift::create([
        'user_id' => $nurse->id,
        'date' => $date1,
        'status' => ShiftStatus::Pending,
    ]);

    $fetchInfo = [
        'start' => $date1->startOfMonth()->toIso8601String(),
        'startStr' => $date1->startOfMonth()->toIso8601String(),
        'end' => $date1->endOfMonth()->toIso8601String(),
        'endStr' => $date1->endOfMonth()->toIso8601String(),
        'tzOffset' => 0,
    ];

    Auth::login($gestor);

    $fetchInfoObj = new FetchInfo($fetchInfo);
    $widget = new ShiftCalendarWidget;
    $events = $widget->getEvents($fetchInfoObj);

    expect($events)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($events)->toHaveCount(1);
    expect($events->first()->getTitle())->toContain($nurse->name);
});
