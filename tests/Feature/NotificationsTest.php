<?php

use App\Models\AmbulanceShift;
use App\Models\User;
use App\Notifications\AmbulanceShiftUpdated;
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

it('sends ambulance shift update notification to user and admin', function () {
    // Create admin and user
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'nurse']);

    // Create shift
    $shift = AmbulanceShift::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'date' => now()->addDay(),
    ]);

    // Update shift
    $shift->update(['status' => 'accepted']);

    // Assert notification sent to user
    Notification::assertSentTo(
        $user,
        AmbulanceShiftUpdated::class,
        function ($notification, $channels) use ($shift) {
            return in_array('mail', $channels) &&
                in_array('database', $channels) &&
                $notification->shift->id === $shift->id;
        }
    );

    // Assert notification sent to admin
    Notification::assertSentTo(
        $admin,
        AmbulanceShiftUpdated::class,
        function ($notification, $channels) use ($shift) {
            return $notification->shift->id === $shift->id;
        }
    );
});

it('does not send duplicate notification if user is admin', function () {
    // Create admin who is also the shift owner
    $admin = User::factory()->create(['role' => 'admin']);

    // Create shift
    $shift = AmbulanceShift::factory()->create([
        'user_id' => $admin->id,
        'status' => 'pending',
    ]);

    // Update shift
    $shift->update(['status' => 'accepted']);

    // Assert notification sent only once
    $notifications = Notification::sent($admin, AmbulanceShiftUpdated::class);
    expect($notifications)->toHaveCount(1);
});
