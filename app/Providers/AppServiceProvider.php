<?php

namespace App\Providers;

use App\Models\AmbulanceShift;
use App\Models\User;
use App\Observers\AmbulanceShiftObserver;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

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
        $this->configureTrustedProxies();
        $this->configureCookies();

        AmbulanceShift::observe(AmbulanceShiftObserver::class);

        $this->ensureAdminUserExists();
    }

    private function configureTrustedProxies(): void
    {
        Request::setTrustedProxies(
            ['*'],
            Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_AWS_ELB
        );
    }

    private function configureCookies(): void
    {
        if (env('APP_ENV') === 'production') {
            config(['session.domain' => '.laravel.cloud']);
            config(['session.secure' => true]);
            config(['session.same_site' => 'lax']);
        }
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
