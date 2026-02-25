<?php

namespace App\Providers;

use App\Models\AmbulanceShift;
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
    }
}
