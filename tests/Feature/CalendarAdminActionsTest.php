<?php

namespace Tests\Feature;

use App\Filament\Widgets\ShiftCalendarWidget;
use App\Models\User;
use Carbon\Carbon;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Notification::fake();
});

it('admin can see user_id field in create action form', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $date = Carbon::parse('2026-11-15');

    $data = [
        'date' => $date->toIso8601String(),
        'allDay' => true,
        'view' => [
            'type' => 'dayGridMonth',
            'title' => 'November 2026',
            'currentStart' => $date->startOfMonth()->toIso8601String(),
            'currentEnd' => $date->endOfMonth()->toIso8601String(),
            'activeStart' => $date->startOfMonth()->toIso8601String(),
            'activeEnd' => $date->endOfMonth()->toIso8601String(),
        ],
        'tzOffset' => 0,
    ];
    $dateClickInfo = new DateClickInfo($data, false);

    Livewire::actingAs($admin)
        ->test(ShiftCalendarWidget::class)
        ->call('onDateClick', $dateClickInfo)
        ->assertActionMounted('create')
        ->assertFormFieldExists('user_id')
        ->assertFormFieldIsVisible('user_id');
});

it('nurse cannot see user_id field in create action form', function () {
    $nurse = User::factory()->create(['role' => 'nurse']);
    $date = Carbon::parse('2026-11-16');

    $data = [
        'date' => $date->toIso8601String(),
        'allDay' => true,
        'view' => [
            'type' => 'dayGridMonth',
            'title' => 'November 2026',
            'currentStart' => $date->startOfMonth()->toIso8601String(),
            'currentEnd' => $date->endOfMonth()->toIso8601String(),
            'activeStart' => $date->startOfMonth()->toIso8601String(),
            'activeEnd' => $date->endOfMonth()->toIso8601String(),
        ],
        'tzOffset' => 0,
    ];
    $dateClickInfo = new DateClickInfo($data, false);

    Livewire::actingAs($nurse)
        ->test(ShiftCalendarWidget::class)
        ->mountAction('create')
        ->assertFormFieldIsHidden('user_id');
});
