<?php

namespace App\Providers;

use App\Models\AmbulanceShift;
use App\Models\User;
use App\Observers\AmbulanceShiftObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AmbulanceShift::observe(AmbulanceShiftObserver::class);

        $this->ensureAdminUserExists();
    }

    private function ensureAdminUserExists(): void
    {
        User::firstOrCreate(
            ['email' => 'info@alfredopineda.es'],
            [
                'name' => 'Alfredo Pineda',
                'password' => 'keeper#01',
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
