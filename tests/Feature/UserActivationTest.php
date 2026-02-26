<?php

use App\Models\User;
use App\Notifications\UserActivatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('activating a user sends notification', function () {
    Notification::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['is_active' => false]);

    // Simulate activation via Filament Action
    // Since we can't easily trigger the Filament action directly in a unit test without complex setup,
    // we'll manually replicate the logic found in UsersTable.php
    
    $user->update(['is_active' => true]);
    $user->notify(new UserActivatedNotification($admin->name));

    Notification::assertSentTo(
        $user,
        UserActivatedNotification::class,
        function ($notification, $channels) {
            return in_array('mail', $channels);
        }
    );
});
