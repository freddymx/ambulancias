<?php

use App\Models\AmbulanceShift;
use App\Models\User;
use App\Notifications\AmbulanceShiftStatusChanged;
use App\Notifications\NewUserRegisteredNotification;
use App\Notifications\UserActivatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    Notification::fake();
});

it('sends user activated notification', function () {
    $user = User::factory()->create();

    $user->notify(new UserActivatedNotification('Admin User'));

    Notification::assertSentTo(
        $user,
        UserActivatedNotification::class,
        function ($notification) use ($user) {
            $mail = $notification->toMail($user);

            expect($mail->subject)->toBe('Tu cuenta ha sido activada');

            return true;
        }
    );
});

it('sends new user registered notification to admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $newUser = User::factory()->create();

    $admin->notify(new NewUserRegisteredNotification($newUser));

    Notification::assertSentTo(
        $admin,
        NewUserRegisteredNotification::class
    );
});

it('sends ambulance shift status changed notification', function () {
    $user = User::factory()->create();
    $shift = AmbulanceShift::factory()->create([
        'user_id' => $user->id,
    ]);

    $user->notify(new AmbulanceShiftStatusChanged($shift->date->format('Y-m-d'), $shift->status->value));

    Notification::assertSentTo(
        $user,
        AmbulanceShiftStatusChanged::class,
        function ($notification) use ($user) {
            $mail = $notification->toMail($user);

            expect($mail->subject)->toBe('Estado de tu turno de ambulancia');

            return true;
        }
    );
});
