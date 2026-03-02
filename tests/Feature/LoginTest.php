<?php

use App\Models\User;
use App\Filament\Pages\Auth\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('displays the login page', function () {
    get(route('filament.admin.auth.login'))
        ->assertStatus(200);
});

it('can login', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);
    
    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoErrors()
        ->assertRedirect(route('filament.admin.pages.dashboard'));
        
    $this->assertAuthenticatedAs($user);
});
